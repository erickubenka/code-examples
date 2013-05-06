<?php

use Illuminate\Database\Migrations\Migration;

class Adduser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		/**
		 * Users-Tabelle: id, name, email, confirmed, 
		 */
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('username', 255);
			$table->timestamps();
			$table->timestamp('deleted_at')->default(null)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}

}