<?php

namespace Animal;

use App\Http\Services\Animal\QueryAnimalService;
use App\Models\Animal;
use App\Models\User;
use Tests\TestCase;

class QueryAnimalTest extends TestCase
{
    public function testGetAnimal(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        Animal::factory(10)->create();

        $animals = (new QueryAnimalService())->getAnimals([]);

        $this->assertGreaterThanOrEqual(10, $animals->count());
    }

    public function testGetAnimalById(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $animal = Animal::factory()->create();

        $actual = (new QueryAnimalService())->getAnimalById($animal->id);

        $this->assertEquals($animal->id, $actual->id);
    }
}
