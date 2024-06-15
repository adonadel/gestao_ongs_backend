<?php

namespace App\Http\Requests;

use App\Enums\UserTypeEnum;
use App\Models\Role;
use App\Repositories\UserRepository;
use App\Rules\UniqueCpfCnpj;
use App\Rules\UniqueEmail;
use App\Rules\ValidateCpfCnpj;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'password' => [
                'required', Password::min(6)->mixedCase()->letters()->numbers()
            ],
            'role_id' => ['nullable', 'int', Rule::exists(Role::class, 'id')],
            'person' => 'array|required',
            'person.name' => 'required|string',
            'person.email' => [
                'required',
                'email',
                new UniqueEmail(new UserRepository()),

            ],
            'person.cpf_cnpj' => [
                'required',
                'string',
                new UniqueCpfCnpj(new UserRepository()),
                new ValidateCpfCnpj()

            ],
            'person.phone' => 'nullable|string',
            'person.profile_picture_id' => 'nullable|int',
            'person.address_id' => 'nullable|int',
            'person.address' => 'nullable|array',
            'person.address.id' => 'nullable|int',
            'person.address.zip' => 'required|string',
            'person.address.street' => 'required|string',
            'person.address.number' => 'nullable|string',
            'person.address.neighborhood' => 'nullable|string',
            'person.address.city' => 'nullable|string',
            'person.address.state' => 'nullable|string',
            'person.address.complement' => 'nullable|string',
            'person.address.longitude' => 'nullable|string',
            'person.address.latitude' => 'nullable|string',
            'type' => ['nullable', Rule::in(UserTypeEnum::cases())],
        ];
    }
}
