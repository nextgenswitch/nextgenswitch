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
        Schema::create('outbound_routes', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->integer('trunk_id');
            $table->string('name');
            $table->string('pattern');
            $table->string('prefix_remove')->nullable();
            $table->string('prefix_append')->nullable();
            $table->string('balance_share');
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbound_routes');
    }
};
