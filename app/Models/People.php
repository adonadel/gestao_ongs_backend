<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class People extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'cpf_cnpj',
        'address_id',
        'profile_picture_id',
        'phone',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function profilePicture(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function person(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
