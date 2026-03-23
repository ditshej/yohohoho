<?php

namespace Database\Seeders;

use App\Enums\CardCategory;
use App\Enums\CardColor;
use App\Enums\CardRarity;
use App\Enums\EffectType;
use App\Models\Card;
use App\Models\CardEffect;
use Illuminate\Database\Seeder;

class BrookLeaderSeeder extends Seeder
{
    public function run(): void
    {
        $brook = Card::updateOrCreate(
            ['card_id' => 'OP15-022'],
            [
                'name' => 'Brook',
                'pack_id' => null,
                'rarity' => CardRarity::Leader,
                'category' => CardCategory::Leader,
                'colors' => [CardColor::Green, CardColor::Black],
                'cost' => null,
                'power' => 5000,
                'counter' => null,
                'types' => ['Straw Hat Crew'],
                'effect' => 'Under the rules of this game, you do not lose when your deck has 0 cards. You lose at the end of the turn in which your deck becomes 0 cards. [Activate: Main] [Once Per Turn] Trash 4 cards from the top of your deck. Then, if your deck has 0 cards, set up to 1 of your Characters as active.',
                'trigger' => null,
                'img_url' => 'https://en.onepiece-cardgame.com/images/cardlist/card/OP15-022.png',
                'is_manually_created' => true,
            ],
        );

        CardEffect::updateOrCreate(
            ['card_id' => $brook->id, 'effect_type' => EffectType::TrashFromDeck],
            ['amount' => 4, 'condition' => null],
        );
    }
}
