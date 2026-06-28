<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Traits\LogActivityTrait;

class Setting extends Model
{
    use LogActivityTrait;

    protected $table = 'settings';

    protected $casts = [
        'app_logo' => 'array',
    ];

    protected $fillable = [
        'app_name',
        'app_name_short',
        'app_color',
        'app_logo',
        'app_favicon',
        'app_stempel',
        'app_background_login_image',
        'youtube_link',
        'instagram_link',
        'tiktok_link',
        'facebook_link',
        'x_twitter_link',
    ];

    protected static function booted()
    {
        static::created(function ($model) {
            try {
                Notification::make()
                    ->title('Success')
                    ->body('The setting has been created successfully.')
                    ->success()
                    ->send();

                $model->logActivity('create', 'The setting has been created successfully.');
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Error')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
                
                \Log::error('Setting creation failed: ' . $e->getMessage());
            }
        });

        static::updated(function ($model) {
            try {
                Notification::make()
                    ->title('Success')
                    ->body('The setting has been updated successfully.')
                    ->success()
                    ->send();

                $model->logActivity('update', 'The setting has been updated successfully.');
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Error')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
                
                \Log::error('Setting update failed: ' . $e->getMessage());
            }
        });
    }
    
}
