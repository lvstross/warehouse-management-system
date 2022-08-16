@extends('layouts.pdf')

@section('content')
<style>
    html { margin: 15px; }
</style>
<body style="margin: 0px;">
    <div class="full-width margin-none padding-none">
        <p class="margin-none padding-none">
        <h2 class="text-center margin-none padding-none">On Time Delivery Report</h2>
        <h3 class="text-center margin-none padding-none">{{$pos->vendor}}</h3>
        <p class="text-center margin-none padding-none">
            <strong>From:</strong> <em>{{ $pos->start }}</em> <strong>To:</strong> <em>{{ $pos->end }}</em>
        </p>
        <hr>
        <table style="width:100%;" class="table margin-none padding-none">
            <thead>
                <tr>
                    <th class="text-center underline">P.O. #</th>
                    <th class="text-center underline">Vendor</th>
                    <th class="text-center underline">Due Date</th>
                    <th class="text-center underline">Date Received</th>
                    <th class="text-center underline">On Time</th>
                    <th class="text-center underline">P.O. Total</th>
                </tr>
            </thead>
            <tbody>
                @for($i=0;$i<count($pos->pos);$i++)
                    <tr>
                        <td class="text-center">{{$pos->pos[$i]->po_num}}</td>
                        <td class="text-center">{{$pos->pos[$i]->vendor}}</td>
                        <td class="text-center">{{$pos->pos[$i]->due_date}}</td>
                        <td class="text-center">{{$pos->pos[$i]->recv_date}}</td>
                        <td class="text-center">{{$pos->pos[$i]->on_time}}</td>
                        <td class="text-center">{{$pos->pos[$i]->po_total}}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <hr>
        <div class="float-right" style="padding-right: 30px;"><p><strong>On Time Delivery Percentage:</strong> <span class="underline">{{ $pos->percentage }}%</span></p></div>
        <div class="float-right" style="padding-right: 30px;"><p><strong>Late Deliveries:</strong> <span class="underline">{{ $pos->late }}</span></p></div>
        <div class="float-right" style="padding-right: 30px;"><p><strong>Total Items this Report:</strong> <span class="underline">{{ $pos->count }}</span></p></div>
    </div>
</body>
@endsection