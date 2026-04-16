<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\ActivityLogService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(private readonly ActivityLogService $activityLogService)
    {
    }

    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::query()
                ->withCount('products')
                ->orderBy('name')
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'category' => new Category(),
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $category = Category::query()->create($validated);

        $this->activityLogService->log(
            $request->user(),
            'category_created',
            "Created category {$category->name}.",
            $category
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        $this->activityLogService->log(
            $request->user(),
            'category_updated',
            "Updated category {$category->name}.",
            $category
        );

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            $categoryName = $category->name;
            $category->delete();

            $this->activityLogService->log(
                request()->user(),
                'category_deleted',
                "Deleted category {$categoryName}.",
                Category::class,
                ['name' => $categoryName]
            );

            return redirect()->route('admin.categories.index')
                ->with('success', 'Category deleted successfully.');
        } catch (QueryException) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Category cannot be deleted while products are assigned to it.');
        }
    }
}
