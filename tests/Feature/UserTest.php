<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Enums\UserStatusEnum;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
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

        $response = $this->postJson('api/users', $data);

        $response->assertCreated();
    }

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

        $response = $this->putJson("api/users/{$user->id}", $data);

        $response->assertStatus(200);
        $this->assertEquals(data_get($data, 'person.name'), $user->fresh()->person->name);
    }

    public function testDisableUser(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $user = User::factory()->create();

        $response = $this->patch("api/users/{$user->id}/disable");

        $response->assertStatus(200);
        $this->assertEquals(UserStatusEnum::DISABLED, $user->fresh()->status);
    }

    public function testEnableUser(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1,
        ]);

        $this->actingAs($actor);

        $user = User::factory()->create([
            'status' => UserStatusEnum::DISABLED
        ]);

        $response = $this->patch("api/users/{$user->id}/enable");

        $response->assertStatus(200);
        $this->assertEquals(UserStatusEnum::ENABLED, $user->fresh()->status);
    }

    public function testDeleteUser(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1,
        ]);

        $this->actingAs($actor);

        $user = User::factory()->create();

        $response = $this->delete("api/users/{$user->id}/delete");

        $response->assertStatus(200);
        $this->assertEquals('Usuário excluído com sucesso!', $response->json('message'));
    }

    public function testGetUsers(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        User::factory(10)->create();

        $response = $this->get('api/users');

        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(10, $response->getOriginalContent()->count());
    }

    public function testGetUserById(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $user = User::factory()->create();

        $response = $this->get("api/users/{$user->id}/");

        $response->assertStatus(200);
        $this->assertEquals($user->id, $response->getOriginalContent()->id);
    }
}
