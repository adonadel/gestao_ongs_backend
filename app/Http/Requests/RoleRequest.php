<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string',
            'permissions' => 'required_without:permissionsIds|array',
            'permissionsIds' => 'required_without:permissions|string'
        ];
    }
}
