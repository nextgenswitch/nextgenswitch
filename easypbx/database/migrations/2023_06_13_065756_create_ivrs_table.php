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
        Schema::create('ivrs', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('name');
            $table->integer('welcome_voice')->nullable();
            $table->integer('instruction_voice');
            $table->integer('invalid_voice');
            $table->integer('timeout_voice');
            $table->integer('invalid_retry_voice')->nullable();
            $table->integer('timeout_retry_voice')->nullable();
            $table->integer('timeout');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ivrs');
    }
};
