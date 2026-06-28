<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RolesResource;
use Filament\Resources\Pages\EditRecord;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;

use Filament\Notifications\Notification;

class EditRoles extends EditRecord
{
    protected static string $resource = RolesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    public function getRedirectUrl(): string
    {
        return request()->header('Referer') ?? static::getResource()::getUrl('index');
    }

    public function getSavedNotification(): ?Notification
    {
        return null;
    }
}
