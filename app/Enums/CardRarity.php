<?php

namespace App\Enums;

enum CardRarity: string
{
    case Leader = 'Leader';
    case Common = 'Common';
    case Uncommon = 'Uncommon';
    case Rare = 'Rare';
    case SuperRare = 'SuperRare';
    case SecretRare = 'SecretRare';
}
