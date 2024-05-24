<?php

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Enums\AnimalAgeTypeEnum;
use App\Enums\AnimalGenderEnum;
use App\Enums\AnimalSizeEnum;
use App\Models\Animal;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AnimalTest extends TestCase
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
            'tags' => 'salsichinha,caramelo,carinhoso',
            'medias' => [
                [
                    'media' => UploadedFile::fake()->image('farinha.jpg'),
                    'display_name' => 'Farinha',
                    'description' => 'Salsichinha chamado farinha sorrindo',
                ],
                [
                    'media' => UploadedFile::fake()->image('farinha1.jpg'),
                    'display_name' => 'Farinha',
                    'description' => 'Salsichinha chamado farinha deitado',
                ]
            ]
        ];

        $response = $this->postJson('api/animals', $data);

        $response->assertCreated();
    }

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

        $response = $this->putJson("api/animals/{$animal->id}", $data);

        $response->assertStatus(200);
        $this->assertEquals(data_get($data, 'name'), $animal->fresh()->name);
    }

    public function testDeleteAnimal(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1,
        ]);

        $this->actingAs($actor);

        $animal = Animal::factory()->create();

        $response = $this->delete("api/animals/{$animal->id}/delete");

        $response->assertStatus(200);
        $this->assertEquals('Animal excluído com sucesso!', $response->json('message'));
    }

    public function testGetAnimals(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        Animal::factory(10)->create();

        $response = $this->get('api/animals');

        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(10, $response->getOriginalContent()->count());
    }

    public function testGetAnimalById(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $animal = Animal::factory()->create();

        $response = $this->get("api/animals/{$animal->id}/");

        $response->assertStatus(200);
        $this->assertEquals($animal->id, $response->getOriginalContent()->id);
    }
}
