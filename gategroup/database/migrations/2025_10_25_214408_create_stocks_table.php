<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stocks', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('product_id');
            $table->date('expiration')->nullable();
            $table->integer('amount')->default(0);
            $table->integer('used')->default(0);
            $table->string('batch_number')->nullable();
            $table->decimal('current_volume', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stocks');
    }
};
