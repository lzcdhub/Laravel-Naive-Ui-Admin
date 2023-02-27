<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_categories', function (Blueprint $table) {
            //主键ID
            $table->id();
            //新增分类的名称字段
            $table->string('name')->default('');
            //在此添加此方法，nestedset将会自动生成：_lft、_rgt、parent_id三个字段
            NestedSet::columns($table);
            //根据实际场景需要决定需不需要软删除
            $table->softDeletes();
            //添加创建时间、更新时间字段
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
        Schema::dropIfExists('news_categories');
    }
};
