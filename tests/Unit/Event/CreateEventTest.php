<?php

namespace Event;

use App\Http\Services\Event\CreateEventService;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CreateEventTest extends TestCase
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

        $event = (new CreateEventService())->create($data);

        $this->assertEquals(data_get($data, 'name'), $event->name);
    }
}
