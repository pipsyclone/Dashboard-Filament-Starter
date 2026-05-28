<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RolesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoles extends CreateRecord
{
    protected static string $resource = RolesResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function afterCreate(): void
    {
        auth()->user()->createLogAktivitas(request(), 'Membuat Role', 'Berhasil membuat role baru: ' . $this->record->name);
    }
}
