<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->boolean('requires_expiration')->default(false);
            $table->boolean('is_mergeable')->default(false);
            $table->decimal('unit_volume', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};
