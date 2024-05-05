<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Repositories\UserRepository;
use App\Rules\UniqueCpfCnpj;
use App\Rules\UniqueEmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'password' => [
                'required', Password::min(6)->mixedCase()->letters()->numbers()
            ],
            'role_id' => ['required', 'int', Rule::exists(Role::class, 'id')],
            'person' => 'array|required',
            'person.name' => 'required|string',
            'person.email' => ['required', 'email', new UniqueEmail(new UserRepository())],
            'person.cpf_cnpj' => ['required', 'string', new UniqueCpfCnpj(new UserRepository())],
        ];
    }
}
