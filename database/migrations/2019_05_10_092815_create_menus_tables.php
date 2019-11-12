<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('system_name');
            $table->string('locale', 10)->nullable();
            $table->boolean('publish')->default(true);
            $table->unsignedInteger('cache')->default(0)->comment('Cache time in sec.');
            $table->boolean('safe')->default(false);
            $table->json('data')->nullable();
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('path')->nullable();
            $table->unsignedInteger('url_alias_id')->nullable();
            $table->string('target', 10)->nullable();
            $table->tinyInteger('path_type')->default(\App\Models\Menu\MenuItem::PATH_TYPE_PATH);

            $table->integer('weight')->default(0);
            $table->unsignedBigInteger('menu_id');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('CASCADE');

            $table->json('data')->nullable();

            $table->unsignedInteger('_lft')->default(0);
            $table->unsignedInteger('_rgt')->default(0);
            $table->unsignedInteger('parent_id')->nullable();
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
        Schema::dropIfExists('menu_items');

        Schema::dropIfExists('menus');
    }
}
