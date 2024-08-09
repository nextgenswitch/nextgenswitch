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
        Schema::create('voice_mails', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->integer('extension_id');
            $table->string('voice_path')->nullable();
            $table->text('transcript')->nullable();
            $table->boolean('read')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voice_mails');
    }
};
