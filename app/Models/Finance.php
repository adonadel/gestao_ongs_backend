<?php

namespace App\Models;


use App\Enums\FinanceTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Finance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'animal_id',
        'description',
        'date',
        'value',
        'type',
    ];

    protected $casts = [
        'type' => FinanceTypeEnum::class,
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
