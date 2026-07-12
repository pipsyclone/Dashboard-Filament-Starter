<?php

namespace App\Filament\Pages;
use Illuminate\Support\Facades\Storage;

use App\Models\Setting;

use Filament\Pages\Page;
use BackedEnum;

// Form Components
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;

use Filament\Actions\Action;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;

use Filament\Notifications\Notification;

class Settings extends Page
{
    protected string $view = 'filament.pages.settings';
    protected static ?string $title = 'Application Settings';
    protected static ?string $slug = 'setting';

    // Sembunyikan dari sidebar
    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('ViewAny:Setting');
    }

    public ?array $data = [];
    public function mount(): void
    {
        $setting = Setting::first();
        $this->form->fill([
            'app_name'                    => $setting?->app_name ?? 'App Name Here',
            'app_name_short'              => $setting?->app_name_short ?? 'App Name Short Here',
            'app_color'                   => $setting?->app_color ?? '#6366f1',
            'app_logo'                    => is_array($setting?->app_logo)
                                                ? $setting->app_logo
                                                : (is_string($setting?->app_logo) && !empty($setting->app_logo)
                                                    ? [$setting->app_logo]  // konversi string lama ke array
                                                    : []),
            'app_favicon'                 => $setting?->app_favicon ?? '',
            'app_stempel'                 => $setting?->app_stempel ?? '',
            'app_background_login_image'  => $setting?->app_background_login_image ?? '',
            'youtube_link'                => $setting?->youtube_link ?? '',
            'instagram_link'              => $setting?->instagram_link ?? '',
            'tiktok_link'                 => $setting?->tiktok_link ?? '',
            'facebook_link'               => $setting?->facebook_link ?? '',
            'x_twitter_link'              => $setting?->x_twitter_link ?? '',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->columns(1)
            ->components([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        Section::make('Application Information')
                            ->description('Basic application settings such as name and short name.')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Grid::make()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('app_name')
                                            ->label('Application Name')
                                            ->required(),
                                        TextInput::make('app_name_short')
                                            ->label('Short Name')
                                            ->required(),
                                    ]),
                                Textarea::make('app_description')
                                    ->label('Application Description')
                                    ->rows(3)
                                    ->maxLength(255)
                                    ->placeholder('Enter a brief description of the application.'),
                            ]),
                        Section::make('Appearance')
                            ->description('Customize primary color and visual appearance of the application.')
                            ->icon('heroicon-o-swatch')
                            ->schema([
                                ColorPicker::make('app_color')
                                            ->label('Primary Color')
                                            ->required(),
                            ]),
                    ]),
                Section::make('Media')
                    ->description('Upload application logo, favicon, and login background images.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                FileUpload::make('app_logo')
                                    ->label('Application Logo')
                                    ->disk('public')
                                    ->directory('settings')
                                    ->image()
                                    ->multiple()
                                    ->reorderable()
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/*'])
                                    ->helperText('Recommended size: 512x512px. Max 2MB.')
                                    ->deleteUploadedFileUsing(function (string $file) {
                                        Storage::disk('public')->delete($file);
                                    }),
                                FileUpload::make('app_favicon')
                                    ->label('Application Favicon')
                                    ->disk('public')
                                    ->directory('settings')
                                    ->image()
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/*'])
                                    ->helperText('Recommended size: 192x192px. Max 2MB.'),
                                FileUpload::make('app_background_login_image')
                                    ->label('Login Background')
                                    ->disk('public')
                                    ->directory('settings')
                                    ->image()
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/*'])
                                    ->helperText('Recommended size: 1200x800px. Max 2MB.'),
                            ]),
                    ]),
                Section::make('Social Media')
                    ->description('Manage social media links for the application.')
                    ->icon('heroicon-o-share')
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('youtube_link')
                                    ->label('YouTube Link')
                                    ->url()
                                    ->placeholder('https://www.youtube.com/channel/...'),
                                TextInput::make('instagram_link')
                                    ->label('Instagram Link')
                                    ->url()
                                    ->placeholder('https://www.instagram.com/...'),
                            ]),
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('tiktok_link')
                                    ->label('TikTok Link')
                                    ->url()
                                    ->placeholder('https://www.tiktok.com/@...'),
                                TextInput::make('facebook_link')
                                    ->label('Facebook Link')
                                    ->url()
                                    ->placeholder('https://www.facebook.com/...'),
                            ]),
                        TextInput::make('x_twitter_link')
                            ->label('X (Twitter) Link')
                            ->url()
                            ->placeholder('https://twitter.com/...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
                Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $setting = Setting::first();
        $disk = Storage::disk('public');

        // File single fields
        $singleFileFields = ['app_favicon', 'app_background_login_image', 'app_stempel'];
        foreach ($singleFileFields as $field) {
            if ($setting && !empty($setting->{$field})) {
                $oldFile = $setting->{$field};
                $newFile = $data[$field] ?? null;
                if ($oldFile !== $newFile && $disk->exists($oldFile)) {
                    $disk->delete($oldFile);
                }
            }
        }

        // app_logo multiple — hapus file lama yang tidak ada di data baru
        if ($setting) {
            $oldLogos = is_array($setting->app_logo) ? $setting->app_logo : [];
            $newLogos = is_array($data['app_logo']) ? $data['app_logo'] : [];
            $deletedLogos = array_diff($oldLogos, $newLogos);
            foreach ($deletedLogos as $file) {
                if ($disk->exists($file)) {
                    $disk->delete($file);
                }
            }
        }

        if ($setting) {
            $setting->update($data);
        } else {
            Setting::create($data);
        }

        $this->redirect(request()->header('Referer') ?? url()->current());
    }
}
