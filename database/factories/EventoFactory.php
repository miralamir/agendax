<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Evento;

class EventoFactory extends Factory
{
    protected $model = Evento::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(3, true),
            'category' => $this->faker->randomElement(['Arte', 'Música', 'Teatro', 'Cine', 'Literatura']),
            'subCategory' => $this->faker->word(),
            'startDate' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'endDate' => $this->faker->dateTimeBetween('+1 month', '+3 months'),
            'inaugurationDate' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'artist' => $this->faker->name(),
            'locationName' => $this->faker->company(),
            'venueAddress' => $this->faker->address(),
            'isPublished' => $this->faker->boolean(80), // 80% chance of being published
            'isFeatured' => $this->faker->boolean(20),
        ];
    }
}
