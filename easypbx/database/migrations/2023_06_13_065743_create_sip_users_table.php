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
        Schema::create('sip_users', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('username');
            $table->string('password');
            $table->string('host')->nullable();
            $table->integer('port')->nullable();
            $table->tinyInteger('transport')->nullable();
            $table->boolean('peer')->default(1);
            $table->boolean('record')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sip_users');
    }
};
