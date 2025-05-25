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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->integer('ticket_id');
            $table->integer('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('phone');
            $table->string('subject');
            $table->text('description');
            $table->tinyInteger('status')->default(1); // 1 - open 2-ongoing 3-closed
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
