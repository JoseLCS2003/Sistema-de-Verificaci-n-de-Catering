<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_merges', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('source_stock_1');
            $table->string('source_stock_2');
            $table->string('result_stock');
            $table->string('merged_by');
            $table->timestamp('merge_date')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('source_stock_1')->references('id')->on('stocks')->cascadeOnDelete();
            $table->foreign('source_stock_2')->references('id')->on('stocks')->cascadeOnDelete();
            $table->foreign('result_stock')->references('id')->on('stocks')->cascadeOnDelete();
            $table->foreign('merged_by')->references('id')->on('employees')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stock_merges');
    }
};
