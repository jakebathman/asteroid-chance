<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('space_objects', function (Blueprint $table) {
            $table->string('ts_max')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('space_objects', function (Blueprint $table) {
            $table->dropColumn('ts_max');
        });
    }
};
