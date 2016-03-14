<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElasticTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gl_elastics', function(Blueprint $table)
		{
			$table->increments('ID');
			$table->string('index_name');
			$table->dateTime('date_created');
			$table->dateTime('date_modified');
			$table->integer('is_successfully_created');
			$table->integer('is_deleted');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('gl_elastics');
	}

}
