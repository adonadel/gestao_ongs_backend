<?php

namespace App\Models;


use App\Enums\FinancesTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Finances extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'animals_id',
        'description',
        'date',
        'value',
        'type',
    ];

    protected $casts = [
        'type' => FinancesTypeEnum::class,
    ];

    public function person(): HasMany
    {
        return $this->hasMany(People::class);
    }

    public function animal(): HasMany
    {
        return $this->hasMany(Animal::class);
    }
}
