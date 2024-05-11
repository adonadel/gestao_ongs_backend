<?php

namespace Tests\Unit\Animal;

use App\Enums\AnimalAgeTypeEnum;
use App\Enums\AnimalGenderEnum;
use App\Enums\AnimalSizeEnum;
use App\Http\Services\Animal\CreateAnimalService;
use App\Models\User;
use Tests\TestCase;

class CreateAnimalTest extends TestCase
{
    public function testCreateAnimal(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $data = [
            'name' => 'Farinha',
            'gender' => AnimalGenderEnum::MALE,
            'size' => AnimalSizeEnum::MEDIUM,
            'age_type' => AnimalAgeTypeEnum::CUB,
            'description' => 'Salsichinha',
            'tags' => 'salsichinha,caramelo,carinhoso'
        ];

        $animal = (new CreateAnimalService())->create($data);

        $this->assertEquals(data_get($data, 'name'), $animal->name);
    }
}
