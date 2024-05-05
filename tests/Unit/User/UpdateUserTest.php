<?php

namespace Tests\Unit\User;

use App\Http\Services\User\UpdateUserService;
use App\Models\User;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    public function testUpdateUser(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $user = User::factory()->create();

        $data = [
            'password' => 'Pw12345678',
            'person' => [
                'id' => $user->person->id,
                'email' => 'teste@teste.com',
                'name' => 'teste',
                'cpf_cnpj' => '123.456.789-00'
            ],
            'role_id' => 1
        ];

        $updated = (new UpdateUserService())->update($data, $user->id);

        $this->assertEquals(data_get($data, 'person.name'), $updated->person->name);
    }
}
