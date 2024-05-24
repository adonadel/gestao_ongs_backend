<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EventTest extends TestCase
{
    public function testCreateEvent(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $data = [
            'name' => 'Coleta de doações ao ar livre',
            'location' => 'Ao lado do parque das nações',
            'event_date' => now(),
            'address' => [
                'zip' => '88.888-888',
                'street' => 'Av. Centenário',
                'city' => 'Criciúma',
                'state' => 'Santa Catarina',
                'latitude' => -28.684918,
                'longitude' => -49.3575162,
                'number' => '123',
            ],
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

        $response = $this->postJson('api/events', $data);

        $response->assertCreated();
    }

    public function testUpdateEvent(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $event = Event::factory()->create();

        $data = [
            'name' => 'Coleta de doações ao ar livre',
            'location' => 'Ao lado do parque das nações',
            'event_date' => now(),
            'address' => [
                'zip' => '88.888-888',
                'street' => 'Av. Centenário',
                'city' => 'Criciúma',
                'state' => 'Santa Catarina',
                'latitude' => -28.684918,
                'longitude' => -49.3575162,
                'number' => '123',
            ],
        ];

        $response = $this->putJson("api/events/{$event->id}", $data);

        $response->assertStatus(200);
        $this->assertEquals(data_get($data, 'name'), $event->fresh()->name);
    }

    public function testDeleteEvent(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1,
        ]);

        $this->actingAs($actor);

        $event = Event::factory()->create();

        $response = $this->delete("api/events/{$event->id}/delete");

        $response->assertStatus(200);
        $this->assertEquals('Evento excluído com sucesso!', $response->json('message'));
    }

    public function testGetEvents(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        Event::factory(10)->create();

        $response = $this->get('api/events');

        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(10, $response->getOriginalContent()->count());
    }

    public function testGetEventById(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $event = Event::factory()->create();

        $response = $this->get("api/events/{$event->id}/");

        $response->assertStatus(200);
        $this->assertEquals($event->id, $response->getOriginalContent()->id);
    }
}
