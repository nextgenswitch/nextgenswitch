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
        Schema::create('queues', function (Blueprint $table) {
            $table->char('call_id', 36);
            $table->integer('organization_id');
            $table->integer('sip_user_id')->nullable();
            $table->char('bridge_call_id', 36)->nullable();
            $table->integer('call_queue_id')->nullable();
            $table->string('queue_name');
            $table->integer('duration')->nullable();
            $table->integer('waiting_duration')->nullable();
            $table->integer('recieved_by')->nullable();
            $table->string('record_file')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
