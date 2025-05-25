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
        Schema::create('inbound_routes', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('did_pattern');
            $table->string('cid_pattern')->nullable();
            $table->tinyInteger('function_id');
            $table->integer('destination_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbound_routes');
    }
};
