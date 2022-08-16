@extends('layouts.pdf')

@section('content')
<style>
    html { margin: 15px; }
    table { width: 1090px;table-layout:fixed; }
</style>
<body style="margin: 0px;">
    <div class="full-width margin-none padding-none">
        <p class="margin-none padding-none">
            @php
                echo date('n/j/Y');
            @endphp
            - On Time Delivery Report - {{ $pos->title }}
        </p>
        <table class="table margin-none padding-none">
            <thead>
                <tr class="sm-text">
                    <th>Will Ship</th>
                    <th>Due Date</th>
                    <th>Date Shipped</th>
                    <th>On Time</th>
                    <th>Part #</th>
                    <th>Qty</th>
                    <th>Customer</th>
                    <th>P.O. #</th>
                </tr>
            </thead>
            <tbody>
                @for($i=0;$i<count($pos->pos);$i++)
                    <tr class="sm-text">
                        <td class="td">{{$pos->pos[$i]->will_ship}}</td>
                        <td class="td">{{$pos->pos[$i]->due_date}}</td>
                        <td class="td">{{$pos->pos[$i]->ship_date}}</td>
                        <td class="td">{{$pos->pos[$i]->on_time}}</td>
                        <td class="td">{{$pos->pos[$i]->part_num}}</td>
                        <td class="td">{{$pos->pos[$i]->qty}}</td>
                        <td class="td">{{$pos->pos[$i]->customer}}</td>
                        <td class="td">{{$pos->pos[$i]->po_num}}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <div class="float-right" style="padding-right: 30px;"><p><strong>On Time Delivery in persent:</strong> <span class="underline">{{ $pos->percentage }}%</span></p></div>
        <div class="float-right" style="padding-right: 30px;"><p><strong>Late Deliveries:</strong> <span class="underline">{{ $pos->late }}</span></p></div>
        <div class="float-right" style="padding-right: 30px;"><p><strong>Total Items this Report:</strong> <span class="underline">{{ $pos->count }}</span></p></div>
    </div>
</body>
@endsection