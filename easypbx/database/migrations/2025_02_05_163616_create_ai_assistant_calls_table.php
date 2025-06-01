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
        Schema::create('ai_assistant_calls', function (Blueprint $table) {
            $table->id();
            $table->char('call_id', 36);
            $table->integer('organization_id');
            $table->string('caller_id');
            $table->integer('ai_assistant_id');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_assistant_calls');
    }
};
