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
        Schema::create('campaign_calls', function (Blueprint $table) {
            $table->id();
            $table->integer('campaign_id');
            $table->char('call_id', 36)->nullable();
            $table->string('tel');
            $table->integer('retry');
            $table->integer('status')->nullable();
            $table->integer('duration')->default(0);
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_calls');
    }
};
