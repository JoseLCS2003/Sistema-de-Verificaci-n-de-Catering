<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stops', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('trip_id');
            $table->integer('stop_number');
            $table->string('airport_code');
            $table->timestamp('scheduled_time');
            $table->timestamps();

            $table->foreign('trip_id')->references('id')->on('trips')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stops');
    }
};
