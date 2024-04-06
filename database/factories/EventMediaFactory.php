<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventMediaFactory extends Factory
{

    public function definition(): array
    {
        $event = Event::factory()->create();
        $media = Media::factory()->create();

        return [
            'animal_id' => $event->id,
            'media_id' => $media->id,
            'order' => fake()->unique()->randomNumber(),
        ];
    }
}
