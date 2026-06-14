<?php

namespace App\Filament\Resources\Roles\Schemas;
use Illuminate\Support\Str;
use Filament\Schemas\Components\Utilities\Set;

use App\Models\Permissions;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\CheckboxList;

class RolesForm
{
    public static function configure(Schema $schema): Schema
    {
        $permissionSections = [];
        try {
            $permissions = Permissions::all();
            $groupedPermissions = $permissions->groupBy(function ($permission) {
                $parts = explode(':', $permission->name);
                return $parts[1] ?? 'Lainnya';
            });

            foreach ($groupedPermissions as $resource => $perms) {
                // Buat judul yang lebih ramah dibaca, misal 'DatabaseBackup' jadi 'Database Backup'
                $title = preg_replace('/(?<!^)[A-Z]/', ' $0', $resource);
                
                $permissionSections[] = Section::make($title)
                    ->schema([
                        CheckboxList::make("permissions_" . strtolower($resource))
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'description',
                                modifyQueryUsing: fn ($query) =>
                                    $query->where('name', 'like', "%:{$resource}")
                            )
                            ->bulkToggleable()
                            ->columns(2),
                    ]);
            }
        } catch (\Exception $e) {
            // Abaikan jika tabel permissions belum ada (misal saat proses migrate awal)
        }

        return $schema
            ->components([
                Section::make('Informasi Umum')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Role Name')
                                    ->placeholder('contoh: Super Admin, Admin, Guest, Visitor, dll.')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Name of the role that will be displayed in the application.'),
                                TextInput::make('slug')
                                    ->label('Slug Role')
                                    ->placeholder('contoh: super_admin, admin, guest, visitor, dll.')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Set $set, ?string $state) {
                                        $set('slug', Str::of($state)->slug('_')->toString());
                                    })
                                    ->regex('/^[a-z0-9_]+$/')
                                    ->helperText('Only lowercase letters, numbers, and underscores are allowed.'),
                            ]),
                        Textarea::make('description')
                            ->label('Role Description')
                            ->placeholder('Example: View, Delete, Edit, etc.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Access Permissions')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->columns(3)
                            ->schema($permissionSections),
                    ]),
            ]);
    }
}
