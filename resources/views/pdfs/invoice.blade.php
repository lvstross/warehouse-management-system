@extends('layouts.pdf')

@section('content')
<body>
    <div class="padding-25 font" style="padding: 0 25px;">
        <div>
            <div class="float-left"><strong>{{ $invoice[16] }}</strong></div>
            <div class="float-right"><strong><span class="sm-text no-bold">{{ $invoice[13] }}</span></div>
            <div class="clear-fix"></div>
        </div>

        <div>
            <div class="float-left">
                @for($i = 0; $i < count($invoice[17]); $i++)
                    <p class="sm-text margin-none padding-none">{{ $invoice[17][$i] }}</p>
                @endfor
                <p class="sm-text margin-none padding-none">Ph: {{ $invoice[18] }}</p>
                <p class="sm-text margin-none padding-none">Em: {{ $invoice[19] }}</p>
            </div>
            <div class="float-right">Invoice #  </strong><span>{{ $invoice[1] }}</span></div>
            <div class="clear-fix"></div>
        </div>

        <div class="space"></div>
        <div class="space"></div>
        <div class="space"></div>

        <div>
            <div class="half-width float-left padding-5">Bill To:</div>
            <div class="half-width float-left padding-5">Ship To:</div>
            <div class="clear-fix"></div>
        </div>

        <div class="space"></div>

        <div>
            <div class="float-left half-width padding-5 light-border">
                @for($i = 0; $i < count($invoice[4]); $i++)
                    <p class="sm-text margin-none padding-none">{{ $invoice[4][$i] }}</p>
                @endfor
            </div>
            <div class="float-left half-width padding-5 light-border">
                @for($i = 0; $i < count($invoice[3]); $i++)
                    <p class="sm-text margin-none padding-none">{{ $invoice[3][$i] }}</p>
                @endfor
            </div>
            <div class="clear-fix"></div>
        </div>

        <div class="space"></div>

        <div>
            <div class="light-border under-half padding-5 float-left set-height">
                <div class="inline half-width sm-text"><span>Customer Order #</span></div>
                <div class="inline half-width md-text"><span>{{ $invoice[6] }}</span></div>
            </div>
            <div class="light-border qtr-width padding-5 float-left set-height">
                <div class="inline half-width sm-text"><span>Ship Date</span></div>
                <div class="inline half-width md-text"><span>{{ $invoice[2] }}</span></div>
                <div></div>
                <div class="text-center full-width sm-text"><span>HSCID 3910.00.00/8803.30<span></div>
            </div>
            <div class="light-border qtr-width padding-5 float-left set-height">
                <div class="float-left half-width sm-text">Carrier</div>
                <div class="float-right half-width md-text"><strong>{{ $invoice[11] }}</strong></div>
                <div class="clear-fix"></div>
            </div>
            <div class="clear-fix"></div>
        </div>

        <div class="space"></div>

        <div class="full-width max-150">
            <table>
                <thead>
                    <tr>
                        <th style="width: 100px;" class="text-center padding-20-sides underline">Part Number</th>
                        <th class="text-center padding-46-sides underline">Quantity</th>
                        <th style="padding-left:140px;" class="text-center padding-46-sides underline">Price</th>
                        <th class="text-center padding-46-sides underline">Extension</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 0; $i < count($invoice[7]); $i++)
                            <tr>
                            @foreach($invoice[7][$i] as $key => $value)
                                    @if($key === 'product' && $value !== '')
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'qty' && $value != 0)
                                        <td class="text-center">{{ $value }}</td>
                                    @elseif($key === 'unit' && $value != 0)
                                        <td style="padding-left:100px;" class="text-center">${{ $value }}</td>
                                    @elseif($key === 'extended' && $value != 0.00)
                                        <td class="text-center">${{ $value }}</td>
                                    @endif
                            @endforeach
                            </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="more-space"></div>

        <div class="float-right border padding-15">
            <div class="full-width margin-bottom-20">
                <div class="inline qtr-width">
                    <span>Sub Total</span>
                </div>
                <div class="inline small-width text-right">
                    <span>${{ $invoice[14] }}</span>
                </div>
            </div>
            <div class="full-width margin-bottom-20">
                <div class="inline qtr-width">
                    <span>Shipping Charges</span>
                </div>
                <div class="inline small-width text-right">
                    <span>${{ $invoice[9] }}</span>
                </div>
            </div>
            <div class="full-width margin-bottom-20">
                <div class="inline qtr-width">
                    <span>Misc. Charges</span>
                </div>
                <div class="inline small-width text-right">
                    <span>${{ $invoice[8] }}</span>
                </div>
            </div>
            <div class="full-width">
                <div class="inline qtr-width">
                    <span>Invoice Total</span>
                </div>
                <div class="inline small-width text-right">
                    <span>${{ $invoice[10] }}</span>
                </div>
            </div>
        </div>
        <div class="clear-fix"></div>


        {{-- <div class="sm-text control">
            <span>{{ $invoice[21] }}</span>
        </div> --}}

    </div>
</body>
@endsection
