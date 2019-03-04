<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->string('id', 32)->primary()->comment('主键');
            $table->string('username')->comment('用户名');
            $table->string('account')->comment('账号')->unique();
            $table->string('password')->comment('密码');
            $table->string('last_login_ip')->comment('登录ip')->default('0.0.0.0');
            $table->dateTime('last_login_time')->comment('登录时间')->default('1970-01-01 00:00:00');
            $table->tinyInteger('administer')->comment('0非管理员，1管理员')->default('0');
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
        Schema::dropIfExists('admin_users');
    }
}
