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
        Schema::table('outbound_routes', function (Blueprint $table) {
            $table->boolean('type')->default(false);
            $table->integer('function_id')->nullable()->change();
            $table->integer('destination_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outbound_routes', function (Blueprint $table) {
            //
        });
    }
};
