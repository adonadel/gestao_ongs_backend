<?php

namespace Tests\Unit\User;

use App\Http\Services\User\DeleteUserService;
use App\Models\User;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    public function testDeleteUser(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1,
        ]);

        $this->actingAs($actor);

        $user = User::factory()->create();

        (new DeleteUserService())->delete($user->id);

        $this->assertSoftDeleted($user);
    }
}
