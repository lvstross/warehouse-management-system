<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_num');
            $table->string('recv_num')->nullable();
            $table->integer('vendor');
            $table->string('due_date')->nullable();
            $table->string('recv_date')->nullable();
            $table->string('terms')->nullable();
            $table->string('carrier')->nullable();
            $table->date('vendor_confirm_order')->nullable();
            /*
                part_num: string,
                qty_ordered: number,
                qty_recv_good: number,
                qty_canceled: number,
                qty_rej: number,
                unit_cost: float,
                unit_of_measure: string,
                recv_date: date,
                back_order_qty: number,
                vendor_confirm_date: date,
                description: string
            */
            $table->json('items')->nullable(); // json items
            $table->decimal('po_total', 7, 2)->nullable();
            $table->string('entered_by')->nullable();
            $table->string('enter_date')->nullable();
            $table->string('modified_by')->nullable();
            $table->string('modify_date')->nullable();
            $table->enum('status', ['Open', 'Closed']);
            $table->string('comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
