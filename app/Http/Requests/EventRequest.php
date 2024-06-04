<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class EventRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'medias' => 'array|required',
            'medias.*.media' => [
                'required', File::types(['jpg', 'jpeg', 'png'])
            ],
            'medias.*.display_name' => 'nullable|string',
            'medias.*.description' => 'nullable|string',
            'medias.*.is_cover' => 'nullable|boolean',
        ];
    }
}
