<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySeminarCompletesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_seminar_completes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('completed_seminar_id')->nullable();
            $table->integer('seminar_id')->nullable();
            $table->integer('content_id')->nullable();
            $table->timestamp('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_seminar_completes');
    }
}