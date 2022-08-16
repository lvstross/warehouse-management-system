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
            - Current Open Order Production Sheet
        </p>
        <table class="table margin-none padding-none">
            <thead>
                <tr class="tr md-text">
                    <th class="text-center">OID #</th>
                    <th class="text-center">Will Ship</th>
                    <th class="text-center">Rt</th>
                    <th class="text-center">Part #</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">NR?</th>
                    <th class="text-center">Stock</th>
                    @for($i=0;$i<10;$i++)
                        <th class="text-center">Rt#</th>
                        <th class="text-center">Qty</th>
                    @endfor
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
                        @if($pos[$i]->need_routers == 'Yes')
                            <td class="td">{{$pos[$i]->need_routers}}</td>
                        @else
                            <td class="td"></td>
                        @endif
                        <td class="td">{{ substr($pos[$i]->stock, 0, 4) }}</td>
                        @for($r=0;$r<10;$r++)
                            @if(array_key_exists($r, $pos[$i]->routers))
                                @foreach($pos[$i]->routers[$r] as $key => $value)
                                    @if($key == 'router')
                                        <td class="td sm-text">{{$value}}</td>
                                    @elseif($key == 'qty')
                                        <td class="td sm-text">{{$value}}</td>
                                    @endif
                                @endforeach
                            @else
                                <td class="td sm-text"></td>
                                <td class="td sm-text"></td>
                            @endif
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</body>
@endsection