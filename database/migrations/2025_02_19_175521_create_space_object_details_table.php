<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('space_object_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('space_object_id')->constrained();

            $table->string('impact_date_soonest')->nullable();
            $table->string('impact_date_highest_ip')->nullable();

            $table->string('first_obs')->nullable();
            $table->string('last_obs')->nullable();
            $table->string('energy')->nullable();
            $table->string('ip')->nullable();
            $table->string('diameter')->nullable();
            $table->string('mass')->nullable();
            $table->string('ps_cum')->nullable();
            $table->string('ts_max')->nullable();

            $table->json('impacts')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('space_object_details');
    }
};
