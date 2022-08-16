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
            - Current Open Order Report
        </p>
        <table class="table margin-none padding-none">
            <thead>
                <tr class="md-text">
                    <th class="text-center">OID #</th>
                    <th class="text-center">Due Date</th>
                    <th class="text-center">Will Ship</th>
                    <th class="text-center">Rating</th>
                    <th class="text-center">Sooner?</th>
                    <th class="text-center">Customer</th>
                    <th class="text-center">P.O. #</th>
                    <th class="text-center">Part #</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Stock</th>
                    <th class="text-center">Customer Contract Requirements and Notes</th>
                </tr>
            </thead>
            <tbody>
                @for($i=0;$i<count($pos);$i++)
                    <tr class="md-text">
                        <td class="td text-center">{{$pos[$i]->key}}</td>
                        <td class="td">{{$pos[$i]->due_date}}</td>
                        <td class="td">{{$pos[$i]->will_ship}}</td>
                        <td class="td text-center">{{$pos[$i]->rating}}</td>
                        @if($pos[$i]->sooner == 'Yes')
                            <td class="td">{{$pos[$i]->sooner}}</td>
                        @else
                            <td class="td"></td>
                        @endif
                        <td class="td">{{$pos[$i]->customer}}</td>
                        <td class="td">{{$pos[$i]->po_num}}</td>
                        <td class="td">{{$pos[$i]->part_num}}</td>
                        <td class="td text-center">{{$pos[$i]->qty}}</td>
                        <td class="td">{{ substr($pos[$i]->stock, 0, 5) }}</td>
                        <td class="td">{{ substr($pos[$i]->cust_req, 0, 75) }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</body>
@endsection