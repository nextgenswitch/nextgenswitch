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
        Schema::create('ip_black_lists', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('title')->nullable();
            $table->string('ip');
            $table->integer('subnet')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_black_lists');
    }
};
