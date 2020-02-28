<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * |==============================================================|
 * | Please DO NOT modify this information :                      |
 * |--------------------------------------------------------------|
 * | Author          : Susantokun
 * | Email           : admin@susantokun.com
 * | Instagram       : @susantokun
 * | Website         : http://www.susantokun.com
 * | Youtube         : http://youtube.com/susantokun
 * | File Created    : Friday, 28th February 2020 2:50:20 pm
 * | Last Modified   : Friday, 28th February 2020 2:50:20 pm
 * |==============================================================|
 */

class CreateMenusTable extends Migration
{
    public function up()
    {
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id');
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('url')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_menus');
    }
}
