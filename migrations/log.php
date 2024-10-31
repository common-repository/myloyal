<?php
namespace myLoyal\migrations;

use As247\WpEloquent\Support\Facades\Schema;

class Log{

	public function run() {
		//hasTable
		if ( !Schema::hasTable( 'myloyal_log' ) ) {
			Schema::create('myloyal_log', function ($table) {
				$table->bigIncrements('id');
				$table->bigInteger('user_id')->unsigned();
				$table->string('case_str')->nullable();
				$table->string('description');
				$table->timestamps();
				$table->foreign('user_id')->references('ID')->on('users')->cascadeOnDelete();
			});
		}
	}
}