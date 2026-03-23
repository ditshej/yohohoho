<?php

namespace App\Services;

use App\Enums\CardColor;
use App\Models\Card;
use Illuminate\Support\Collection;

class CardImportService
{
    /** @param  array<int, CardColor>|null  $colorFilter */
    public function import(Collection $data, ?array $colorFilter = null): array
    {
        $imported = 0;
        $skipped = 0;

        foreach ($data as $cardData) {
            if ($this->shouldSkip($cardData, $colorFilter)) {
                $skipped++;

                continue;
            }

            Card::updateOrCreate(
                ['card_id' => $cardData['id']],
                [
                    'pack_id' => $cardData['pack_id'] ?? null,
                    'name' => $cardData['name'],
                    'rarity' => $cardData['rarity'],
                    'category' => $cardData['category'],
                    'colors' => $cardData['colors'],
                    'cost' => $cardData['cost'] ?? null,
                    'power' => $cardData['power'] ?? null,
                    'counter' => $cardData['counter'] ?? null,
                    'types' => $cardData['types'] ?? null,
                    'attributes' => $cardData['attributes'] ?? null,
                    'effect' => $cardData['effect'] ?? null,
                    'trigger' => $cardData['trigger'] ?? null,
                    'img_url' => $cardData['img_url'] ?? null,
                    'is_manually_created' => false,
                ],
            );

            $imported++;
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
        ];
    }

    /** @param  array<int, CardColor>|null  $colorFilter */
    private function shouldSkip(array $cardData, ?array $colorFilter): bool
    {
        if ($colorFilter === null) {
            return false;
        }

        $cardColors = $cardData['colors'] ?? [];
        $filterValues = array_map(fn (CardColor $color) => $color->value, $colorFilter);

        return empty(array_intersect($cardColors, $filterValues));
    }
}
