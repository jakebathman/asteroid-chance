<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('space_objects', function (Blueprint $table) {
            $table->id();

            $table->string('designation');
            $table->string('sentry_id')->nullable();
            $table->string('fullname')->nullable();
            $table->string('ip')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('space_objects');
    }
};
