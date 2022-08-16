<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchasesTableSeeder extends Seeder
{
    /**
     * Generate a carrier.
     *
     * @return string
     */
    public function generateCarrier()
    {
        $carrTerms = ['FedEx', 'UPS', 'USPS', 'Pigeon'];
        $carriers = array_rand($carrTerms, 1);
        return $carrTerms[$carriers];
    }

    /**
     * Generate a carrier.
     *
     * @return string
     */
    public function generateTerms()
    {
        $arrTerms = ['COD', 'NET30', 'Collect', 'Prepaid', 'POA'];
        $terms = array_rand($arrTerms, 1);
        return $arrTerms[$terms];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0;$i<24;$i++){
            DB::table('purchases')->insert([
                'po_num' => str_random(10),
                'vendor' => rand(1, 15),
                'due_date' => date('Y-m-d'),
                'recv_date' => NULL,
                'terms' => $this->generateTerms(),
                'carrier' => $this->generateCarrier(),
                'vendor_confirm_order' => NULL,
                'items' => '[{"part_num":"001","qty_ordered":"100","qty_recv_good":null,"qty_canceled":null,"qty_rej":null,"unit_cost":"13.42","unit_of_measure":"yard","recv_date":null,"back_order_qty":null,"vendor_confirm_date":null,"description":"Roll of Material"}]',
                'po_total' => 1342.00,
                'entered_by' => 'Levi Gonzales',
                'enter_date' => '2018-03-12',
                'modified_by' => NULL,
                'modify_date' => NULL,
                'status' => 'Open',
                'comments' => 'This is a comment for this purchases.'
            ]);
        }
    }
}
