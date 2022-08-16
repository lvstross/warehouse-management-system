@extends('layouts.pdf')

@section('content')
<style>
    th,td { font-size: 12px; }
</style>
<body>
    <div class="padding-25 font" style="padding: 0 25px;">
        <div>
            <div class="float-left"><strong>{{ $data->company->name }}</strong></div>
            <div class="clear-fix"></div>
        </div>

        <div>
            <div class="float-left">
                @for($i = 0; $i < count($data->company->address); $i++)
                    <p class="sm-text margin-none padding-none">{{ $data->company->address[$i] }}</p>
                @endfor
                <p class="sm-text margin-none padding-none">Ph: {{ $data->company->phone }}</p>
                <p class="sm-text margin-none padding-none">Em: {{ $data->company->email }}</p>
            </div>
            <div class="float-right">Receival #  </strong><span>{{ $data->purchase->recv_num }}</span></div>
            <div class="clear-fix"></div>
        </div>

        <div class="space"></div>
        <div class="space"></div>
        <div class="space"></div>
        <h3 class="text-center">Receiver</h3>

        <div>
            <div class="half-width float-left padding-5">To:</div>
            <div class="half-width float-left padding-5">Ship To:</div>
            <div class="clear-fix"></div>
        </div>

        <div class="space"></div>

        <div>
            <div class="float-left half-width padding-5">
                @for($i = 0; $i < count($data->vendor->purch_address); $i++)
                    <p class="sm-text margin-none padding-none">{{ $data->vendor->purch_address[$i] }}</p>
                @endfor
            </div>
            <div class="float-left half-width padding-5">
                <p class="sm-text margin-none padding-none">{{ $data->company->name }}</p>
                @for($i = 0; $i < count($data->company->address); $i++)
                    <p class="sm-text margin-none padding-none">{{ $data->company->address[$i] }}</p>
                @endfor
            </div>
            <div class="clear-fix"></div>
        </div>

        <div class="space"></div>

        <div class="light-border">
            <div class="float-left half-width padding-5">
                <p class="sm-text margin-none padding-none"><strong>Receiver Number:</strong> {{$data->purchase->po_num}}</p>
                <p class="sm-text margin-none padding-none"><strong>Date Received:</strong> {{$data->purchase->recv_date}}</p>
                <p class="sm-text margin-none padding-none"><strong>Ship Via:</strong> {{$data->purchase->carrier}}</p>
                <p class="sm-text margin-none padding-none"><strong>Order Date:</strong> {{$data->purchase->entered_by}}</p>
            </div>
            <div class="float-left half-width padding-5">
                <p class="sm-text margin-none padding-none"><strong>Vendor Code:</strong> {{$data->vendor->name}}</p>
                <p class="sm-text margin-none padding-none"><strong>Phone:</strong> {{$data->vendor->phone}}</p>
                <p class="sm-text margin-none padding-none"><strong>PO Number:</strong> {{$data->purchase->po_num}}</p>
                <p class="sm-text margin-none padding-none"><strong>Terms:</strong> {{$data->purchase->terms}}</p>
            </div>
            <div class="clear-fix"></div>
        </div>

        <div class="space"></div>

        <div class="full-width">
            <table class="full-width">
                <thead>
                    <tr>
                        <th class="text-center underline">Item</th>
                        <th class="text-center underline">Ordered</th>
                        <th class="text-center underline">Good</th>
                        <th class="text-center underline">Rejected</th>
                        <th class="text-center underline">Canceled</th>
                        <th class="text-center underline">Back Order</th>
                        <th class="text-center underline">Unit</th>
                        <th class="text-center underline">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < count($data->purchase->items); $i++)
                            <tr>
                            @foreach($data->purchase->items[$i] as $key => $value)
                                    @if($key === 'item')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'qty_ordered')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'qty_recv_good')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'qty_rej')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'qty_canceled')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'back_order_qty')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'unit_of_measure')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'description')
                                        <td class="text-center">{{ $value }}</td>
                                    @endif
                            @endforeach
                            </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="more-space"></div>

        <div class="sig-bottom-right">
            <p class="padding-none margin-none sm-text">Quality Control Initials</p>
            <p class="padding-none margin-none sm-text">{{ $data->company->name }}</p>
        </div>
    </div>
</body>
@endsection
