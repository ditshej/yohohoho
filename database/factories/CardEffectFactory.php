<?php

namespace Database\Factories;

use App\Enums\EffectType;
use App\Models\Card;
use App\Models\CardEffect;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CardEffect> */
class CardEffectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'card_id' => Card::factory(),
            'effect_type' => fake()->randomElement(EffectType::cases()),
            'amount' => fake()->numberBetween(1, 5),
            'condition' => fake()->optional()->sentence(),
        ];
    }
}
