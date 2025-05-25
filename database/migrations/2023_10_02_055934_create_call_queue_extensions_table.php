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
        Schema::create('call_queue_extensions', function (Blueprint $table) {
            $table->id();
            $table->integer('call_queue_id');
            $table->integer('extension_id');
            $table->integer('priority');
            $table->boolean('member_type')->default(0);
            $table->boolean('allow_diversion')->default(0);
            $table->timestamp('last_ans')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_queue_extensions');
    }
};
