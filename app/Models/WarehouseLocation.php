<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'warehouse_area',
        'rack_number',
        'shelf_number',
        'description',
    ];

    protected $appends = [
        'full_label',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getFullLabelAttribute(): string
    {
        return sprintf(
            '%s | Rack %s | Shelf %s',
            $this->warehouse_area,
            $this->rack_number,
            $this->shelf_number
        );
    }
}
