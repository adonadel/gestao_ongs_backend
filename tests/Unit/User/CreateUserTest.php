<?php

namespace Tests\Unit\User;

use App\Exceptions\ExternalUserCantCreateAdminUserException;
use App\Http\Services\User\CreateUserService;
use App\Models\User;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    public function testCreateUser(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $data = [
            'password' => 'Pw12345678',
            'person' => [
                'email' => 'teste@teste.com',
                'name' => 'teste',
                'cpf_cnpj' => '123.456.789-00'
            ],
            'role_id' => 1
        ];

        $user = (new CreateUserService())->create($data);

        $this->assertEquals(data_get($data, 'person.name'), $user->person->name);
    }

    public function testCreateUserExternalAsAdmin(): void
    {
        $this->expectException(ExternalUserCantCreateAdminUserException::class);
        $user = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($user);

        $data = [
            'password' => 'Pw12345678',
            'person' => [
                'email' => 'teste@teste.com',
                'name' => 'teste',
                'cpf_cnpj' => '123.456.789-00'
            ],
            'role_id' => 1
        ];

        (new CreateUserService())->create($data, true);
    }
}
