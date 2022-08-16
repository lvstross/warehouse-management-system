<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('address', 255);
            $table->string('phone', 25);
            $table->string('email', 50);
            $table->text('desc')->nullable();
            $table->string('invoice_con', 50)->nullable();
            $table->string('shipper_con', 50)->nullable();
            $table->string('router_con', 50)->nullable();
            $table->string('po_con', 50)->nullable();
            $table->string('inv_prefix', 15);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
