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
        Schema::create('call_legs', function (Blueprint $table) {
            $table->id();
            $table->integer('call_id');
            $table->integer('sip_user_id');
            $table->string('channel');
            $table->string('destination');
            $table->tinyInteger('call_status');
            $table->timestamp('connect_time');
            $table->timestamp('ringing_time');
            $table->timestamp('establish_time');
            $table->timestamp('disconnect_time');
            $table->integer('duration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_legs');
    }
};
