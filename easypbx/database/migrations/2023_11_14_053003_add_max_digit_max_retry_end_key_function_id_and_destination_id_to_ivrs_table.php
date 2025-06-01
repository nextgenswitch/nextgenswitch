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
        Schema::table('ivrs', function (Blueprint $table) {
            $table->integer('max_digit')->nullable();
            $table->integer('max_retry')->nullable();
            $table->char('end_key', 1)->nullable();
            $table->integer('function_id');
            $table->integer('destination_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ivrs', function (Blueprint $table) {
            $table->dropColumn(['max_digit', 'max_retry', 'end_key']);
        });
    }
};
