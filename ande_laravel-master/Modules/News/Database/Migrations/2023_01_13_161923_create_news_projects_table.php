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
        Schema::create('news_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('user_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->string('gather_time')->nullable()->comment('募集时间');
            $table->tinyInteger('time_limit')->default(0)->comment('期限');
            $table->string('offering_size')->nullable()->comment('募集规模');
            $table->string('interest_start_date')->nullable()->comment('起息时间');
            $table->tinyInteger('interest_paymet')->default(0)->comment('付息方式');
            $table->string('interest_pay_date')->nullable()->comment('付息时间');
            $table->string('project_label')->nullable()->comment('项目标签');
            $table->string('publish_schedule')->nullable()->comment('发行进度');
            $table->string('project_area')->nullable()->comment('地区');
            $table->string('project_rating')->nullable()->comment('项目评级');
            $table->string('expected_yield')->nullable()->comment('预期收益率');
            $table->string('expected_yield_min')->nullable()->comment('最低预期收益率');
            $table->string('specifications')->nullable()->comment('业绩比较基准');
            $table->tinyInteger('money_invest')->default(0)->comment('资金投向');
            $table->string('use_funds')->nullable()->comment('资金用途');
            $table->text('introduce')->nullable()->comment('项目介绍');
            $table->text('publisher_introduce')->nullable()->comment('城投公司Ⅰ介绍');
            $table->text('guarantee_introduce')->nullable()->comment('城投公司Ⅱ介绍');
            $table->text('area_introduce')->nullable()->comment('产品亮点');
            $table->text('product_lightspot')->nullable()->comment('产品亮点');
            $table->tinyInteger('status_f')->default(1)->comment('状态 1上架 2下架');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_projects');
    }
};
