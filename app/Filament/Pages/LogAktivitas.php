<?php

namespace App\Filament\Pages;

use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;
use App\Models\LogAktivitas as LogAktivitasModel;

use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\TextEntry;

class LogAktivitas extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.log-aktivitas';
    protected static ?string $navigationLabel = 'Log Aktivitas';
    protected static ?string $title = 'Log Aktivitas';
    protected static string|UnitEnum|null $navigationGroup = 'Sistem';

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('ViewAny:LogAktivitas');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(LogAktivitasModel::with('user')->latest())
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->default('Anonim')
                    ->searchable(),
                TextColumn::make('aktivitas')
                    ->label('Aktivitas')
                    ->badge()
                    ->color('info'),
                TextColumn::make('ip_address')->label('IP Address'),
                TextColumn::make('location')->label('Lokasi')->default('-'),
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(40),
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->actions([
                Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->infolist([
                        TextEntry::make('user.name')->label('User'),
                        TextEntry::make('aktivitas')
                            ->label('Aktivitas')
                            ->badge()
                            ->color('info'),
                        TextEntry::make('ip_address')->label('IP Address'),
                        TextEntry::make('location')->label('Lokasi')->default('-'),
                        TextEntry::make('user_agent')->label('User Agent')->default('-'),
                        TextEntry::make('keterangan')->label('Keterangan'),
                        TextEntry::make('created_at')
                            ->label('Waktu')
                            ->dateTime('d M Y, H:i:s'),
                    ])
                    ->modalHeading('Detail Log Aktivitas')
                    ->modalWidth('lg')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}