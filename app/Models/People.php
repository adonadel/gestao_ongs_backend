<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class People extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'cpf_cnpj',
        'address_id',
        'profile_picture_id',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function profilePicture(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }
}
