<?php

namespace Tests\Unit\Animal;

use App\Http\Services\Animal\DeleteAnimalService;
use App\Models\Animal;
use App\Models\User;
use App\Repositories\AnimalRepository;
use Tests\TestCase;

class DeleteAnimalTest extends TestCase
{
    public function testDeleteAnimal(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1,
        ]);

        $this->actingAs($actor);

        $animal = Animal::factory()->create();

        (new DeleteAnimalService())->delete($animal->id);

        $this->assertNull((new AnimalRepository())->getById($animal->id));
    }
}
