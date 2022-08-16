@extends('layouts.pdf')

@section('content')
<body>
    <style>
        h3 {font-weight: normal;font-size: 14px;}
        li {font-size: 12px;}
    </style>
    @php
    // Upcase Company Name
    $upperName = strtoupper($cert[0][0]->name);
    $lotCount = count($cert[3]);
    $firstCount = 0;

    if($lotCount > 10){
        $firstCount = 10;
    }else{
        $firstCount = $lotCount;
    }
    @endphp
    <div class="font padding-25-sides">
        <h2 class="text-center margin-little">CERTIFICATION OF CONFORMANCE</h2>
        <h3 class="text-center margin-little">{{ $cert[0][0]->name }}</h3>
        <div class="full-width margin-none">
            @for($i = 0; $i < count($cert[0][0]->address); $i++)
                <p class="text-center margin-none sm-text">{{ $cert[0][0]->address[$i] }}</p>
            @endfor
            <p class="text-center margin-none xs-text">MADE IN THE USA</p>
        </div>

        <div class="full-width margin-bottom-10">
            <div class="less-qtr-width float-left">
                <h3 class="sm-padding-left margin-bottom-5">Customer:</h3>
                <p class="sm-padding-left sm-text margin-none">Address: </p>
            </div>
            <div class="three-qtr-width float-left">
                <h3 class="margin-bottom-5">{{ $cert[1]->customer['name'] }}</h3>
                @for($i = 0; $i < count($cert[1]->customer['shipto']); $i++)
                    <p class="sm-text margin-none">{{ $cert[1]->customer['shipto'][$i] }}</p>
                @endfor
            </div>
            <div class="clear-fix"></div>
        </div>

        <div class="full-width">
            <div class="less-qtr-width float-left height-200">
                <h3 class="sm-padding-left less-margin-tb">Date:</h3>
                <h3 class="sm-padding-left less-margin-tb">PO #:</h3>
                <h3 class="sm-padding-left less-margin-tb">Packing List #:</h3>
                <h3 class="sm-padding-left less-margin-tb">Part #:</h3>
                <h3 class="sm-padding-left less-margin-tb">Total Qty:</h3>
            </div>
            <div class="three-qtr-width float-left height-200">
                <h3 class="less-margin-tb">{{ $cert['date'] }}</h3>
                <h3 class="less-margin-tb">{{ $cert[1]->po_num }}</h3>
                <h3 class="less-margin-tb">{{ $cert[1]->inv_num }}</h3>
                <h3 class="less-margin-tb">{{ $cert[2][0]->name }}</h3>
                <h3 class="less-margin-tb">{{ $cert['qty'] }}</h3>
            </div>
            <div class="clear-fix"></div>
        </div>
        <div class="full-width margin-top-25">
            <h3 class="sm-padding-left margin-none">SELLER CERTIFIES THAT: </h3>
            <ol class="margin-little">
                <li>Materials and/or parts(s) were produced in conformance with all contractually applicable government and/or
                 customer specifications as referenced in, and furnished with the above noted purchase order.</li>
                 <li>The materials and/or part(s) furnished under the above purchase order were produced either from
                 materials furnished by the customer for the production of such parts or from materials from which
                the seller has available for examination. Other evidence of conformance is available upon request.</li>
                <li>All Processes required in the production of these materials and/or part(s) are listed below.</li>
                <li>When they were performed by a facility other than the seller and/or the processing facility has specifically
                  requested by the buyer. Such facilities have been approved by the cognizant government agency.</li>
                <li>Certificate of Registration ISO 9001:2015 and AS9100-D.</li>
                <li>{{ $cert[0][0]->name }} uses no metals in our products and fully comply with DFARS 252.225-7008. Specialty metals
                are defined in this clauses as certain steels, titanium's and zirconium based alloys.</li>
                <li>{{ $cert[0][0]->name }} does not use Class I - Ozone Depleting materials in the production of or with its products.</li>
                <li>{{ $cert[0][0]->name }} does not use any materials as called out in the Dodd Frank Act Section 1502 (conflict minerals).</li>
                <li>{{ $cert[0][0]->name }} is the manufacturer of all seals and sleeves sold by {{ $cert[0][0]->name }}. Therefore we do not sell counterfeit parts.</li>
                @if(count($cert['seller_certifies']) > 0 && $cert['seller_certifies'][0] != '')
                    @for($i = 0; $i < count($cert['seller_certifies']); $i++)
                        <li>{{ $cert['seller_certifies'][$i] }}</li>
                    @endfor
                @endif
            </ol>
        </div>
        <br>
        <div class="full-width">
            <h3 class="sm-padding-left margin-little">{{ $cert[2][0]->name }} &nbsp;&nbsp; Drawing Revision {{ $cert[2][0]->rev }} &nbsp;&nbsp; {{ $cert[2][0]->rev_date }} &nbsp;&nbsp; Unlimited Shelf-Life</h3>
            <h3 class="sm-padding-left margin-little">Materials: &nbsp;&nbsp;&nbsp; {{ $cert[2][0]->material }}</h3>
        </div>

        <div class="full-width">
            <div class="full-width">
                <div class="just-under-half inline">
                    <h3 class="sm-padding-left margin-little"><span class="underline">Batch/Lot/Serial #:</span> &nbsp; <span class="underline">Cure Date:</span> &nbsp; <span class="underline">Qty:</span></h3>
                </div>
                <div class="just-under-half inline">
                    <h3 class="sm-padding-left margin-little"><span class="underline">Batch/Lot/Serial #:</span> &nbsp; <span class="underline">Cure Date:</span> &nbsp; <span class="underline">Qty:</span></h3>
                </div>
            </div>
            <div class="full-width">
                <div class="just-under-half float-left">
                        @for($i=0; $i < $firstCount; $i++)
                            <p class="sm-padding-left margin-none sm-text">
                                <span style="padding-left:5px; margin-right:100px;">{{ $cert[3][$i][0] }}</span>
                                <span style="margin-right:45px;">{{ $cert[3][$i][1] }}</span>
                                <span>{{ $cert[3][$i][2] }}</span>
                            </p>
                        @endfor
                </div>
                <div class="just-under-half float-left">
                    @if($lotCount > 10)
                        @for($i=10; $i < $lotCount; $i++)
                            <p class="sm-padding-left margin-none sm-text">
                                <span style="padding-left:10px; margin-right:100px;">{{ $cert[3][$i][0] }}</span>
                                <span style="margin-right:45px;">{{ $cert[3][$i][1] }}</span>
                                <span>{{ $cert[3][$i][2] }}</span>
                            </p>
                        @endfor
                    @endif
                </div>
                <div class="clear-fix"></div>
            </div>

            <div class="full-width margin-top-25" style="border-top: 1px dotted #aaa;">
                <h3 class="sm-text sm-padding-left margin-top-5">Manufacturer: &nbsp;&nbsp;&nbsp;&nbsp; {{ $cert[0][0]->name }}</h3>
            </div>

            <div class="full-width margin-top-none">
                <div class="half-width float-left" style="width: 30%;">
                    <h3 class="sm-text sm-padding-left margin-top-5">AUTHORIZED SIGNATURE</h3>
                </div>
                <div class="half-width float-left" style="width: 65%;">
                    <p style="padding-bottom: 0; margin: 0;">___________________________________</p>
                    <span class="sm-text">Authorized By: </span>
                    <p style="padding: 5px 0; margin: 0;">___________________________________</p>
                    <span class="sm-text">Authorized Date: </span>
                </div>
                <div class="clear-fix"></div>
            </div>
            {{-- <div class="full-width">
                <span class="sm-text control"> Control number </span>
            </div> --}}
        </div>
    </div>
</body>
@endsection
