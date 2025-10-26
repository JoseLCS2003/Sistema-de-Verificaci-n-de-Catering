<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tray_movements', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('tray_id');
            $table->string('stop_id');
            $table->string('movement_type');
            $table->timestamp('timestamp')->useCurrent();
            $table->timestamps();

            $table->foreign('tray_id')->references('id')->on('trays')->cascadeOnDelete();
            $table->foreign('stop_id')->references('id')->on('stops')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tray_movements');
    }
};
