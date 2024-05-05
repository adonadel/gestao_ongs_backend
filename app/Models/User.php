<?php

namespace App\Models;

use App\Enums\UserStatusEnum;
use App\Traits\HasPersonEmail;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


/**
 *
 * @property Role $role
 * @property People $person
 */
class User extends Authenticatable implements JWTSubject, CanResetPasswordContract
{
    use HasApiTokens, HasFactory, Notifiable, HasPersonEmail, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'password',
        'status',
        'role_id',
        'people_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'status' => UserStatusEnum::class,
    ];

    protected $with = [
        'person',
        'person.address',
        'role',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(People::class, 'people_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->role->permissions();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->role->hasPermission($permission);
    }
}
