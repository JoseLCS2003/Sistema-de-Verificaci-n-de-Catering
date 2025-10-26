<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tray_refills', function (Blueprint $table) {
            $table->id();
            $table->string('tray_id');
            $table->string('employee_id');
            $table->timestamp('refill_start')->nullable();
            $table->timestamp('refill_end')->nullable();
            $table->integer('total_items_added')->default(0);
            $table->timestamps();

            $table->foreign('tray_id')->references('id')->on('trays')->cascadeOnDelete();
            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tray_refills');
    }
};
