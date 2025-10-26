<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tray_contents', function (Blueprint $table) {
            $table->id();
            $table->string('tray_id');
            $table->string('stock_id');
            $table->integer('quantity')->default(0);
            $table->integer('original_quantity')->default(0);
            $table->timestamps();

            $table->foreign('tray_id')->references('id')->on('trays')->cascadeOnDelete();
            $table->foreign('stock_id')->references('id')->on('stocks')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tray_contents');
    }
};
