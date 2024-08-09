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
        Schema::create('mail_profiles', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('name');
            $table->string('provider');
            $table->text('options');
            $table->tinyInteger('default')->default(0); 
            $table->tinyInteger('status')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_profiles');
    }
};
