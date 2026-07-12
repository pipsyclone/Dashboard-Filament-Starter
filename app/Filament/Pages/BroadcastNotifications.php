<?php

namespace App\Filament\Pages;
use App\Traits\LogActivityTrait;
use Filament\Pages\Page;

use App\Models\User;

use Filament\Notifications\Notification;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

use Filament\Actions\Action;

class BroadcastNotifications extends Page implements HasForms
{
    use InteractsWithForms, LogActivityTrait;

    protected string $view = 'filament.pages.broadcast-notifications';
    protected static ?string $title = 'Broadcast Notifications';
    protected static ?string $slug = 'broadcast-notifications';

    // Sembunyikan dari sidebar
    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return true;
        // return auth()->check() && auth()->user()->hasPermission('ViewAny:BroadcastNotifications');
    }

    public ?array $data = [];
    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns(2)
            ->components([
                Section::make('Broadcast Notifications')
                    ->icon('heroicon-o-speaker-wave')
                    ->description('Send broadcast notifications to all users.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Notification Title')
                            ->required(),
                        Textarea::make('message')
                            ->label('Notification Message')
                            ->rows(4)
                            ->required(),
                    ]),
                Section::make('Notification Settings')
                    ->icon('heroicon-o-speaker-wave')
                    ->description('Configure the settings for the broadcast notification.')
                    ->schema([
                        Select::make('send_to')
                            ->label('Send To')
                            ->options([
                                'all' => 'All Users',
                                'admin' => 'Admin Users',
                                'regular' => 'Regular Users',
                            ])
                            ->required(),
                        Select::make('type')
                            ->label('Notification Type')
                            ->options([
                                'success' => 'Success',
                                'warning' => 'Warning',
                                'danger' => 'Danger',
                                'info' => 'Info',
                            ])
                            ->required(),
                    ]),
            ]);
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Send Notification')
                ->requiresConfirmation()
                ->submit('save'),
        ];
    }

    public function save()
    {
        $data = $this->form->getState();

        try {
            // Filter users berdasarkan send_to option
            $users = $this->getTargetUsers($data['send_to']);
            
            // Kirim notifikasi ke setiap user
            foreach ($users as $user) {
                Notification::make()
                    ->title($data['title'])
                    ->body($data['message'])
                    ->{$data['type']}()
                    ->sendToDatabase($user);
            }

            $this->logActivity('broadcast', 'Broadcast notification sent to ' . count($users) . ' user(s).');

            Notification::make()
                ->title('Success')
                ->body('Broadcast notification sent to ' . count($users) . ' user(s).')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
            
            \Log::error('Broadcast notification failed: ' . $e->getMessage());
        }
    }

    private function getTargetUsers(string $sendTo)
    {
        return match($sendTo) {
            'all' => User::query()->get(),
            'admin' => User::query()
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })
                ->get(),
            'regular' => User::query()
                ->whereHas('roles', function ($query) {
                    $query->whereNotIn('name', ['admin']);
                })
                ->get(),
            default => User::query()->get(),
        };
    }
}
