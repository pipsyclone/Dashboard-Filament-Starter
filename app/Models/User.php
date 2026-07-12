<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;

use Database\Factories\UserFactory;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'foto',
        'name',
        'email',
        'phone',
        'password',
        'status_activity',
        'last_activity',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity' => 'datetime',
        ];
    }

    // Methods
    public function canAccessPanel(Panel $panel): bool
    {
        return true; // Allow all users to access the panel for now
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        if (str_starts_with($this->foto, 'http')) {
            return $this->foto;
        }

        return Storage::disk('public')->url($this->foto);
    }

    public function getFilamentName(): string
    {
        return "{$this->name}";
    }

    public function hasRoles($roles): array
    {
        return $this->roles()->whereIn('slug', (array) $roles)->pluck('slug')->toArray();
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionName) {
                $query->where('name', $permissionName);
            })
            ->exists();
    }

    public static function createLog(
        Request $request,
        string $activity,
        ?string $description = null,
    ): void {
        ActivityLogs::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'ip_address' => $request->ipLocation(),
            'user_agent' => $request->userAgent(),
            'description' => $description,
        ]);
    }

    // Relationship
    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'user_has_roles', 'user_id', 'role_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLogs::class);
    }
}
