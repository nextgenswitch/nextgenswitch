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
        Schema::create('ai_bots', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('name');
            $table->integer('voice_id');
            $table->integer('llm_provider_id');
            $table->string('api_key');
            $table->string('api_endpoint')->nullable();
            $table->string('model')->nullable();            
            $table->longText('resource');
            $table->integer('max_interactions');
            $table->integer('max_silince');
            $table->integer('waiting_tone')->nullable();
            $table->integer('inaudible_voice')->nullable();
            $table->integer('listening_tone')->nullable();
            // $table->integer('internal_directory');
            $table->string('email')->nullable();
            $table->tinyInteger('create_support_ticket')->default(0);
            $table->integer('function_id');
            $table->integer('destination_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_bots');
    }
};
