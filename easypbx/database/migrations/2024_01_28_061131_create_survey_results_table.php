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
        Schema::create('survey_results', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id');
            $table->char('call_id', 36);
            $table->integer('survey_id');
            $table->integer('caller_id');
            $table->integer('pressed_key')->nullable();
            $table->string('record_file')->nullable();
            $table->timestamps();
        });

        DB::table('funcs')->insert(
            array(
                'organization_id' => 0,
                'name' => 'CALL SURVEY',
                'func_type'=>0,
                'func'=>'call_survey'
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_results');
    }
};
