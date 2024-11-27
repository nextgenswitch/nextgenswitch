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
        Schema::table('campaign_calls', function (Blueprint $table) {
            if(!Schema::hasColumn('campaign_calls', 'duration'))
                $table->integer('duration')->nullable()->default(0);
            $table->integer('completed')->nullable()->change();
            $table->renameColumn('completed', 'sms_history_id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};