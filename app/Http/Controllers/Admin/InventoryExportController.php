<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ActivityLogService;
use Illuminate\Http\StreamedResponse;

class InventoryExportController extends Controller
{
    public function __construct(private readonly ActivityLogService $activityLogService)
    {
    }

    public function products(): StreamedResponse
    {
        $fileName = 'inventory-export-'.now()->format('Ymd-His').'.csv';

        $this->activityLogService->log(
            request()->user(),
            'inventory_exported',
            'Exported inventory to CSV.',
            Product::class
        );

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Name',
                'SKU',
                'Category',
                'Quantity',
                'Minimum Stock Level',
                'Stock Status',
                'Warehouse Area',
                'Rack Number',
                'Shelf Number',
                'Unit Price',
                'Status',
            ]);

            Product::query()
                ->with(['category', 'warehouseLocation'])
                ->orderBy('name')
                ->chunk(200, function ($products) use ($handle) {
                    foreach ($products as $product) {
                        fputcsv($handle, [
                            $product->name,
                            $product->sku,
                            $product->category?->name,
                            $product->quantity,
                            $product->minimum_stock_level,
                            $product->stock_status,
                            $product->warehouseLocation?->warehouse_area,
                            $product->warehouseLocation?->rack_number,
                            $product->warehouseLocation?->shelf_number,
                            number_format((float) $product->unit_price, 2, '.', ''),
                            $product->status?->label(),
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
