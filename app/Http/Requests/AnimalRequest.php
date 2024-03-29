<?php

namespace App\Http\Requests;

use App\Enums\AgeTypeEnum;
use App\Enums\GenderEnum;
use App\Enums\SizeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class AnimalRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'gender' => ['required', Rule::in(GenderEnum::cases())],
            'size' => ['required', Rule::in(SizeEnum::cases())],
            'age_type' => ['required', Rule::in(AgeTypeEnum::cases())],
            'description' => 'nullable|string',
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
