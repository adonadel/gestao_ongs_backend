<?php

namespace App\Http\Requests;

use App\Enums\AnimalAgeTypeEnum;
use App\Enums\AnimalCastrateEnum;
use App\Enums\AnimalGenderEnum;
use App\Enums\AnimalSizeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class AnimalRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'gender' => ['required', Rule::in(AnimalGenderEnum::cases())],
            'size' => ['required', Rule::in(AnimalSizeEnum::cases())],
            'age_type' => ['required', Rule::in(AnimalAgeTypeEnum::cases())],
            'castrate_type' => ['required', Rule::in(AnimalCastrateEnum::cases())],
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'tags' => 'nullable|string',
            'medias' => 'array|required',
            'medias.*.media' => [
                'required', File::types(['jpg', 'jpeg', 'png'])
            ],
            'medias.*.display_name' => 'nullable|string',
            'medias.*.description' => 'nullable|string',
        ];
    }
}
