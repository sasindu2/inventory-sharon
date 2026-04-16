<?php

namespace App\Http\Requests\Admin;

use App\Enums\StockMovementType;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreStockAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(StockMovementType::class)],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var Product|null $product */
            $product = $this->route('product');

            if (! $product) {
                return;
            }

            if (
                $this->input('type') === StockMovementType::Decrease->value
                && (int) $this->integer('quantity') > $product->quantity
            ) {
                $validator->errors()->add('quantity', 'The adjustment would reduce stock below zero.');
            }
        });
    }
}
