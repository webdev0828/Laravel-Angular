<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('country');
			$table->string('city');
			$table->string('website');
			$table->string('email')->unique();
			$table->string('phone', 15);
			$table->string('genre');
			$table->enum('user_type', array('admin','admin_user','stm_user','artist'));
			$table->enum('subscription', array('FREE','PREMIUM','UNLIMITED','PROPREMIUM','PROUNLIMITED'));
			$table->enum('status', array('1','0'));
			$table->string('password', 255);
			$table->string('avatar');
			$table->string('cover');
			$table->string('souncloud_url');
			$table->string('facebook_url');
			$table->string('twitter_url');
			$table->string('youtube_url');
			$table->string('instagram_url');
			$table->rememberToken();
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
		Schema::drop('users');
	}

}
