<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\WarehouseLocation;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $baseProductQuery = Product::query();

        $stats = [
            'products' => (clone $baseProductQuery)->count(),
            'categories' => Category::query()->count(),
            'locations' => WarehouseLocation::query()->count(),
            'total_units' => (clone $baseProductQuery)->sum('quantity'),
            'inventory_value' => (clone $baseProductQuery)->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as total')->value('total'),
            'low_stock' => (clone $baseProductQuery)
                ->whereColumn('quantity', '<=', 'minimum_stock_level')
                ->where('quantity', '>', 0)
                ->count(),
            'out_of_stock' => (clone $baseProductQuery)->where('quantity', 0)->count(),
        ];

        $recentMovements = StockMovement::query()
            ->with(['product', 'user'])
            ->latest()
            ->take(8)
            ->get();

        $recentActivities = ActivityLog::query()
            ->with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard.index', [
            'user' => $user,
            'stats' => $stats,
            'recentMovements' => $recentMovements,
            'recentActivities' => $recentActivities,
        ]);
    }
}
