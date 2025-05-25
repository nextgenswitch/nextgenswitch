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
        Schema::create('queue_calls', function (Blueprint $table) {
            $table->char('call_id', 36);
            $table->char('parent_call_id', 36)->nullable();
            $table->integer('organization_id');
            $table->string('queue_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_calls');
    }
};
