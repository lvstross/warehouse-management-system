<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorsTableSeeder extends Seeder
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
        DB::table('vendors')->insert([
            'name' => str_random(10),
            'contact' => str_random(40),
            'phone' => $this->getPhone(),
            'fax' => $this->getPhone(),
            'ext' => '123',
            'email' => str_random(10) . '@gmail.com',
            'website' => 'https://' . str_random(10) . '.com/',
            'type' => $this->generateType(),
            'ship_address' => str_random(40),
            'purch_address' => str_random(40),
            'remit_address' => str_random(40),
            'comment_to' => 'This is a comment for this vendor.'
        ]); 
    }

    /**
     * Run the database seeds.
     *
     * @return string
     */
    public function run()
    {
        for($i=0;$i<16;$i++){
            $this->insert();
        }
        return '16 vendors added';
    }
}
