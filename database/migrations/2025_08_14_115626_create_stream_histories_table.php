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
        Schema::create('stream_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->char('call_id', 36);
            $table->string('stream_id');
            $table->string('caller_id');
            $table->integer('duration')->default(0);
            $table->string('record_file')->nullable();
            $table->string('transcript')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stream_histories');
    }
};
