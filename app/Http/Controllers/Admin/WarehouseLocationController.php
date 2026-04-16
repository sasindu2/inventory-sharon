<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreWarehouseLocationRequest;
use App\Http\Requests\Admin\UpdateWarehouseLocationRequest;
use App\Models\WarehouseLocation;
use App\Services\ActivityLogService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WarehouseLocationController extends Controller
{
    public function __construct(private readonly ActivityLogService $activityLogService)
    {
    }

    public function index(): View
    {
        return view('admin.warehouse-locations.index', [
            'locations' => WarehouseLocation::query()
                ->withCount('products')
                ->orderBy('warehouse_area')
                ->orderBy('rack_number')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.warehouse-locations.create', [
            'location' => new WarehouseLocation(),
        ]);
    }

    public function store(StoreWarehouseLocationRequest $request): RedirectResponse
    {
        $location = WarehouseLocation::query()->create($request->validated());

        $this->activityLogService->log(
            $request->user(),
            'warehouse_location_created',
            "Created warehouse location {$location->code}.",
            $location
        );

        return redirect()->route('admin.warehouse-locations.index')
            ->with('success', 'Warehouse location created successfully.');
    }

    public function edit(WarehouseLocation $warehouseLocation): View
    {
        return view('admin.warehouse-locations.edit', [
            'location' => $warehouseLocation,
        ]);
    }

    public function update(UpdateWarehouseLocationRequest $request, WarehouseLocation $warehouseLocation): RedirectResponse
    {
        $warehouseLocation->update($request->validated());

        $this->activityLogService->log(
            $request->user(),
            'warehouse_location_updated',
            "Updated warehouse location {$warehouseLocation->code}.",
            $warehouseLocation
        );

        return redirect()->route('admin.warehouse-locations.index')
            ->with('success', 'Warehouse location updated successfully.');
    }

    public function destroy(WarehouseLocation $warehouseLocation): RedirectResponse
    {
        try {
            $locationCode = $warehouseLocation->code;
            $warehouseLocation->delete();

            $this->activityLogService->log(
                request()->user(),
                'warehouse_location_deleted',
                "Deleted warehouse location {$locationCode}.",
                WarehouseLocation::class,
                ['code' => $locationCode]
            );

            return redirect()->route('admin.warehouse-locations.index')
                ->with('success', 'Warehouse location deleted successfully.');
        } catch (QueryException) {
            return redirect()->route('admin.warehouse-locations.index')
                ->with('error', 'Warehouse location cannot be deleted while products are assigned to it.');
        }
    }
}
