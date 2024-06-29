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
            'address' => 'nullable|array',
            'address.id' => 'nullable|int',
            'address.zip' => 'required|string',
            'address.street' => 'required|string',
            'address.number' => 'nullable|string',
            'address.neighborhood' => 'nullable|string',
            'address.city' => 'nullable|string',
            'address.state' => 'nullable|string',
            'address.complement' => 'nullable|string',
            'address.longitude' => 'nullable|string',
            'address.latitude' => 'nullable|string',
        ];
    }
}
