<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySeminarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_seminars', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('department_id')->nullable();

            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('short_description')->nullable();
            $table->longText('description')->nullable();

            $table->text('benefits')->nullable();
            $table->text('requirements')->nullable();

            $table->timestamp('launch_at')->nullable();
            $table->integer('thumbnail_id')->nullable();
            $table->text('video_src')->nullable();
            $table->integer('total_video_time')->nullable();

            $table->tinyInteger('total_lectures')->default(0)->nullable();
            $table->tinyInteger('total_assignments')->default(0)->nullable();
            $table->tinyInteger('total_quiz')->default(0)->nullable();

            $table->timestamp('last_updated_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_seminars');
    }
}