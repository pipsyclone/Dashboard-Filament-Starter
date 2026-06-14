<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use BackedEnum;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';
    protected string|null $heading = 'Dashboard';
    
    public function getSubheading(): ?string
    {
        return __('Welcome to dashboard, ' . auth()->user()->name);
    }
}
