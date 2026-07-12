<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                TextEntry::make('roles.name')
                                    ->hiddenLabel()
                                    ->formatStateUsing(fn ($record) => $record->roles->pluck('name')->implode(', ') ?: 'This User Doesnt Have Roles')
                                    ->badge()
                                    ->alignCenter(),
                                TextEntry::make('status_activity')
                                    ->hiddenLabel()
                                    ->badge()
                                    ->colors([
                                        'success' => 'online',
                                        'warning' => 'idle',
                                        'danger' => 'offline',
                                    ])
                                    ->alignCenter(),
                                TextEntry::make('last_activity')
                                    ->hiddenLabel()
                                    ->formatStateUsing(fn ($record) => $record->last_activity ? $record->last_activity->diffForHumans() : 'Never')
                                    ->alignCenter(),
                            ]),
                    ]),
                Section::make()
                    ->schema([
                        ImageEntry::make('foto')
                            ->hiddenLabel()
                            ->disk('public')
                            ->alignCenter(),
                    ]),
                Section::make()
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name'),
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextEntry::make('email')
                                    ->label('Email'),
                                TextEntry::make('phone')
                                    ->label('Phone'),
                            ]),
                    ]),
            ]);
    }
}
