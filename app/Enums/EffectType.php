<?php

namespace App\Enums;

enum EffectType: string
{
    case TrashFromDeck = 'TrashFromDeck';
    case ReturnFromTrash = 'ReturnFromTrash';
    case Draw = 'Draw';
}
