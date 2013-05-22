<?php

use Illuminate\Database\Migrations\Migration;

class Adduser extends Migration
{

		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
				// Set up the users table with username and password
				Schema::create('users', function($table)
								{
										$table->increments('id');
										$table->string('username', 255)->unique();
										$table->string('password', 64);
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
				Schema::dropIfExists('users');
		}

}