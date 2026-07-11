<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRoles;
use App\Filament\Resources\Roles\Pages\EditRoles;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Pages\ViewRoles;
use App\Filament\Resources\Roles\Schemas\RolesForm;
use App\Filament\Resources\Roles\Schemas\RolesInfolist;
use App\Filament\Resources\Roles\Tables\RolesTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

use App\Models\Roles;

class RolesResource extends Resource
{
    protected static ?string $model = Roles::class;

    protected static string|BackedEnum|null $navigationIcon = null;
    protected static string|UnitEnum|null $navigationGroup = 'User Management';

    public static function canAccess(): bool
    {
        return auth()->user()->can('viewAny', Roles::class);
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
