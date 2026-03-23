<?php

namespace App\Models;

use App\Enums\CardCategory;
use App\Enums\CardColor;
use App\Enums\CardRarity;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'category' => CardCategory::class,
            'rarity' => CardRarity::class,
            'colors' => AsEnumCollection::of(CardColor::class),
            'attributes' => 'array',
            'types' => 'array',
            'is_manually_created' => 'boolean',
        ];
    }

    public function effects(): HasMany
    {
        return $this->hasMany(CardEffect::class);
    }
}
