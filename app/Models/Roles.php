<?php

namespace App\Models;
use App\Traits\LogActivityTrait;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roles extends Model
{
    use LogActivityTrait, SoftDeletes;

    protected $table = 'roles';
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function booted()
    {
        static::created(function ($model) {
            try {
                Notification::make()
                    ->title('Success')
                    ->body('The role has been created successfully.')
                    ->success()
                    ->send();

                $model->logActivity('create', 'The role has been created successfully.');
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Error')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
                
                \Log::error('Role creation failed: ' . $e->getMessage());
            }
        });

        static::updated(function ($model) {
            try {
                Notification::make()
                    ->title('Success')
                    ->body('The role has been updated successfully.')
                    ->success()
                    ->send();

                $model->logActivity('update', 'The role has been updated successfully.');
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Error')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
                
                \Log::error('Role update failed: ' . $e->getMessage());
            }
        });

        static::deleted(function ($model) {
            try {
                Notification::make()
                    ->title('Success')
                    ->body('The role has been deleted successfully.')
                    ->success()
                    ->send();

                $model->logActivity('delete', 'The role has been deleted successfully.');
                return;
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Error')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
                
                \Log::error('Role delete failed: ' . $e->getMessage());
                return;
            }
        });
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_roles', 'role_id', 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permissions::class, 'role_has_permissions', 'role_id', 'permission_id');
    }
}
