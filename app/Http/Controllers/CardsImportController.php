<?php

namespace App\Http\Controllers;

use App\Enums\CardColor;
use App\Http\Requests\ImportCardsFromApiRequest;
use App\Http\Requests\ImportCardsRequest;
use App\Services\CardImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class CardsImportController extends Controller
{
    public function create(): View
    {
        return view('cards-import.create', [
            'colors' => CardColor::cases(),
        ]);
    }

    public function store(ImportCardsRequest $request, CardImportService $service): RedirectResponse
    {
        $json = file_get_contents($request->file('file')->getRealPath());
        $decoded = json_decode($json, true);

        if (! is_array($decoded)) {
            return back()->withErrors(['file' => 'The file does not contain valid card data.']);
        }

        $summary = $service->import(collect($decoded), $this->resolveColorFilter($request));

        return redirect()
            ->route('cards.index')
            ->with('success', "Import complete: {$summary['imported']} imported, {$summary['skipped']} skipped.");
    }

    public function storeFromApi(ImportCardsFromApiRequest $request, CardImportService $service): RedirectResponse
    {
        $response = Http::timeout(5)->get($request->input('url'));

        if (! $response->successful()) {
            return back()->withErrors(['url' => 'The API could not be reached. Please try again.']);
        }

        $cards = $response->json();

        if (! is_array($cards) || ! array_is_list($cards)) {
            return back()->withErrors(['url' => 'The API did not return a valid list of cards.']);
        }

        $summary = $service->import(collect($cards), $this->resolveColorFilter($request));

        return redirect()
            ->route('cards.index')
            ->with('success', "Import complete: {$summary['imported']} imported, {$summary['skipped']} skipped.");
    }

    /** @return array<int, CardColor>|null */
    private function resolveColorFilter(ImportCardsFromApiRequest|ImportCardsRequest $request): ?array
    {
        if (! $request->filled('colors')) {
            return null;
        }

        return array_map(
            fn (string $color) => CardColor::from($color),
            $request->input('colors'),
        );
    }
}
