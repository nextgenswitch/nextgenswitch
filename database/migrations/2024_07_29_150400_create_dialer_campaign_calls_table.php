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
        Schema::create('dialer_campaign_calls', function (Blueprint $table) {
            $table->uuid('id');
            $table->integer('dialer_campaign_id');
            $table->char('call_id', 36)->nullable();
            $table->string('tel');
            $table->integer('retry')->default(1);
            $table->integer('status')->nullable();
            $table->integer('duration')->default(0);
            $table->text('form_data')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialer_campaign_calls');
    }
};
