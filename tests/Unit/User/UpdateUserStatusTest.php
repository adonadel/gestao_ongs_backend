<?php

namespace Tests\Unit\User;

use App\Enums\UserStatusEnum;
use App\Http\Services\User\DisableUserService;
use App\Http\Services\User\EnableUserService;
use App\Models\User;
use Tests\TestCase;

class UpdateUserStatusTest extends TestCase
{

    public function testDisableUser(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $user = User::factory()->create();

        (new DisableUserService())->disable($user->id);

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

        (new EnableUserService())->enable($user->id);

        $this->assertEquals(UserStatusEnum::ENABLED, $user->fresh()->status);
    }
}
