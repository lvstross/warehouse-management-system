@extends('layouts.pdf')

@section('content')
<style>
    html { margin: 15px; }
</style>
<body style="margin: 0px;">
    <div class="full-width margin-none padding-none">
        <h2 class="text-center margin-none padding-none">Purchase Order Summary</h2>
        <h2 class="text-center margin-none padding-none">{{$pos->company}}</h2>
        <p class="text-center margin-none padding-none"><strong>Vendor Breakdown</strong></p>
        <p class="text-center margin-none padding-none">
            <strong>From:</strong> <em>{{ $pos->start }}</em> <strong>To:</strong> <em>{{ $pos->end }}</em>
        </p>
        <hr>
        <table style="width:100%;" class="table margin-none padding-none">
            <thead>
                <tr class="md-text">
                    <th class="text-center underline">Vendor</th>
                    <th class="text-center underline">Percent Of Total</th>
                    <th class="text-center underline">Total POs</th>
                    <th class="text-center underline">Average Amount</th>
                    <th class="text-center underline">Total</th>
                </tr>
            </thead>
            <tbody>
                @for($i=0;$i<count($pos->vps);$i++)
                    <tr class="md-text">
                        <td class="text-center">{{$pos->vps[$i]->name}}</td>
                        <td class="text-center">{{$pos->vps[$i]->percentage}} %</td>
                        <td class="text-center">{{$pos->vps[$i]->count}}</td>
                        <td class="text-center">${{$pos->vps[$i]->average}}</td>
                        <td class="text-center">${{$pos->vps[$i]->total}}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <hr>
        <div class="float-right" style="padding-right: 30px;"><p><strong>Total:</strong> <span>${{ $pos->total_purchasing }}</span></p></div>
        <div class="float-right" style="padding-right: 30px;"><p><strong>Average:</strong> <span>{{ $pos->total_average }}</span></p></div>
        <div class="float-right" style="padding-right: 30px;"><p><strong>Total POS:</strong> <span>{{ $pos->total_pos }}</span></p></div>
        <div class="float-right" style="padding-right: 30px;"><p><strong>Total Percent:</strong> <span>100.00 %</span></p></div>
    </div>
</body>
@endsection