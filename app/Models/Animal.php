<?php

namespace App\Models;


use App\Enums\AnimalAgeTypeEnum;
use App\Enums\AnimalCastrateEnum;
use App\Enums\AnimalGenderEnum;
use App\Enums\AnimalSizeEnum;
use App\Enums\AnimalTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 *
 * @property Adoption $adoption
 * @property Media $medias
 */

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'size',
        'age_type',
        'castrate_type',
        'animal_type',
        'description',
        'tags',
        'location',
    ];

    protected $casts = [
        'gender' => AnimalGenderEnum::class,
        'size' => AnimalSizeEnum::class,
        'age_type' => AnimalAgeTypeEnum::class,
        'castrate_type' => AnimalCastrateEnum::class,
        'animal_type' => AnimalTypeEnum::class,
    ];

    public function adoption(): HasOne
    {
        return $this->hasOne(Adoption::class);
    }

    public function medias(): BelongsToMany
    {
        return $this->belongsToMany(Media::class)->orderBy('is_cover')->withPivot('is_cover');
    }
}
