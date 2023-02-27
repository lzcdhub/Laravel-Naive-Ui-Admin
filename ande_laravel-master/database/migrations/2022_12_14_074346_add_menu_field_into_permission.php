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
        Schema::table('permissions',function (Blueprint $table) {
            $table->string('menu_path')->nullable();
            $table->string('menu_component')->nullable();
            $table->string('menu_redirect')->nullable();
            $table->string('menu_icon')->nullable();
            $table->string('menu_title')->nullable();
            $table->bigInteger('pid')->default(0);
            $table->tinyInteger('menu_sort')->default(0);
            $table->tinyInteger('level')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('menu_path');
            $table->dropColumn('menu_sort');
            $table->dropColumn('menu_component');
            $table->dropColumn('menu_redirect');
            $table->dropColumn('menu_icon');
            $table->dropColumn('menu_title');
            $table->dropColumn('pid');
            $table->dropColumn('level');
        });
    }
};
