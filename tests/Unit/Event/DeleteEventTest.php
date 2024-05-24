<?php

namespace Event;

use App\Http\Services\Event\DeleteEventService;
use App\Models\Event;
use App\Models\User;
use App\Repositories\EventRepository;
use Tests\TestCase;

class DeleteEventTest extends TestCase
{
    public function testDeleteEvent(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1,
        ]);

        $this->actingAs($actor);

        $event = Event::factory()->create();

        (new DeleteEventService())->delete($event->id);

        $this->assertNull((new EventRepository())->getById($event->id));
    }
}
