<?php

namespace Tests\Unit\User;

use App\Http\Services\User\QueryUserService;
use App\Models\User;
use Tests\TestCase;

class QueryUserTest extends TestCase
{
    public function testGetUsers(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        User::factory(10)->create();

        $users = (new QueryUserService())->getUsers([]);

        $this->assertGreaterThanOrEqual(10, $users->count());
    }

    public function testGetUserById(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $user = User::factory()->create();

        $actual = (new QueryUserService())->getUserById($user->id);

        $this->assertEquals($user->id, $actual->id);
    }
}
