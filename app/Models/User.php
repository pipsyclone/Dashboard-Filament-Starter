<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'foto',
        'name',
        'username',
        'email',
        'phone',
        'password',
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
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'user_has_roles', 'user_id', 'role_id');
    }

    public function hasRole($roles): bool
    {
        return $this->roles()->whereIn('slug', (array) $roles)->exists();
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionName) {
                $query->where('name', $permissionName);
            })
            ->exists();
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    public static function createLogAktivitas(
        Request $request,
        string $aktivitas,
        ?string $keterangan = null,
    ): void {
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => $aktivitas,
            'ip_address' => $request->realIp(),
            'location' => $request->ipLocation(),
            'user_agent' => $request->userAgent(),
            'keterangan' => $keterangan,
        ]);
    }
}
