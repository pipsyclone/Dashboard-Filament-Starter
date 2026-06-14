<?php

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Resources\Roles\RolesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

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

    public function afterSave(): void
    {
        auth()->user()->createLog(request(), 'Update Role', 'Successfully updated role: ' . $this->record->name);
    }
}
