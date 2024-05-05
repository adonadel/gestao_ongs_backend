<?php

namespace Tests\Unit;

use App\Http\Services\User\CreateUserService;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testCreateUser(): void
    {
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
            'role_id' =>2
        ];

        $createdUser = (new CreateUserService())->create($data);

        $this->assertEquals(data_get($data, 'person.name'), $createdUser->person->name);
    }
}
