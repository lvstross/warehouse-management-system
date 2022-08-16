<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('part_num', 50);
            $table->string('po_num', 30);
            $table->string('customer', 50);
            $table->integer('qty');
            $table->enum('status',['Approved', 'Unapproved', 'Canceled'])->default('Unapproved');
            $table->string('cust_req', 500)->nullable();
            $table->json('routers')->nullable();
            $table->json('boxes')->nullable();
            $table->json('log')->nullable();
            $table->date('date');
            $table->enum('trip_count', ['Yes', 'No'])->default('No');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
