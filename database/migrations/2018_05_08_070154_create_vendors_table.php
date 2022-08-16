<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('contact', 50)->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('ext', 10)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('type', 50)->nullable();
            $table->string('ship_address', 255)->nullable();
            $table->string('purch_address', 255)->nullable();
            $table->string('remit_address', 255)->nullable();
            $table->string('comment_to', 1000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
