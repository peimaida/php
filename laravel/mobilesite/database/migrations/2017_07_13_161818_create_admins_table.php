<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id')->unique()->index();
            $table->string('username')->unique()->comment('用户名');
            $table->string('password')->comment('密码');
            $table->integer('tel')->unique()->unsigned()->nullable()->comment('电话号码');
            $table->enum('status', ['1', '0', '-1'])->nullable()->comment('状态(-1:删除,0:禁用,1:正常)');
            $table->string('last_login_ip')->nullable()->comment('上次登录时的IP地址');
            $table->integer('last_login_time')->unsigned()->nullable()->comment('上次登录时间');
            $table->integer('create_time')->unsigned()->nullable()->comment('创建时间');
            $table->integer('update_time')->unsigned()->nullable()->comment('更新时间');
            $table->boolean('permission')->default('0')->comment('管理员权限(0:普通,1:超级)');
            $table->rememberToken();
            $table->timestamps();
            $table->comment = '后台管理员表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
