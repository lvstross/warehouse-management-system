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
            - Daily Employee Task Sheet
        </p>
        <table class="table margin-none padding-none">
            <thead>
                <tr class="tr md-text">
                    <th class="text-center">OID #</th>
                    <th class="text-center">Will Ship</th>
                    <th class="text-center">Rt</th>
                    <th class="text-center">Part #</th>
                    <th class="text-center">Qty</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                </tr>
            </thead>
            <tbody>
                @for($i=0;$i<count($pos);$i++)
                    <tr class="tr md-text">
                        <td class="td text-center">{{$pos[$i]->key}}</td>
                        <td class="td">{{$pos[$i]->will_ship}}</td>
                        <td class="td text-center">{{$pos[$i]->rating}}</td>
                        <td class="td">{{$pos[$i]->part_num}}</td>
                        <td class="td text-center">{{$pos[$i]->qty}}</td>
                        <td class="td" style="padding-left:100px; padding-right:100px;"></td>
                        <td class="td" style="padding-left:100px; padding-right:100px;"></td>
                        <td class="td" style="padding-left:100px; padding-right:100px;"></td>
                        <td class="td" style="padding-left:100px; padding-right:100px;"></td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</body>
@endsection