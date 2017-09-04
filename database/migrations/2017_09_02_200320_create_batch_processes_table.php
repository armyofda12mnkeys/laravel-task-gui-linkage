<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_processes', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('time_to_use');
			$table->string('schedule_timing_cron')->nullable();
			$table->string('schedule_timing_keyword')->nullable();
			$table->string('task')->nullable();
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
        Schema::dropIfExists('batch_processes');
    }
}
