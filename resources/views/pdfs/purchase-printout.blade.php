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
            <div class="float-right">PO #  </strong><span>{{ $data->purchase->po_num }}</span></div>
            <div class="clear-fix"></div>
        </div>

        <div class="space"></div>
        <div class="space"></div>
        <div class="space"></div>
        <h3 class="text-center">Purchase Order</h3>

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
                <p class="sm-text margin-none padding-none"><strong>Order Number:</strong> {{$data->purchase->po_num}}</p>
                <p class="sm-text margin-none padding-none"><strong>Order Date:</strong> {{$data->purchase->enter_date}}</p>
                <p class="sm-text margin-none padding-none"><strong>Vendor:</strong> {{$data->vendor->name}}</p>
                <p class="sm-text margin-none padding-none"><strong>Vendor Phone:</strong> {{$data->vendor->phone}}</p>
                <p class="sm-text margin-none padding-none"><strong>Vendor FAX:</strong> {{$data->vendor->fax}}</p>
                <p class="sm-text margin-none padding-none"><strong>Vendor Ext:</strong> {{$data->vendor->ext}}</p>
            </div>
            <div class="float-left half-width padding-5">
                <p class="sm-text margin-none padding-none"><strong>Ship Via:</strong> {{$data->purchase->carrier}}</p>
                <p class="sm-text margin-none padding-none"><strong>Terms:</strong> {{$data->purchase->terms}}</p>
                <p class="sm-text margin-none padding-none"><strong>Contact:</strong> {{$data->vendor->contact}}</p>
                <p class="sm-text margin-none padding-none"><strong>Purchaseing Agent:</strong> {{$data->purchase->entered_by}}</p>
            </div>
            <div class="clear-fix"></div>
        </div>

        <div class="space"></div>

        <div class="full-width">
            <table class="full-width">
                <thead>
                    <tr>
                        <th class="text-center underline">Item #</th>
                        <th class="text-center underline">Qty Ordered</th>
                        <th class="text-center underline">Description</th>
                        <th class="text-center underline">Date Required</th>
                        <th class="text-center underline">Unit Cost</th>
                        <th class="text-center underline">Unit</th>
                        <th class="text-center underline">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < count($data->purchase->items); $i++)
                            <tr>
                            @foreach($data->purchase->items[$i] as $key => $value)
                                    @if($key === 'part_num')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'qty_ordered')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'description')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'due_date')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'unit_cost')
                                        <td class="text-center">${{ $value }}</td>
                                    @elseif($key === 'unit_of_measure')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'total')
                                        <td class="text-center">${{ $value }}</td>
                                    @endif
                            @endforeach
                            </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="space"></div>
        <div class="space"></div>

        <div class="float-right padding-15">
            <div class="full-width margin-bottom-20">
                <div class="inline qtr-width">
                    <span>Order Total: </span>
                </div>
                <div class="inline small-width text-right">
                    <span>${{ $data->purchase->po_total }}</span>
                </div>
            </div>
        </div>
        <div class="clear-fix"></div>
        @for($i=0; $i<count($data->purchase->comments); $i++)
            <p class="text-center padding-none margin-none sm-text">{{ $data->purchase->comments[$i] }}</p>
        @endfor
        <br>
        <p class="text-center padding-none margin-none sm-text">Thank you,</p>
        <p class="text-center padding-none margin-none sm-text">{{ $data->purchase->entered_by }}</p>

        <div class="sig-bottom-right">
            <p class="padding-none margin-none sm-text">Authorized Signature</p>
            <p class="padding-none margin-none sm-text">{{ $data->company->name }}</p>
        </div>
    </div>
</body>
@endsection
