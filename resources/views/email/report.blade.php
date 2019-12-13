<html>
    <head>

    </head>
    <body>
        <h4 style="margin-bottom:-10px;">Dear All, Here Social Media Growth Report</h4>
        <h5>{{date('l, d F Y')}}</h5>
        <br><br>
        @if ($detail!="")
        @foreach ($detail as $group => $realdata )
            <span><b>{{$realdata[0]->groupMaster["group_name"]}}</b></span>
            <table width="70%" style="border:solid 1px #000;text-align:center;">
                <thead>
                    <tr style="border:solid 1px #000;background-color:green;color:white;">
                        <th>Name</th>
                        <th>{{date('Y-m-d', strtotime(date('Y-m-d')))}}</th>
                        <th>{{date('Y-m-d', strtotime(date('Y-m-d')."-1 days"))}}</th>
                        <th>Growth</th>
                        <th>Growth (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                     $now_sum = 0;
                     $yesterday_sum = 0;
                     $growth_sum = 0;
                     $percent_sum = 0;
                    @endphp
                    @foreach ($realdata as $v)
                        <tr style="border:solid 1px #000;">
                            <td style="text-align: left">{{$v->socmed_name}}</td>
                            <td style="text-align: right">{{$v->detail_data["now"]}}</td>
                            <td style="text-align: right">{{$v->detail_data["yesterday"]}}</td>
                            <td style="text-align: right">{!!numbercolor($v->detail_data["count"])!!}</td>
                            <td style="text-align: right">{!!numbercolor($v->detail_data["percent"])!!}</td>
                        </tr>
                        @php
                            $now_sum += removeRupiah($v->detail_data["now"]);
                            $yesterday_sum += removeRupiah($v->detail_data["yesterday"]);
                            $percent_sum += (double)($v->detail_data["percent"]);
                            $growth_sum += removeRupiah($v->detail_data["count"]);
                        @endphp
                    @endforeach
                    <tr style="border:solid 1px #000;background-color:green;color:white;">
                        <th>TOTAL</th>
                        <th style="text-align: right">{{rupiah($now_sum)}}</th>
                        <th style="text-align: right">{{rupiah($yesterday_sum)}}</th>
                        <th style="text-align: right">{{rupiah($growth_sum)}}</th>
                        <th style="text-align: right">{{(double)$percent_sum}}</th>
                    </tr>
                </tbody> 
            </table><br>
        @endforeach
        @endif
        @if ($alexa!="")
        <hr>
        <h4>Alexa Analyze Report</h4>
        @foreach ($alexa as $group => $realdata )
        <br><br>
        <span><b>{{$realdata[0]->groupMaster["group_name"]}}</b></span>
        <table width="50%" style="border:solid 1px #000;text-align:center;">
            <thead>
                <tr style="border:solid 1px #000;background-color:green;color:white;">
                    <th rowspan="2">Name</th>
                    <th colspan="3">Global Rank</th>
                    <th colspan="3">Local Rank</th>
                </tr>
                <tr style="border:solid 1px #000;background-color:green;color:white;">
                    <th>{{date('Y-m-d', strtotime(date('Y-m-d')))}}</th>
                    <th>{{date('Y-m-d', strtotime(date('Y-m-d')."-1 days"))}}</th>
                    <th>Growth</th>
                    <th>{{date('Y-m-d', strtotime(date('Y-m-d')))}}</th>
                    <th>{{date('Y-m-d', strtotime(date('Y-m-d')."-1 days"))}}</th>
                    <th>Growth</th>
                    
                </tr>
            </thead>
            <tbody>
            @foreach ($realdata as $v)
            <tr style="border:solid 1px #000;">
                <td style="text-align: left" >{{$v->alexa_name}}</td>
                    <td style="text-align: right">{{$v->detail_data["rank_now"]}}</td>
                    <td style="text-align: right">{{$v->detail_data["rank_yesterday"]}}</td>
                    <td style="text-align: right">{!!numbercolor($v->detail_data["rank"])!!}</td>
                    <td style="text-align: right">{{$v->detail_data["local_now"]}}</td>
                    <td style="text-align: right">{{$v->detail_data["local_yesterday"]}}</td>
                    <td style="text-align: right">{!!numbercolor($v->detail_data["local_rank"])!!}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @endforeach
        @endif
        <br><br>
        Thanks.
     
    </body>
</html>