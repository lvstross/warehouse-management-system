@extends('layouts.pdf')

@section('content')
<body>
    <div class="padding-50-lr font">
        <h1 class="text-center">Invoice Report</h1>
        <div class="full-width">
            <div class="inline less-qtr-width text-center underline"><p><strong>Invoice Number</strong></p></div>
            <div class="inline less-qtr-width text-center underline"><p><strong>Shipping Date</strong></p></div>
            <div class="inline less-qtr-width text-center underline"><p><strong>Customer</strong></p></div>
            <div class="inline less-qtr-width text-center underline"><p><strong>Extended Total</strong></p></div>
        </div>
        <div class="full-width">
            @for($i = 0; $i < count($report); $i++)
                @if($i !== count($report) - 1)
                    @for($n = 0; $n < count($report[$i]); $n++)
                        <div class="float-left less-qtr-width">
                            <p class="text-center less-margin-tb">{{ $report[$i][$n] }}</p>
                        </div>
                    @endfor
                @endif
                <div class="clear-fix"></div>
            @endfor
        </div>
        <h3 class="float-right">Total: {{ end($report) }}</h3>
    </div>
</body>
@endsection