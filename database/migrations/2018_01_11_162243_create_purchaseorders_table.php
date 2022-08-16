<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseorders', function (Blueprint $table) {
            $table->increments('id');
            $table->date('due_date');
            $table->date('will_ship');
            $table->date('ship_date')->nullable();
            $table->string('rating')->nullable();
            $table->enum('sooner', ['Yes', 'No']);
            $table->string('customer', 100);
            $table->string('po_num', 50);
            $table->string('part_num', 50);
            $table->integer('qty');
            $table->string('stock')->nullable();
            $table->decimal('sales', 8, 2)->nullable();
            $table->enum('need_routers', ['Yes', 'No']);
            $table->json('routers')->nullable();
            $table->string('cust_req', 1000)->nullable();
            $table->enum('status', ['Open', 'Closed']);
            $table->string('invoice',50)->nullable();
            $table->integer('key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchaseorders');
    }
}
