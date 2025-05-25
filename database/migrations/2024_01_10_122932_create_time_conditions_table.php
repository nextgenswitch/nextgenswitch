<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('time_conditions', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->string('name');
            $table->integer('time_group_id');
            $table->integer('matched_function_id');
            $table->integer('matched_destination_id');
            $table->integer('function_id');
            $table->integer('destination_id');
            $table->timestamps();

        });

        DB::table('funcs')->insert(
            array(
                'organization_id' => 0,
                'name' => 'TIME CONDITION',
                'func_type'=>0,
                'func'=>'time_condition'
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_conditions');
    }
};
