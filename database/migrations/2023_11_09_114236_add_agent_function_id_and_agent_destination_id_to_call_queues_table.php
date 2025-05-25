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
        Schema::table('call_queues', function (Blueprint $table) {
            $table->integer('agent_function_id')->nullable();
            $table->integer('agent_destination_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('call_queues', function (Blueprint $table) {
            $table->dropColumn(['agent_function_id', 'agent_destination_id']);
        });
    }
};
