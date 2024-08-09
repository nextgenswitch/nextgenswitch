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
        Schema::create('calls', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->integer('organization_id');
            $table->char('parent_call_id', 36)->nullable();
            $table->string('caller_id');
            $table->integer('sip_user_id');
            $table->string('channel')->nullable();
            $table->string('destination');
            $table->tinyInteger('status');
            $table->timestamp('connect_time');
            $table->timestamp('ringing_time')->nullable();
            $table->timestamp('establish_time')->nullable();
            $table->timestamp('disconnect_time')->nullable();
            $table->integer('disconnecct_code')->nullable();
            $table->integer('duration');
            $table->string('record_file')->nullable();
            $table->string('user_agent')->nullable();
            $table->boolean('uas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
