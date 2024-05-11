<?php

namespace Tests\Unit\Animal;

use App\Enums\AnimalAgeTypeEnum;
use App\Enums\AnimalGenderEnum;
use App\Enums\AnimalSizeEnum;
use App\Http\Services\Animal\UpdateAnimalService;
use App\Models\Animal;
use App\Models\User;
use Tests\TestCase;

class UpdateAnimalTest extends TestCase
{
    public function testUpdateAnimal(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $animal = Animal::factory()->create();

        $data = [
            'name' => 'Farinha',
            'gender' => AnimalGenderEnum::MALE,
            'size' => AnimalSizeEnum::MEDIUM,
            'age_type' => AnimalAgeTypeEnum::CUB,
            'description' => 'Salsichinha',
            'tags' => 'salsichinha,caramelo,carinhoso'
        ];

        $updated = (new UpdateAnimalService())->update($data, $animal->id);

        $this->assertEquals(data_get($data, 'name'), $updated->name);
    }
}
