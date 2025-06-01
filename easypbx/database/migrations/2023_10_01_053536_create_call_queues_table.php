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
        Schema::create('call_queues', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->integer('extension_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->tinyInteger('strategy');
            $table->string('cid_name_prefix')->nullable();
            $table->integer('join_announcement')->nullable();
            $table->integer('agent_announcemnet')->nullable();
            $table->string('service_level')->nullable();
            $table->boolean('join_empty')->default(1);
            $table->boolean('leave_when_empty')->default(0);
            $table->string('timeout_priority')->nullable();
            $table->integer('queue_timeout')->default(30);
            $table->integer('member_timeout')->default(15);
            $table->integer('retry')->default(5);
            $table->integer('wrap_up_time')->default(0);
            $table->boolean('queue_callback')->default(0);
            $table->integer('music_on_hold')->nullable();
            $table->boolean('ring_busy_agent')->default(0);
            $table->boolean('record')->default(0);
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
        Schema::dropIfExists('call_queues');
    }
};
