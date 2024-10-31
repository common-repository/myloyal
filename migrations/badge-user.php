<?php
namespace myLoyal\migrations;

use As247\WpEloquent\Support\Facades\Schema;

class Badge_User{

	public function run() {
		//hasTable
		if ( !Schema::hasTable( 'myloyal_badge_user' ) ) {
			Schema::create('myloyal_badge_user', function ($table) {
				$table->bigIncrements('id');
				$table->bigInteger('user_id')->unsigned();
				$table->bigInteger('badge_id')->unsigned();
				$table->bigInteger('term_id')->unsigned();
				$table->timestamps();

				$table->foreign('user_id')->references('ID')->on('users')->cascadeOnDelete();
				$table->foreign('badge_id')->references('id')->on('posts')->cascadeOnDelete();
			});
		}
	}
}