<?php

namespace App\Models;

use App\Enums\PermissionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'status',
        'type',
    ];

    protected $casts = [
        'status' => PermissionStatusEnum::class,
    ];

    protected $hidden = [
      'pivot'
    ];
}
