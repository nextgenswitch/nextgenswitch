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
        Schema::table('outbound_routes', function (Blueprint $table) {
            
            $table->string('trunk_id')->change();
            $table->text('pattern')->change();
            $table->integer('pin_list_id')->nullable();
            $table->integer('function_id');
            $table->integer('destination_id');
            $table->dropColumn('prefix_append');
            $table->dropColumn('prefix_remove');
            $table->renameColumn('balance_share','priority');          
            $table->boolean('record')->nullable();
            $table->string('outbound_cid')->nullable();
           
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outbound_routes', function (Blueprint $table) {
           
          
        });
    }
};
