<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();

            $table->string('username')->comment('用户名称');
            $table->string('password')->comment('用户密码');

            $table->string('real_name')->nullable()->comment('真实姓名');
            $table->tinyInteger('gender')->default(0)->comment('1男2女0未知');
            $table->string('phone')->nullable()->comment('手机号');

            $table->integer('group_id')->default(1)->comment('用户组');

            $table->softDeletes();
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
        Schema::dropIfExists('members');
    }
};
