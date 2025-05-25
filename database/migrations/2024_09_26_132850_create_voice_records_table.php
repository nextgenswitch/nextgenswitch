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
        Schema::create('voice_records', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('name');
            $table->integer('voice_id');
            $table->boolean('is_transcript')->default(false);
            // $table->text('text')->nullable();
            $table->boolean('play_beep')->default(false);
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_create_ticket')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voice_records');
    }
};
