<?php

namespace Event;

use App\Http\Services\Event\QueryEventService;
use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

class QueryEventTest extends TestCase
{
    public function testGetEvent(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        Event::factory(10)->create();

        $events = (new QueryEventService())->getEvents([]);

        $this->assertGreaterThanOrEqual(10, $events->count());
    }

    public function testGetEventById(): void
    {
        $actor = User::factory()->create([
            'role_id' => 1
        ]);

        $this->actingAs($actor);

        $event = Event::factory()->create();

        $actual = (new QueryEventService())->getEventById($event->id);

        $this->assertEquals($event->id, $actual->id);
    }
}
