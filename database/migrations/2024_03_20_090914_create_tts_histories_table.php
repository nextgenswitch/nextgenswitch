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
        Schema::create('tts_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->integer('tts_profile_id');
            $table->tinyInteger('type');
            $table->text('input');
            $table->text('output')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tts_histories');
    }
};
