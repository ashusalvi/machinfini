<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_employees', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->comment('foraign key of company id');
            $table->integer('department_id')->comment('foraign key of department id');
            $table->integer('user_id')->comment('user_table_id');
            $table->string('employee_id')->nullable()->comment('company employee id');
            $table->string('status')->default(1)->comment('0 => inactive, 1 => active');
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
        Schema::dropIfExists('company_employees');
    }
}