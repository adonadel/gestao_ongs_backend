<?php

namespace App\Models;

use App\Enums\PermissionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'display_name',
        'status',
    ];

    protected $casts = [
        'status' => PermissionStatusEnum::class,
    ];

    protected $hidden = [
      'pivot'
    ];
}
