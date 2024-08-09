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
        Schema::create('call_histories', function (Blueprint $table) {
                $table->char('call_id', 36);
                $table->char('bridge_call_id', 36);
                $table->integer('organization_id');
                $table->integer('duration')->nullable();                
                $table->string('record_file')->nullable();
                $table->integer('status')->default(0);
                $table->timestamps();            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_histories');
    }
};
