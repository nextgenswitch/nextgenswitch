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
        Schema::table('tts_profiles', function (Blueprint $table) {
            $table->renameColumn('others', 'config')->nullable(false)->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tts_profiles', function (Blueprint $table) {
            $table->renameColumn('config', 'others')->nullable(true)->change();
        });
    }
};