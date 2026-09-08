<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public const STATUS_ACTIVE = 0;
    public const STATUS_INACTIVE = 1;
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'password',
        'must_change_password',
        'role_id',
        'team_id',
        'is_team_leader',
        'profile_photo_path',
        'nav_layout',

    ];

    protected $appends = ['status_label', 'profile_photo_url'];

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            default => 'Unknown',
        };
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo_path;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime:M d, Y, h:i A',
        'password' => 'hashed',
        'created_at' => 'datetime:M d, Y, h:i A',
        'updated_at' => 'datetime:M d, Y, h:i A',
        'is_team_leader' => 'boolean',
        'must_change_password' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(SettingRole::class, 'role_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
