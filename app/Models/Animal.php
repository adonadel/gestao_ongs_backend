<?php

namespace App\Models;


use App\Enums\AgeTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\SizeEnum;
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
    ];

    protected $casts = [
        'gender' => GenderEnum::class,
        'size' => SizeEnum::class,
        'age_type' => AgeTypeEnum::class,
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
