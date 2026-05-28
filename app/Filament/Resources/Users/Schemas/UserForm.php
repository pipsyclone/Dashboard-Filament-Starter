<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi')
                    ->icon('heroicon-o-information-circle')
                    ->description('Informasi dasar tentang pengguna.')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Pengguna')
                            ->placeholder('Masukkan nama lengkap pengguna')
                            ->required()
                            ->validationMessages([
                                'required' => 'Nama pengguna wajib diisi.',
                            ]),
                        TextInput::make('email')
                            ->label('Email Pengguna')
                            ->placeholder('Masukkan alamat email pengguna')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'required' => 'Email pengguna wajib diisi.',
                                'email' => 'Format email tidak valid.',
                                'unique' => 'Email sudah digunakan oleh pengguna lain.',
                            ]),
                        TextInput::make('phone')
                            ->label('No. Telp Pengguna')
                            ->placeholder('Masukkan nomor telepon pengguna')
                            ->tel()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'required' => 'Nomor telepon pengguna wajib diisi.',
                                'tel' => 'Format nomor telepon tidak valid.',
                                'unique' => 'Nomor telepon sudah digunakan oleh pengguna lain.',
                            ]),
                    ]),
                Section::make('Kredensial')
                    ->icon('heroicon-o-lock-closed')
                    ->description('Informasi kredensial untuk autentikasi pengguna.')
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('ubah_password')
                            ->label('Ubah Password ?')
                            ->live(),
                        Grid::make()
                            ->columns(2)
                            ->visible(fn($livewire) => $livewire->data['ubah_password'] ?? false)
                            ->schema([
                                TextInput::make('password')
                                    ->label('Password')
                                    ->placeholder('Masukkan password pengguna')
                                    ->password()
                                    ->revealable()
                                    ->required(fn($livewire) => $livewire instanceof CreateUser)
                                    ->dehydrated(fn($state) => filled($state))
                                    ->validationMessages([
                                        'required' => 'Password pengguna wajib diisi.',
                                        'min' => 'Password pengguna minimal :min karakter.',
                                    ]),
                                TextInput::make('password_confirmation')
                                    ->label('Konfirmasi Password')
                                    ->placeholder('Masukkan konfirmasi password pengguna')
                                    ->password()
                                    ->revealable()
                                    ->required(fn($livewire) => $livewire instanceof CreateUser)
                                    ->dehydrated(false)
                                    ->same('password')
                                    ->validationMessages([
                                        'required' => 'Konfirmasi password pengguna wajib diisi.',
                                        'same' => 'Konfirmasi password tidak cocok dengan password.',
                                    ]),
                            ]),
                    ]),
                Section::make('Roles')
                    ->icon('heroicon-o-user-group')
                    ->description('Kelola role dan izin pengguna.')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                CheckboxList::make('roles')
                                    ->relationship(
                                        name: 'roles',
                                        titleAttribute: 'name',
                                    )
                                    ->columns(5)
                                    ->required()
                                    ->validationMessages([
                                        'required' => 'Setidaknya satu role harus dipilih.',
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
