<?php

namespace App\Http\Requests;

use App\Enums\FinanceTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinanceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'nullable|integer|exists:users,id',
            'animal_id' => 'nullable|integer|exists:animals,id',
            'description' => 'nullable|string',
            'value' => 'required',
            'type' => ['required', Rule::in(FinanceTypeEnum::cases())],
        ];
    }
}
