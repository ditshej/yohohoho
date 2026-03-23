<?php

namespace Database\Factories;

use App\Enums\CardCategory;
use App\Enums\CardColor;
use App\Enums\CardRarity;
use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Card> */
class CardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'card_id' => strtoupper(fake()->unique()->bothify('OP##-###')),
            'pack_id' => fake()->optional()->numerify('######'),
            'name' => fake()->word(),
            'rarity' => fake()->randomElement(CardRarity::cases()),
            'category' => fake()->randomElement(CardCategory::cases()),
            'colors' => [fake()->randomElement(CardColor::cases())],
            'cost' => fake()->numberBetween(0, 10),
            'power' => fake()->randomElement([null, 1000, 2000, 3000, 4000, 5000, 6000]),
            'counter' => fake()->randomElement([null, 1000, 2000]),
            'types' => [fake()->word()],
            'effect' => fake()->optional()->sentence(),
            'trigger' => fake()->optional()->sentence(),
            'img_url' => fake()->optional()->url(),
            'is_manually_created' => false,
        ];
    }

    public function leader(): static
    {
        return $this->state(fn () => [
            'category' => CardCategory::Leader,
            'rarity' => CardRarity::Leader,
            'cost' => null,
            'counter' => null,
        ]);
    }

    public function manuallyCreated(): static
    {
        return $this->state(fn () => [
            'is_manually_created' => true,
        ]);
    }
}
