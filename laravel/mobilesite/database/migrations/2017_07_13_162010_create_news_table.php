<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id')->unique()->index();
            $table->string('news_author')->comment('发布者(显示内容)');
            $table->longtext('news_content')->comment('发布内容');
            $table->string('news_admin')->comment('发布者(用户名)');
            $table->string('news_title')->comment('新闻标题');
            $table->boolean('status')->default('0')->comment('状态(0:删除,1:发布)');
            $table->integer('create_time')->unsigned()->nullable()->comment('创建时间');
            $table->integer('update_time')->unsigned()->nullable()->comment('更新时间');
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
        Schema::dropIfExists('news');
    }
}
