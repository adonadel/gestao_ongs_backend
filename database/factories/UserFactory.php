<?php

namespace Database\Factories;

use App\Enums\UserTypeEnum;
use App\Models\People;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $person = People::factory()->create();
        $role = Role::factory()->create();

        return [
            'password' => static::$password ??= Hash::make('password'),
            'people_id' => $person->id,
            'role_id' => $role->id,
            'type' => UserTypeEnum::INTERNAL
        ];
    }
}
