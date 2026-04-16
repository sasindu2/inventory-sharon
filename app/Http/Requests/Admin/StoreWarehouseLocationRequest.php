<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWarehouseLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', 'unique:warehouse_locations,code'],
            'warehouse_area' => ['required', 'string', 'max:255'],
            'rack_number' => ['required', 'string', 'max:255'],
            'shelf_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('warehouse_locations', 'shelf_number')->where(fn ($query) => $query
                    ->where('warehouse_area', (string) $this->input('warehouse_area'))
                    ->where('rack_number', (string) $this->input('rack_number'))),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
