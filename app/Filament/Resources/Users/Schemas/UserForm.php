<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Information')
                    ->icon('heroicon-o-information-circle')
                    ->description('Basic information about the user.')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('User Name')
                            ->placeholder('Enter user name')
                            ->required(),
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('email')
                                    ->label('User Email')
                                    ->placeholder('Enter user email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                TextInput::make('phone')
                                    ->label('User Phone')
                                    ->placeholder('Enter user phone number')
                                    ->tel()
                                    ->required()
                                    ->unique(ignoreRecord: true),
                            ])
                    ]),
                Section::make('Credentials')
                    ->icon('heroicon-o-lock-closed')
                    ->description('Credentials information for user authentication.')
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('change_password')
                            ->label('Change Password ?')
                            ->live(),
                        Grid::make()
                            ->columns(2)
                            ->visible(fn($livewire) => $livewire->data['change_password'] ?? false)
                            ->schema([
                                TextInput::make('password')
                                    ->label('Password')
                                    ->placeholder('Enter user password')
                                    ->password()
                                    ->revealable()
                                    ->required(fn($livewire) => $livewire instanceof CreateUser)
                                    ->dehydrated(fn($state) => filled($state)),
                                TextInput::make('password_confirmation')
                                    ->label('Password Confirmation')
                                    ->placeholder('Enter user password confirmation')
                                    ->password()
                                    ->revealable()
                                    ->required(fn($livewire) => $livewire instanceof CreateUser)
                                    ->dehydrated(false)
                                    ->same('password'),
                            ]),
                    ]),
                Section::make('Roles')
                    ->icon('heroicon-o-user-group')
                    ->description('Manage user roles and permissions.')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('roles')
                            ->relationship(
                                name: 'roles',
                                titleAttribute: 'name',
                            )
                            ->searchable()
                            ->required()
                            ->preload(),
                    ]),
            ]);
    }
}
