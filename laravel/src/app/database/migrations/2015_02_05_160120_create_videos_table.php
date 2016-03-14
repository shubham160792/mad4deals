<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gl_videos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('gallery_id');
			$table->string('url');
			$table->string('caption');
			$table->text('description');
			$table->string('author');
			$table->integer('views');
			$table->integer('active');
			$table->integer('display_order');
			//$table->text('position_tags');
			$table->text('meta_tags');
			$table->text('attributes');
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
		Schema::drop('gl_videos');
	}

}
