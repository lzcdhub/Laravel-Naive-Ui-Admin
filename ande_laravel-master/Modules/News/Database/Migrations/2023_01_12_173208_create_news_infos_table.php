<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->string('title');
            $table->string('ftitle')->nullable()->comment('副标题');
            $table->string('smalltext')->nullable()->comment('内容简介');
            $table->string('writer')->nullable()->comment('作者');
            $table->string('befrom')->nullable()->comment('信息来源');
            $table->tinyInteger('diggtop')->default(0)->comment('文章顶置');
            $table->mediumText('newstext')->nullable()->comment('新闻正文');
            $table->integer('user_id')->default('0')->comment('录入人员');
            $table->tinyInteger('status_f')->default(1)->comment('状态 1上架 2下架');
            $table->timestamps();
            $table->softDeletes();

            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_infos');
    }
};
