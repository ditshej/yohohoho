<?php

namespace App\Http\Requests;

use App\Enums\CardColor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportCardsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:json'],
            'colors' => ['nullable', 'array'],
            'colors.*' => ['string', Rule::in(array_column(CardColor::cases(), 'value'))],
        ];
    }
}
