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
        Schema::table('sip_users', function (Blueprint $table) {
            $table->tinyInteger('user_type')->nullable();
            $table->integer('call_limit')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */

    public function down(): void
    {
        Schema::table('sip_users', function (Blueprint $table) {
            $table->dropColumn('user_type');
            $table->dropColumn('call_limit');
        });
    }
};
