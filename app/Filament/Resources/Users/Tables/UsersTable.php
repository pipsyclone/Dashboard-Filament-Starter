<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Filters\SelectFilter;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // ->modifyQueryUsing(function ($query) {
            //     return $query->with('roles');
            // })
            ->poll('10s')
            ->deferLoading()
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('No. Telp')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_activity')
                    ->label('Status Activity')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            'online' => 'success',
                            'idle' => 'warning',
                            'offline' => 'danger',
                        };
                    })
                    ->searchable()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('last_activity')
                    ->label('Last Activity')
                    ->since()
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('roles')->relationship('roles', 'name')->multiple(),
                SelectFilter::make('status_activity')->options([
                    'online' => 'Online',
                    'idle' => 'Idle',
                    'offline' => 'Offline',
                ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
