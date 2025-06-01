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
        Schema::create('call_parking_logs', function (Blueprint $table) {
            //$table->char('id', 36)->primary();
            $table->char('call_id', 36);
            $table->integer('call_parking_id');
            $table->integer('organization_id');
            $table->integer('parking_no');
            $table->string('from');
            $table->string('to');
           // $table->integer('waiting_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_parking_logs');
    }
};
