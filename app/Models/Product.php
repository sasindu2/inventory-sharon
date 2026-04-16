<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'warehouse_location_id',
        'name',
        'sku',
        'description',
        'quantity',
        'minimum_stock_level',
        'unit_price',
        'image_path',
        'status',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'status' => ProductStatus::class,
    ];

    protected $appends = [
        'stock_status',
        'location_label',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function warehouseLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function scopeFilter(Builder $query, array $filters): void
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $categoryId = $filters['category_id'] ?? null;
        $status = $filters['status'] ?? null;
        $stock = $filters['stock'] ?? null;
        $sort = $filters['sort'] ?? 'updated_desc';

        $query
            ->when($search !== '', function (Builder $builder) use ($search) {
                $builder->where(function (Builder $inner) use ($search) {
                    $inner
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('warehouseLocation', function (Builder $locationQuery) use ($search) {
                            $locationQuery
                                ->where('code', 'like', "%{$search}%")
                                ->orWhere('warehouse_area', 'like', "%{$search}%")
                                ->orWhere('rack_number', 'like', "%{$search}%")
                                ->orWhere('shelf_number', 'like', "%{$search}%");
                        });
                });
            })
            ->when($categoryId, fn (Builder $builder) => $builder->where('category_id', $categoryId))
            ->when($status, fn (Builder $builder) => $builder->where('status', $status))
            ->when($stock === 'low', function (Builder $builder) {
                $builder
                    ->whereColumn('quantity', '<=', 'minimum_stock_level')
                    ->where('quantity', '>', 0);
            })
            ->when($stock === 'out', fn (Builder $builder) => $builder->where('quantity', 0))
            ->when($stock === 'available', fn (Builder $builder) => $builder->where('quantity', '>', 0));

        match ($sort) {
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            'sku_asc' => $query->orderBy('sku'),
            'qty_low_high' => $query->orderBy('quantity'),
            'qty_high_low' => $query->orderByDesc('quantity'),
            'price_low_high' => $query->orderBy('unit_price'),
            'price_high_low' => $query->orderByDesc('unit_price'),
            'updated_asc' => $query->orderBy('updated_at'),
            default => $query->orderByDesc('updated_at'),
        };
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->quantity === 0) {
            return 'Out of Stock';
        }

        if ($this->quantity <= $this->minimum_stock_level) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    public function getLocationLabelAttribute(): string
    {
        return $this->warehouseLocation?->full_label ?? 'Unassigned';
    }

    public function isLowStock(): bool
    {
        return $this->quantity > 0 && $this->quantity <= $this->minimum_stock_level;
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity === 0;
    }
}
