<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ivrs', function (Blueprint $table) {
            $table->tinyInteger('mode')->default(0);
        });

        Schema::table('ivr_actions', function (Blueprint $table) {
            $table->string('voice')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ivrs', function (Blueprint $table) {
            $table->dropColumn('mode');
        });

        Schema::table('ivr_actions', function (Blueprint $table) {
            $table->dropColumn('voice');
        });

    }
};
