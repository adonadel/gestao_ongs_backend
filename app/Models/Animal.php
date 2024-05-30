<?php

namespace App\Models;


use App\Enums\AnimalAgeTypeEnum;
use App\Enums\AnimalGenderEnum;
use App\Enums\AnimalSizeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'size',
        'age_type',
        'description',
        'tags',
        'location',
    ];

    protected $casts = [
        'gender' => AnimalGenderEnum::class,
        'size' => AnimalSizeEnum::class,
        'age_type' => AnimalAgeTypeEnum::class,
    ];

    public function adoption(): HasOne
    {
        return $this->hasOne(Adoption::class);
    }

    public function medias(): HasMany
    {
        return $this->hasMany(Media::class);
    }
}
