<?php

namespace Event;

use App\Enums\EventAgeTypeEnum;
use App\Enums\EventGenderEnum;
use App\Enums\EventSizeEnum;
use App\Http\Services\Event\UpdateEventService;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UpdateEventTest extends TestCase
{
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

        $updated = (new UpdateEventService())->update($data, $event->id);

        $this->assertEquals(data_get($data, 'name'), $updated->name);
    }
}
