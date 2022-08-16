@extends('layouts.pdf')
@section('content')
<body>
    <style>
        p,li {font-size: 14px;}
        th, td {font-size: 12px;}
        table {table-layout: fixed; width: 100%}
        td {word-wrap:break-word;}
    </style>
    <pre>
    @php
    usort($shipticket['routers'], function($a, $b){
        return strcmp($a->r_num, $b->r_num);
    });
    @endphp
    </pre>
    <div class="padding-25 font" style="padding: 0 25px;">
        <div class="float-left xlg-text">Inventory Ship Ticket</div>
        <div class="float-right xlg-text">{{$shipticket['date']}}</div>
        <div class="clear-fix"></div>
        <br>
        <div style="width:100%;border-bottom:1px solid #000;"></div>
        <br>
        <span class="md-text">Invoice #: ______________________</span><br>
        <span class="md-text">Part Number: {{ $shipticket['part_num'] }} {{ $shipticket['prod_description'] }}</span><br>
        <span class="md-text">Rev / Rev Date: {{ $shipticket['prod_rev'] }} {{ $shipticket['prod_rev_date'] }}</span><br>
        <span class="md-text">Qty: {{ $shipticket['qty'] }}</span><br>
        <span class="md-text">Customer: {{ $shipticket['customer'] }}</span><br>
        <span class="md-text">Purchase Order Number: {{ $shipticket['po_num'] }}</span><br>
        @if($shipticket['cust_req'] != null)
            <span class="md-text">Customer Requirments: </span><br>
            <ol>
                @for($i = 0; $i < count($shipticket['cust_req']); $i++)
                    <li>{{ $shipticket['cust_req'][$i] }}</li>
                @endfor
            </ol>
        @else
            <span class="md-text">Customer Requirments: NA</span><br>
        @endif
        <p style="font-size: 16px;">Routers</p>
        <div class="full-width">
            <table>
                <thead>
                    <tr style="background-color: #ddd;">
                        <th style="padding-top:3px; width: 200px; font-weight: normal;" class="text-center">Router Number</th>
                        <th style="padding-top:3px; width: 200px; font-weight: normal;" class="text-center">Cure Qtr</th>
                        <th style="padding-top:3px; width: 200px; font-weight: normal;" class="text-center">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < count($shipticket['routers']); $i++)
                        <tr>
                            <td class="text-center">{{ $shipticket['routers'][$i]->r_num }}</td>
                            @if($shipticket['routers'][$i]->cure_qtr)
                                <td class="text-center">{{ $shipticket['routers'][$i]->cure_qtr }}</td>
                            @endif
                            <td class="text-center">{{ $shipticket['routers'][$i]->apply_qty }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <p style="font-size: 16px;">Boxes</p>
        <div class="full-width">
            <table>
                <thead>
                    <tr style="background-color: #ddd;">
                        <th style="padding:3px; width: 95px; font-weight: normal;" class="text-center">Box #</th>
                        <th style="padding:3px; width: 95px; font-weight: normal;" class="text-center">Router # (s)</th>
                        <th style="padding:3px; width: 95px; font-weight: normal;" class="text-center">Cure Qtr</th>
                        <th style="padding:3px; width: 95px; font-weight: normal;" class="text-center">Qty</th>
                        <th style="padding:3px; width: 95px; font-weight: normal;" class="text-center">Weight</th>
                        <th style="padding:3px; width: 95px; font-weight: normal;" class="text-center">Dimension</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < count($shipticket['boxes']); $i++)
                        <tr>
                            <td class="text-center">{{ $i+1 }} of {{ count($shipticket['boxes']) }}</td>
                            <td class="text-center">{{ $shipticket['boxes'][$i]->router_num }}</td>
                            <td class="text-center">{{ $shipticket['boxes'][$i]->cure_qtr }}</td>
                            <td class="text-center">{{ $shipticket['boxes'][$i]->qty }}</td>
                            <td class="text-center">{{ $shipticket['boxes'][$i]->wt }}</td>
                            <td class="text-center">{{ $shipticket['boxes'][$i]->dim }}</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        <br>
        <br>
        @if($shipticket['trip_count'] == 'Yes')
            <p>The purpose of the below section is to ensure the customer that {{ $shipticket['company'] }} have assigned (3) people to verify the count in
            this shipment. Each person performs an individual piece-by-piece count per each lot number on each purchase order listed.</p>
            <p>Quantities listed are accurate and verified by:</p>
            <div class="full-width">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 300px;">Name</th>
                            <th style="width: 100px;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 0; $i < 3; $i++)
                        <tr>
                            <td style="border-bottom: 1px solid #000;padding-top: 30px;"></td><td style="border-bottom: 1px solid #000;padding-top: 30px;"></td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            <br>
            <br>
        @endif
        <p>Quality Approval ____________________________________________</p>
    </div>
</body>
@endsection
