<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('router_num');
            $table->string('part_num', 50);
            $table->integer('qty');
            $table->integer('stock_qty')->default(0);
            /* Status:
                NIP = 'Not In Production',
                IP = 'In Production',
                STFI = 'Staged For Inventory',
                II = 'In Inventory',
                A = 'Archive'
            */
            $table->enum('status', ['NIP', 'IP', 'STFI', 'II', 'A']);
            $table->string('dept_name', 50)->nullable();
            $table->json('move_log')->nullable();
            $table->date('date');
            $table->date('date_in_inv')->nullable();
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
        Schema::dropIfExists('routers');
    }
}
