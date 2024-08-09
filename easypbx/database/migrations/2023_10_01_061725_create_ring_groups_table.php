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
        Schema::create('ring_groups', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->integer('extension_id');
            $table->string('description');
            $table->tinyInteger('ring_strategy');
            $table->integer('ring_time');
            $table->boolean('answer_channel')->default(0);
            $table->boolean('skip_busy_extension')->default(0);
            $table->boolean('allow_diversions')->default(0);
            $table->boolean('ringback_tone')->default(0);
            $table->text('extensions');
            $table->integer('function_id');
            $table->integer('destination_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ring_groups');
    }
};
