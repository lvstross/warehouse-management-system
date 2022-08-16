<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    /**
     * Generate phone number
     *
     * @return string
     */
    public function getPhone()
    {
        $phone = '';
        for($i=0;$i<10;$i++){
            $phone .= rand(0,9);
        }
        return $phone;
    }

    /**
     * Generate a carrier.
     *
     * @return string
     */
    public function generateType()
    {
        $arrtypes = ['Callibration', 'Services', 'Repair', 'Inspection'];
        $types = array_rand($arrtypes, 1);
        return $arrtypes[$types];
    }

    /**
     * Insert method
     *
     * @return void
     */
    public function insert()
    {
        DB::table('customers')->insert([
            'name' => str_random(10),
            'shipto' => str_random(100),
            'billto' => str_random(100),
            'buyer' => str_random(10),
            'country' => str_random(10),
            'disclaimer' => str_random(40),
            'comments' => str_random(200)
        ]); 
    }

    /**
     * Run the database seeds.
     *
     * @return string
     */
    public function run()
    {
        for($i=0;$i<30;$i++){
            $this->insert();
        }
        return '30 customers added';
    }
}
