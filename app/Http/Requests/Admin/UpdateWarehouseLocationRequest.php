<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWarehouseLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        $location = $this->route('warehouse_location');

        return [
            'code' => ['required', 'string', 'max:255', Rule::unique('warehouse_locations', 'code')->ignore($location)],
            'warehouse_area' => ['required', 'string', 'max:255'],
            'rack_number' => ['required', 'string', 'max:255'],
            'shelf_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('warehouse_locations', 'shelf_number')
                    ->ignore($location)
                    ->where(fn ($query) => $query
                        ->where('warehouse_area', (string) $this->input('warehouse_area'))
                        ->where('rack_number', (string) $this->input('rack_number'))),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
