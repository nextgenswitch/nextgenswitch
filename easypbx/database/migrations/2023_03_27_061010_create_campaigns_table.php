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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('name');
            $table->string('from');
            $table->integer('function_id');
            $table->integer('destination_id');
            $table->string('contact_groups', 100);
            $table->tinyInteger('status');
            $table->integer('max_retry');
            $table->integer('call_limit');
            $table->string('timezone', 100);
            $table->time('start_at');
            $table->time('end_at');
            $table->string('schedule_days', 100);
            $table->integer('total_sent')->nullable();
            $table->integer('total_successfull')->nullable();
            $table->integer('total_failed')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
