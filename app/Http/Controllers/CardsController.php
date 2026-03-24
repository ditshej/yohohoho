<?php

namespace App\Http\Controllers;

use App\Enums\CardCategory;
use App\Enums\CardColor;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CardsController extends Controller
{
    public function index(Request $request): View
    {
        $query = Card::query();

        if ($request->filled('color')) {
            $query->where(function ($q) use ($request) {
                foreach ($request->input('color') as $color) {
                    $q->orWhere('colors', 'LIKE', '%"'.$color.'"%');
                }
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%'.$request->input('search').'%');
        }

        return view('cards.index', [
            'cards' => $query->orderBy('card_id')->get(),
            'colors' => CardColor::cases(),
            'categories' => CardCategory::cases(),
        ]);
    }

    public function show(Card $card): View
    {
        return view('cards.show', [
            'card' => $card,
        ]);
    }
}
