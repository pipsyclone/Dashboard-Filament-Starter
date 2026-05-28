<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRoles;
use App\Filament\Resources\Roles\Pages\EditRoles;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Pages\ViewRoles;
use App\Filament\Resources\Roles\Schemas\RolesForm;
use App\Filament\Resources\Roles\Schemas\RolesInfolist;
use App\Filament\Resources\Roles\Tables\RolesTable;
use App\Models\Roles;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RolesResource extends Resource
{
    protected static ?string $model = Roles::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('ViewAny:Role');
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Pengguna';
    }

    protected static ?string $recordTitleAttribute = 'name';
    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Slug' => $record->slug,
            'Description' => $record->description,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return RolesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RolesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRoles::route('/create'),
            'view' => ViewRoles::route('/{record}'),
            'edit' => EditRoles::route('/{record}/edit'),
        ];
    }
}
