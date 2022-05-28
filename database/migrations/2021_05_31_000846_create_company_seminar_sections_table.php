<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySeminarSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_seminar_sections', function (Blueprint $table) {
            $table->id();
            $table->integer('seminar_id')->nullable();
            $table->string('section_name')->nullable();
            $table->timestamp('unlock_date')->nullable();
            $table->tinyInteger('unlock_days')->nullable();
            $table->tinyInteger('sort_order')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_seminar_sections');
    }
}