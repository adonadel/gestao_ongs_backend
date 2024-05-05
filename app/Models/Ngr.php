<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ngr extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'address_id',
        'name',
        'cnpj',
        'description',
    ];

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }
}
