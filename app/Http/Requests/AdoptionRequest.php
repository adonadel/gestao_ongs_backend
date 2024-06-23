<?php

namespace App\Http\Requests;

use App\Enums\AdoptionsStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdoptionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => ['required', Rule::in(AdoptionsStatusEnum::cases())],
            'description' => 'nullable|string',
            'user_id' => 'required|int|exists:users,id',
            'animal_id' => 'required|int|exists:animals,id'
        ];
    }
}
