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
        return $schema
            ->components([
                Section::make('Informasi Umum')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Role')
                                    ->placeholder('contoh: Super Admin, Admin, Guest, Visitor, dll.')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Nama role yang akan ditampilkan di aplikasi.')
                                    ->validationMessages([
                                        'required' => 'Nama role wajib diisi.',
                                        'unique' => 'Nama role sudah digunakan. Silakan gunakan nama lain.',
                                    ]),
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
                                    ->helperText('Hanya huruf kecil, angka, dan underscore yang diperbolehkan.')
                                    ->validationMessages([
                                        'required' => 'Slug role wajib diisi.',
                                        'unique' => 'Slug role sudah digunakan. Silakan gunakan slug lain.',
                                        'regex' => 'Slug hanya boleh mengandung huruf kecil, angka, dan underscore.',
                                    ]),
                            ]),
                        Textarea::make('description')
                            ->label('Deskripsi Role')
                            ->placeholder('contoh: Lihat, Hapus, edit, view, dll.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Hak Akses')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                Section::make('Pengaturan Aplikasi')
                                    ->schema([
                                        CheckboxList::make('permissions_settings')
                                            ->relationship(
                                                name: 'permissions',
                                                titleAttribute: 'description',
                                                modifyQueryUsing: fn ($query) =>
                                                    $query->where('name', 'like', '%:Setting')
                                            )
                                            ->columns(2),
                                    ]),
                                Section::make('Data Pengguna')
                                    ->schema([
                                        CheckboxList::make('permissions_users')
                                            ->relationship(
                                                name: 'permissions',
                                                titleAttribute: 'description',
                                                modifyQueryUsing: fn ($query) =>
                                                    $query->where('name', 'like', '%:User')
                                            )
                                            ->columns(2),
                                    ]),
                                Section::make('Data Roles')
                                    ->schema([
                                        CheckboxList::make('permissions_roles')
                                            ->relationship(
                                                name: 'permissions',
                                                titleAttribute: 'description',
                                                modifyQueryUsing: fn ($query) =>
                                                    $query->where('name', 'like', '%:Role')
                                            )
                                            ->columns(2),
                                    ]),
                                Section::make('Backup Database')
                                    ->schema([
                                        CheckboxList::make('permissions_backup')
                                            ->relationship(
                                                name: 'permissions',
                                                titleAttribute: 'description',
                                                modifyQueryUsing: fn ($query) =>
                                                    $query->where('name', 'like', '%:DatabaseBackup')
                                            )
                                            ->columns(2),
                                    ]),
                                Section::make('Log Aktivitas')
                                    ->schema([
                                        CheckboxList::make('permissions_log_aktivitas')
                                            ->relationship(
                                                name: 'permissions',
                                                titleAttribute: 'description',
                                                modifyQueryUsing: fn ($query) =>
                                                    $query->where('name', 'like', '%:LogAktivitas')
                                            )
                                            ->columns(2),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
