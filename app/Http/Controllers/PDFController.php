<?php

namespace App\Http\Controllers;

use App\Company;
use App\Invoice;
use App\Inventory;
use App\Purchaseorder;
use App\Vendor;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use stdClass;
use Vsmoraes\Pdf\Pdf;

class PDFController extends Controller
{
    /**
    * @var PDF Object
    */
    private $pdf;

    /**
    * Set pdf variable
    *
    * @param $pdf  PDF Object
    * @return void
    */
    public function __construct(PDF $pdf)
    {
        $this->pdf = $pdf;
    }

    /**
    * PDF INVOICES
    *
    * @param $id of the invoice
    * @return pdf
    */
    public function invoice($id)
    {
        // Get Invoice Data by id
        $invoice = Invoice::find($id);
        // Get Company Data
        if(Company::find(1)){
            $company = Company::find(1);
        }else{
            return redirect('/invoices')->with('company-message', 'Invoice can not be printed with out company information. Please go to your settings to add your companies information.');
        }

        $newInvoice = [];
        foreach($invoice['original'] as $key => $value){
            if($key === 'customer' || $key === 'line_items'){
                $d_decoded = $this->doubleDecode($value);
                foreach($d_decoded as $k => $v){
                    if($k === 'shipto' || $k === 'billto'){
                        $addressArr = explode("\n", $v);
                        array_push($newInvoice, $addressArr);
                    }
                }
                array_push($newInvoice, $d_decoded);
            } else if ($key === 'date'){
                $newDate = $this->invertDate($value);
                array_push($newInvoice, $newDate);
            } else {
                array_push($newInvoice, $value);
            }
        }

        for($i = 0; $i < count($newInvoice[7]); $i++){
            $set = [
                'item' => $newInvoice[7][$i]['item'],
                'product' => $newInvoice[7][$i]['product'],
                'qty' => $newInvoice[7][$i]['qty'],
                'unit' => $newInvoice[7][$i]['unit'],
                'extended' => $newInvoice[7][$i]['extended']
            ];
            $newInvoice[7][$i] = $set;
        }

        $sub_total = floatval($invoice['original']['total']) - ( floatval($invoice['original']['ship_fee'] + floatval($invoice['original']['misc_char']) ) );
        $sub_total = $this->addDecimal($sub_total);
        array_push($newInvoice, $sub_total);

        foreach($company['original'] as $key => $value){
            if($key === 'address'){
                $addArr = explode("\n", $value);
                array_push($newInvoice, $addArr);
            }else if($key === 'inv_prefix'){
                //
            }else{
                array_push($newInvoice, $value);
            }
        }
        // Add Decimal places to the extended values
        for($i = 0; $i < count($newInvoice[7]); $i++){
            $newInvoice[7][$i]['unit'] = $this->addDecimal($newInvoice[7][$i]['unit']);
            $newInvoice[7][$i]['extended'] = $this->addDecimal($newInvoice[7][$i]['extended']);
        }

        $filename = $newInvoice[1] . '-invoice.pdf';
        $html = view('pdfs.invoice')->with('invoice', $newInvoice);
        return $this->pdf
            ->load($html)
            ->filename($filename)
            ->download();
    }


    /**
    * PDF SHIPPER
    *
    * @param $id of the invoice
    * @return pdf
    */
    public function shipper($id)
    {
        // Get Invoice Data by id
        $invoice = Invoice::find($id);
        // Get Company Data
        if(Company::find(1)){
            $company = Company::find(1);
        }else{
            return redirect('/invoices')->with('company-message', 'Shipper can not be printed with out company information. Please go to your settings to add your companies information.');
        }

        $newInvoice = [];

        foreach($invoice['original'] as $key => $value){
            if($key === 'customer' || $key === 'line_items'){
                $d_decoded = $this->doubleDecode($value);
                foreach($d_decoded as $k => $v){
                    if($k === 'shipto' || $k === 'billto'){
                        $addressArr = explode("\n", $v);
                        array_push($newInvoice, $addressArr);
                    }
                }
                array_push($newInvoice, $d_decoded);
            } else if ($key === 'date'){
                $newDate = $this->invertDate($value);
                array_push($newInvoice, $newDate);
            // All other values need no further processing, they are pushed to the 'newInvoice' array.
            } else {
                array_push($newInvoice, $value);
            }
        }
        // Organize the line item data properly if data is moved around due to export and import
        for($i = 0; $i < count($newInvoice[7]); $i++){
            $set = [
                'item' => $newInvoice[7][$i]['item'],
                'product' => $newInvoice[7][$i]['product'],
                'qty' => $newInvoice[7][$i]['qty'],
                'unit' => $newInvoice[7][$i]['unit'],
                'extended' => $newInvoice[7][$i]['extended']
            ];
            $newInvoice[7][$i] = $set;
        }
        // lastly, we need to add the company information to place onto the pdf document.
        foreach($company['original'] as $key => $value){
            if($key === 'address'){
                $addArr = explode("\n", $value);
                array_push($newInvoice, $addArr);
            }else if($key === 'inv_prefix'){
                //
            }else{
                array_push($newInvoice, $value);
            }
        }
        // Finally, the view and the newInvoice data is stored in a variable to be passed to the PDF objects instance for pdf generation.
        $filename = $newInvoice[1] . '-shipper.pdf';
        $html = view('pdfs.shipper')->with('invoice', $newInvoice);
        return $this->pdf
            ->load($html)
            ->filename($filename)
            ->download();
    }

    /**
    *
    * @param $start  Start date of the report
    * @param $end  End date of the report
    * @return pdf
    */
    public function invoiceReport($start, $end, $reportname = "invoice-report.pdf")
    {
        $report = DB::table('invoices')
                    ->select('inv_num', 'date', 'customer', 'total')
                    ->whereBetween('date', [$start, $end])
                    ->get()
                    ->toArray();
        $newReport = [];
        for($i = 0; $i < count($report); $i++){
            $inner = [];
            foreach($report[$i] as $k => $v){
                if($k === 'customer'){
                    $newVal = $this->doubleDecode($v);
                    $array = $this->doubleDecode($newVal);
                    array_push($inner, $array['name']);
                } else if ($k === 'date'){
                    $dateArr = explode('-', $v);
                    $newDate = $dateArr[1].'-'.$dateArr[2].'-'.$dateArr[0];
                    array_push($inner, $newDate);
                }else{
                    array_push($inner, $v);
                }
            }
            array_push($newReport, $inner);
        }

        $total = 0;
        for($i = 0; $i < count($newReport); $i++){
            $total += $newReport[$i][3];
        }
        array_push($newReport, floatval($total));

        $html = view('pdfs.invoicereport')->with('report', $newReport);
        return $this->pdf
            ->load($html)
            ->filename($reportname)
            ->download();
    }

    /* C of C printout
     *
     * @return pdf
     */
    public function certPrintOut(Request $request)
    {
        // Set the request data
        $data = $request->input();
        // Set the id of the for the invoice query
        $newPlArr = explode('-', $data['pl_num']);
        $plId = intval($newPlArr[0]);
        // Extract id from data product string
        $newIdArr = explode('-',$data['product']);
        $productId = intval($newIdArr[0]);
        // Invert Date
        $data['date'] = $this->invertDate($data['date']);
        // Slice the lot items, chunk them into threes and unshift them to data
        $length = count($data) - 5;
        $lot_items = array_slice($data, 5, $length);
        array_splice($data, 5, $length);
        $lot_items = array_chunk($lot_items, 3);
        array_unshift($data, $lot_items);
        // Split each seller cert item into an array
        $data['seller_certifies'] = explode("\n", $data['seller_certifies']);
        // Get Product Data
        $product = DB::table('products')
            ->select('name', 'material', 'rev', 'rev_date')
            ->where('id', $productId)
            ->get()
            ->toArray();
        $product[0]->rev_date = $this->invertDate($product[0]->rev_date);
        array_unshift($data, $product);
        // Get invoice Data: Decode customer data and then explode shipto and billto address
        $invoice = DB::table('invoices')
            ->select('inv_num', 'date', 'po_num', 'customer')
            ->where('id', $plId)
            ->get()
            ->toArray();
        $invoice[0]->customer = $this->doubleDecode($invoice[0]->customer);
        $invoice[0]->customer['shipto'] = explode("\n", $invoice[0]->customer['shipto']);
        $invoice[0]->customer['billto'] = explode("\n", $invoice[0]->customer['billto']);
        array_unshift($data, $invoice[0]);
        // Get Company Data and create address array to loop through
        $company = DB::table('companies')
            ->select('name', 'address')
            ->where('id', 1)
            ->get()
            ->toArray();
        $company[0]->address = explode("\n", $company[0]->address);
        array_unshift($data, $company);
        // Set file name and return pdf download
        $filename = 'CofC-' . $data[1]->inv_num;
        // dd($data);
        $html = view('pdfs.cert')->with('cert', $data);
        return $this->pdf
            ->load($html)
            ->filename($filename)
            ->download();
    }

    /**
    * shipticket print out
    *
    * @param $id
    * @return pdf
    */
    public function shipticket($id)
    {
        // Get Inventory data by id
        $inventory = Inventory::find($id);
        $company = Company::find(1);
        // Check for inventory
        if(!$company){
            return redirect('/production?app=viewInventory')->with('inventory-message', 'Ship tickets require company information. Please add company information in the settings area.');
        }
        // Add product information
        $product = DB::table('products')->select('*')->where('name', $inventory->part_num)->get();
        // dd($product);
        if(count($product) == 0){
            return redirect('/production?app=viewInventory')->with('inventory-message', 'Sorry! Could not find product information for ' . $inventory->part_num . '. Make sure that product is a real product inside your dataentry system.');   
        }
        $inventory->prod_name = $product[0]->name;
        $inventory->prod_description = $product[0]->description;
        $inventory->prod_rev = $product[0]->rev;
        $inventory->prod_rev_date = $this->invertDate($product[0]->rev_date);

        $inventory->routers = json_decode($inventory->routers);
        $inventory->boxes = json_decode($inventory->boxes);
        $inventory->date = date('F j, Y', strtotime($inventory->date));
        if($inventory->cust_req != null){
            $inventory->cust_req = explode("\n", $inventory->cust_req);
        }
        $inventory->company = $company->name;
        // dd($inventory['attributes']);
        $filename = 'shipticket-' . $inventory->date;
        $html = view('pdfs.shipticket')->with('shipticket', $inventory['attributes']);
        return $this->pdf
            ->load($html)
            ->filename($filename)
            ->download();
    }

    /**
     * COOR Print Out
     * @return pdf
     */
    public function coor()
    {
        $pos = DB::table('purchaseorders')
                ->select('*')
                ->where('status', 'Open')
                ->orderBy('key', 'asc')
                ->get();
        for($i=0;$i<count($pos);$i++){
            $pos[$i]->key = $i + 1;
            $pos[$i]->due_date = $this->invertDate($pos[$i]->due_date);
            $pos[$i]->will_ship = $this->invertDate($pos[$i]->will_ship);
        }
        $html = view('pdfs.coor')->with('pos', $pos);
        return $this->pdf
            ->load($html, 'A4', 'landscape')
            ->filename('COOR')
            ->download();
    }

    /**
     * COOR Production Print Out
     * @return pdf
     */
    public function coorProduction()
    {
        $pos = DB::table('purchaseorders')
                ->select('*')
                ->where('status', 'Open')
                ->orderBy('key', 'asc')
                ->get();
        for($i=0;$i<count($pos);$i++){
            $pos[$i]->key = $i + 1;
            $pos[$i]->will_ship = $this->invertDate($pos[$i]->will_ship);
            $pos[$i]->routers = $this->doubleDecode($pos[$i]->routers);
            for($r = 0; $r < count($pos[$i]->routers); $r++){
                $set = new stdClass;
                $set->router = $pos[$i]->routers[$r]['router'];
                $set->qty = $pos[$i]->routers[$r]['qty'];
                $pos[$i]->routers[$r] = $set;
            }
        }
        $html = view('pdfs.coor-production')->with('pos', $pos);
        return $this->pdf
            ->load($html, 'A4', 'landscape')
            ->filename('COOR-production')
            ->download();
    }

    /**
     * COOR Task Sheet Print Out
     * @return pdf
     */
    public function coorTask()
    {
        $pos = DB::table('purchaseorders')
                ->select('*')
                ->where('status', 'Open')
                ->orderBy('key', 'asc')
                ->get();
        for($i=0;$i<count($pos);$i++){
            $pos[$i]->key = $i + 1;
            $pos[$i]->will_ship = $this->invertDate($pos[$i]->will_ship);
        }
        $html = view('pdfs.coor-task-sheet')->with('pos', $pos);
        return $this->pdf
            ->load($html, 'A4', 'landscape')
            ->filename('COOR-TaskSheet')
            ->download();
    }

    /**
     * COOR On Time Report
     * @return pdf
     */
    public function onTimeReport($start, $end, $title = null)
    {
        $pos = DB::table('purchaseorders')
                ->select('*')
                ->where('status', 'Closed')
                ->whereBetween('will_ship', [$start, $end])
                ->orderBy('will_ship', 'asc')
                ->get();
        $late = 0;
        for($i=0;$i<count($pos);$i++){
            $will_ship = new DateTime($pos[$i]->will_ship);
            $ship_date = new DateTime($pos[$i]->ship_date);
            if($ship_date > $will_ship){
                $pos[$i]->on_time = 'No';
                $late++;
            }else{
                $pos[$i]->on_time = 'Yes';
            }
            $pos[$i]->due_date = $this->invertDate($pos[$i]->due_date);
            $pos[$i]->will_ship = $this->invertDate($pos[$i]->will_ship);
            $pos[$i]->ship_date = $this->invertDate($pos[$i]->ship_date);
        }
        $data = new stdClass;
        $data->pos = $pos;
        $data->late = $late;
        $data->count = count($pos);
        $data->percentage = number_format(abs($late / count($pos) * 100 - 100), 2);
        if($title){
            $data->title = $title;
        }else{
            $data->title = '';
        }
        $html = view('pdfs.on-time-report')->with('pos', $data);
        return $this->pdf
            ->load($html, 'A4', 'landscape')
            ->filename('COOR-on-time-report')
            ->download();
    }

    /**
     * COOR Sales Report
     * @return pdf
     */
    public function salesReport($start, $end, $show, $status)
    {
        if($status == 'Choose An Option' || $status == 'Open'){
            $pos = DB::table('purchaseorders')
                    ->select('*')
                    ->where('status', 'Open')
                    ->whereBetween('will_ship', [$start, $end])
                    ->orderBy('will_ship', 'asc')
                    ->get();
        }else{ // 'Closed'
            $pos = DB::table('purchaseorders')
                    ->select('*')
                    ->where('status', 'Closed')
                    ->whereBetween('ship_date', [$start, $end])
                    ->orderBy('ship_date', 'asc')
                    ->get();
        }

        $total_qty = 0;
        $total_sales = 0.00;
        for($i=0;$i<count($pos);$i++){
            $pos[$i]->due_date = $this->invertDate($pos[$i]->due_date);
            $pos[$i]->will_ship = $this->invertDate($pos[$i]->will_ship);
            if($pos[$i]->ship_date){
                $pos[$i]->ship_date = $this->invertDate($pos[$i]->ship_date);
            }
            $total_qty =  $total_qty + $pos[$i]->qty;
            $total_sales = $total_sales + $pos[$i]->sales;
        }
        $data = new stdClass;
        $data->pos = $pos;
        $data->total_qty = $total_qty;
        $data->start = $this->invertDate($start);
        $data->end = $this->invertDate($end);
        $data->total_sales = number_format($total_sales, 2, '.', '');
        $data->status = $status;
        if($show == 'Choose An Option' || $show == 'Yes'){
            $data->show = true;
        }else{
            $data->show = false;
        }

        $html = view('pdfs.coor-sales-report')->with('pos', $data);
        return $this->pdf
            ->load($html, 'A4', 'landscape')
            ->filename('COOR-projection-report')
            ->download();
    }

    /**
     * COOR On Time Report
     * @return pdf
     */
    public function onTimeReportPurchases($start, $end, $vendor)
    {
        $pos = DB::table('purchases')
                ->select('*')
                ->where([
                    ['status', 'Closed'],
                    ['vendor', $vendor]
                ])
                ->whereBetween('due_date', [$start, $end])
                ->orderBy('due_date', 'asc')
                ->get();
        for($i=0;$i<count($pos);$i++){
            $vend = Vendor::find($pos[$i]->vendor);
            if($vendor){
                $pos[$i]->vendor = $vend->name;
            }else{
                $pos[$i]->vendor = 'Vendor Not Found';
            }
        }
        $late = 0;
        for($i=0;$i<count($pos);$i++){
            $recv_date = new DateTime($pos[$i]->recv_date);
            $due_date = new DateTime($pos[$i]->due_date);
            if($recv_date > $due_date){
                $pos[$i]->on_time = 'No';
                $late++;
            }else{
                $pos[$i]->on_time = 'Yes';
            }
            $pos[$i]->due_date = $this->invertDate($pos[$i]->due_date);
            $pos[$i]->recv_date = $this->invertDate($pos[$i]->recv_date);
        }
        $data = new stdClass;
        $v = DB::table('vendors')->select('name')->where('id', $vendor)->get();
        $data->vendor = $v[0]->name;
        $data->start = $this->invertDate($start);
        $data->end = $this->invertDate($end);
        $data->pos = $pos;
        $data->late = $late;
        $data->count = count($pos);
        $data->percentage = number_format(abs($late / count($pos) * 100 - 100), 2);

        $html = view('pdfs.purchases-on-time-report')->with('pos', $data);
        return $this->pdf
            ->load($html)
            ->filename('purchases-on-time-report')
            ->download();
    }

    /**
     * COOR Sales Report
     * @return pdf
     */
    public function purchasingReport($start, $end)
    {
        // Get all vendors in alphabetical order
        $vendors = DB::table('vendors')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
        // Get all purchases between given dates
        $start = $this->correctYearFormat($start);
        $end = $this->correctYearFormat($end);
        $pos = DB::table('purchases')
        ->select('*')
        ->whereBetween('due_date', [$start, $end])
        ->orderBy('due_date', 'asc')
        ->get();
        // Loop through purchases to invert dates and calculate the total spent on all purchases
        $total_purchasing = 0.00;
        for($i=0;$i<count($pos);$i++){
            $pos[$i]->due_date = $this->invertDate($pos[$i]->due_date);
            $total_purchasing = $total_purchasing + $pos[$i]->po_total;
        }
        // Loop through vendors and get all purchases with those venodrs
        $counter = 0;
        $total_vps = 0;
        $total_avg = 0.00;
        $data = new stdClass;
        for($i=0;$i<count($vendors);$i++){
            // Query for the purchases with the selected vendor
            $vps = DB::table('purchases')
                ->select('*')
                ->where('vendor', $vendors[$i]->id)
                ->get();
            // if vps has a value then set the need values into an stdClass
            if(count($vps) > 0){
                $total = 0;
                for($c=0;$c<count($vps);$c++){
                    $total = $total + $vps[$c]->po_total;
                }
                $data->vps[$counter] = new stdClass;
                $data->vps[$counter]->po = $vps;
                $data->vps[$counter]->name = $vendors[$i]->name;
                $data->vps[$counter]->count = count($vps);
                $total_vps = $total_vps + count($vps);
                $data->vps[$counter]->total = number_format($total, 2, '.', '');
                $data->vps[$counter]->average = number_format($data->vps[$counter]->average = $total / count($vps), 2, '.', '');
                $total_avg = $total_avg + $data->vps[$counter]->average;
                $data->vps[$counter]->percentage = number_format(abs(number_format(abs($total / $total_purchasing * 100 - 100), 2) - 100.00), 2);
                $counter++;
            }
        }
        // Get company name for title
        $company = DB::table('companies')
            ->select('name')
            ->where('id', 1)
            ->get();
        // Set the main values
        $data->company = $company[0]->name;
        $data->start = $this->invertDate($start);
        $data->end = $this->invertDate($end);
        $data->total_pos = $total_vps;
        $data->total_average = number_format($total_avg, 2, '.', ',');
        $data->total_purchasing = number_format($total_purchasing, 2, '.', ',');

        $html = view('pdfs.purchases-report')->with('pos', $data);
        return $this->pdf
            ->load($html)
            ->filename('purchases-report')
            ->download();
    }

    /**
     * Purchases print out
     *
     * @return pdf
     */
    public function PurchasePrintOut($id)
    {
        // Get data
        $purchase = DB::table('purchases')->select('*')->where('id', $id)->get();
        $vendor = DB::table('vendors')->select('*')->where('id', $purchase[0]->vendor)->get();
        $company = DB::table('companies')->select('*')->where('id', 1)->get();
        // Convert Purchase Data
        for($i=0;$i<count($purchase);$i++):
            if($purchase[$i]->due_date):
                $purchase[$i]->due_date = $this->invertDate($purchase[$i]->due_date);
            endif;
            if($purchase[$i]->recv_date):
                $purchase[$i]->recv_date = $this->invertDate($purchase[$i]->recv_date);
            endif;
            if($purchase[$i]->po_total):
                $purchase[$i]->po_total = number_format($purchase[$i]->po_total, 2, '.', ',');
            endif;
            if($purchase[$i]->comments):
                $purchase[$i]->comments = explode("\n", $purchase[$i]->comments);
            endif;
            $purchase[$i]->items = $this->doubleDecode($purchase[$i]->items);
            for($m=0;$m<count($purchase[$i]->items);$m++):
                if($purchase[$i]->items[$m]['recv_date']):
                    $purchase[$i]->items[$m]['recv_date'] = $this->invertDate($purchase[$i]->items[$m]['recv_date']);
                endif;
                if($purchase[$i]->items[$m]['vendor_confirm_date']):
                    $purchase[$i]->items[$m]['vendor_confirm_date'] = $this->invertDate($purchase[$i]->items[$m]['vendor_confirm_date']);
                endif;
                if($purchase[$i]->items[$m]['unit_cost']):
                    $purchase[$i]->items[$m]['unit_cost'] = number_format($purchase[$i]->items[$m]['unit_cost'], 2, '.', '');
                endif;
                $set = [
                    'part_num' => $purchase[$i]->items[$m]['part_num'],
                    'qty_ordered' => $purchase[$i]->items[$m]['qty_ordered'],
                    'description' => $purchase[$i]->items[$m]['description'],
                    'due_date' => $purchase[$i]->due_date,
                    'unit_cost' => $purchase[$i]->items[$m]['unit_cost'],
                    'unit_of_measure' => $purchase[$i]->items[$m]['unit_of_measure'],
                    'total' => $total = number_format($purchase[$i]->items[$m]['qty_ordered'] * $purchase[$i]->items[$m]['unit_cost'], '2', '.', ',')
                ];
                $purchase[$i]->items[$m] = $set;
            endfor;
        endfor;
        // Convert Vendor Data
        $vendor[0]->ship_address = explode("\n", $vendor[0]->ship_address);
        $vendor[0]->purch_address = explode("\n", $vendor[0]->purch_address);
        $vendor[0]->remit_address = explode("\n", $vendor[0]->remit_address);
        // Convert Company Data
        $company[0]->address = explode("\n", $company[0]->address);
        // Set template data
        $data = new stdClass;
        $data->purchase = $purchase[0];
        $data->vendor = $vendor[0];
        $data->company = $company[0];
        // dd($data);
        $html = view('pdfs.purchase-printout')->with('data', $data);
        return $this->pdf
            ->load($html)
            ->filename('purchase')
            ->download();
    }

    /**
     * Receiver Print out
     *
     * @return pdf
     */
    public function ReceiverPrintOut($id)
    {
        // Get data
        $purchase = DB::table('purchases')->select('*')->where('id', $id)->get();
        $vendor = DB::table('vendors')->select('*')->where('id', $purchase[0]->vendor)->get();
        $company = DB::table('companies')->select('*')->where('id', 1)->get();
        // Convert Purchase Data
        for($i=0;$i<count($purchase);$i++):
            if($purchase[$i]->due_date):
                $purchase[$i]->due_date = $this->invertDate($purchase[$i]->due_date);
            endif;
            if($purchase[$i]->recv_date):
                $purchase[$i]->recv_date = $this->invertDate($purchase[$i]->recv_date);
            endif;
            if($purchase[$i]->po_total):
                $purchase[$i]->po_total = number_format($purchase[$i]->po_total, 2, '.', ',');
            endif;
            if($purchase[$i]->comments):
                $purchase[$i]->comments = explode("\n", $purchase[$i]->comments);
            endif;
            $purchase[$i]->items = $this->doubleDecode($purchase[$i]->items);
            for($m=0;$m<count($purchase[$i]->items);$m++):
                if($purchase[$i]->items[$m]['recv_date']):
                    $purchase[$i]->items[$m]['recv_date'] = $this->invertDate($purchase[$i]->items[$m]['recv_date']);
                endif;
                if($purchase[$i]->items[$m]['vendor_confirm_date']):
                    $purchase[$i]->items[$m]['vendor_confirm_date'] = $this->invertDate($purchase[$i]->items[$m]['vendor_confirm_date']);
                endif;
                if($purchase[$i]->items[$m]['unit_cost']):
                    $purchase[$i]->items[$m]['unit_cost'] = number_format($purchase[$i]->items[$m]['unit_cost'], 2, '.', '');
                endif;
                $set = [
                    'item' => $m+1,
                    'qty_ordered' => $purchase[$i]->items[$m]['qty_ordered'],
                    'qty_recv_good' => $purchase[$i]->items[$m]['qty_recv_good'],
                    'qty_rej' => $purchase[$i]->items[$m]['qty_rej'],
                    'qty_canceled' => $purchase[$i]->items[$m]['qty_canceled'],
                    'back_order_qty' => $purchase[$i]->items[$m]['back_order_qty'],
                    'unit_of_measure' => $purchase[$i]->items[$m]['unit_of_measure'],
                    'description' => $purchase[$i]->items[$m]['description']
                ];
                $purchase[$i]->items[$m] = $set;
            endfor;
        endfor;
        // Convert Vendor Data
        $vendor[0]->ship_address = explode("\n", $vendor[0]->ship_address);
        $vendor[0]->purch_address = explode("\n", $vendor[0]->purch_address);
        $vendor[0]->remit_address = explode("\n", $vendor[0]->remit_address);
        // Convert Company Data
        $company[0]->address = explode("\n", $company[0]->address);
        // Set template data
        $data = new stdClass;
        $data->purchase = $purchase[0];
        $data->vendor = $vendor[0];
        $data->company = $company[0];
        // dd($data);
        $html = view('pdfs.receival-printout')->with('data', $data);
        return $this->pdf
            ->load($html)
            ->filename('purchase')
            ->download();
    }
}