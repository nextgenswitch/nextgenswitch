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
        Schema::table('queue_calls', function (Blueprint $table) {
         
            $table->dropColumn('queue_name');
            $table->integer('extension_id');
            $table->integer('call_queue_id');
            $table->integer('status');
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};