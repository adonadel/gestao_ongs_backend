<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 * @property Permission $permissions
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('name', $permission)->exists();
    }
}
