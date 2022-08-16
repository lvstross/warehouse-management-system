@extends('layouts.pdf')

@section('content')
<style>
    html { margin: 15px; }
</style>
<body style="margin: 0px;">
    <div class="full-width margin-none padding-none">
        <p class="margin-none padding-none">
            @php
                echo date('n/j/Y')
            @endphp
            @if($pos->status == 'Choose An Option' || $pos->status == 'Open')
                - <strong>Projection Report From:</strong> <em>{{ $pos->start }}</em> <strong>To:</strong> <em>{{ $pos->end }}</em>
            @else
                - <strong>Sales Report From:</strong> <em>{{ $pos->start }}</em> <strong>To:</strong> <em>{{ $pos->end }}</em>
            @endif
        </p>
        <table class="table margin-none padding-none">
            <thead>
                <tr class="md-text">
                    <th class="text-center">Due Date</th>
                    <th class="text-center">Will Ship</th>
                    @if($pos->status == 'Closed')
                        <th class="text-center">Ship Date</th>
                    @endif
                    <th class="text-center">Customer</th>
                    <th class="text-center">P.O. #</th>
                    <th class="text-center">Part #</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Stock</th>
                    <th class="text-center">Status</th>
                    @if($pos->show)
                        <th class="text-center">Sales</th>
                    @endif
                    <th class="text-center">Customer Contract Requirements and Notes</th>
                </tr>
            </thead>
            <tbody>
                @for($i=0;$i<count($pos->pos);$i++)
                    <tr class="md-text">
                        <td class="td">{{$pos->pos[$i]->due_date}}</td>
                        <td class="td">{{$pos->pos[$i]->will_ship}}</td>
                        @if($pos->status == 'Closed')
                            <td class="td">{{$pos->pos[$i]->ship_date}}</td>
                        @endif
                        <td class="td">{{$pos->pos[$i]->customer}}</td>
                        <td class="td">{{$pos->pos[$i]->po_num}}</td>
                        <td class="td">{{$pos->pos[$i]->part_num}}</td>
                        <td class="td text-center">{{$pos->pos[$i]->qty}}</td>
                        <td class="td">{{ substr($pos->pos[$i]->stock, 0, 5) }}</td>
                        <td class="td">{{ $pos->pos[$i]->status }}</td>
                        @if($pos->show)
                            <td class="td">{{ $pos->pos[$i]->sales }}</td>
                        @endif
                        <td class="td">{{ substr($pos->pos[$i]->cust_req, 0, 45) }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
        @if($pos->show)
            <div class="float-right" style="padding-right: 30px;"><p><strong>Total Sales:</strong> <span class="underline">${{ $pos->total_sales }}</span></p></div>
        @endif
        <div class="float-right" style="padding-right: 30px;"><p><strong>Total Qty:</strong> <span class="underline">{{ $pos->total_qty }}</span></p></div>
    </div>
</body>
@endsection