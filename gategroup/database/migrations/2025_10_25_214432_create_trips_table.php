<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('trips', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('flight_number');
            $table->string('origin');
            $table->string('destination');
            $table->integer('no_stops')->default(0);
            $table->string('service_class')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('trips');
    }
};
