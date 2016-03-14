<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('gl_gallery', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name') -> unique();
			$table->text('description');
			$table->string('author');
			$table->string('thumb_img_url');
			$table->string('thumb_img_extension');
			$table->integer('views');
			$table->string('url')->unique();
			$table->string('type');
			$table->string('product');
			$table->string('product_id');
			$table->string('pro_cat_id');
			$table->string('product_views');
			$table->string('brand');
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
		Schema::drop('gl_gallery');
	}

}
