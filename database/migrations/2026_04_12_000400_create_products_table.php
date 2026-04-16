<?php

use App\Enums\ProductStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('warehouse_location_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('minimum_stock_level')->default(0);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->string('image_path')->nullable();
            $table->enum('status', array_map(fn (ProductStatus $status) => $status->value, ProductStatus::cases()))
                ->default(ProductStatus::Active->value);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'status']);
            $table->index(['warehouse_location_id', 'status']);
            $table->index(['quantity', 'minimum_stock_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
