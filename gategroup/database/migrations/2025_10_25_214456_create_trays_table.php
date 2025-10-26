<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('trays', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('tray_code');
            $table->string('tray_type');
            $table->integer('capacity')->default(0);
            $table->string('status')->default('available');
            $table->string('current_trip')->nullable();
            $table->timestamp('last_refill')->nullable();
            $table->string('assigned_trip')->nullable();
            $table->timestamps();

            $table->foreign('current_trip')->references('id')->on('trips')->nullOnDelete();
            $table->foreign('assigned_trip')->references('id')->on('trips')->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('trays');
    }
};
