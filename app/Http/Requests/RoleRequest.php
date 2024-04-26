<?php

namespace App\Http\Requests;

use App\Enums\AgeTypeEnum;
use App\Enums\AnimalGenderEnum;
use App\Enums\SizeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class RoleRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'permissions' => 'required|array'
        ];
    }
}
