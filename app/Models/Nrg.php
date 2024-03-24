<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nrg extends Model
{
    use HasFactory;

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
