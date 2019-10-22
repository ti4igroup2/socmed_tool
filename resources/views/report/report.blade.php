@extends('layout.main')
@section('title',"Sosmed Growth Report")
@section('content-header')
<div class="row align-items-center">
        <div class="col-md-12">
            <div class="page-header-title">
                <h3 class="m-b-10">SOSMED GROWTH REPORT</h3>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                <li class="breadcrumb-item"><a href="/socmed">Sosmed List</a></li>
                <li class="breadcrumb-item active"><a href="#!">Sosmed Growth Report</a></li>
            </ul>
        </div>
@endsection
@section('content')
  <div class="row">
      <input type="hidden" id="param_id" value="{{request()->route('id')}}">
      <div class="col-lg-4 col-xs-4">
        <div class="bg-default">
            <h4>{{$sosmed["socmed_name"]}} ({{$sosmed["groupMaster"]->group_name}})</h4>
            <span><i class="fab fa-{{$sosmed["socmed_type"]}}"></i>  {{$sosmed["socmed_type"]}}  
            @if ($sosmed["socmed_status"]=="1")
                <span class="badge bg-green">Active</span>
            @else
            <span class="badge bg-red">Non Active</span>
            @endif
            </span>
            <p>{{$sosmed["socmed_url"]}} <a target="_blank" class="text-black" href="{{$sosmed["socmed_url"]}}"><i class="fas fa-external-link-alt"></i></a></p>
          </div>
      </div>
        <div class="col-lg-4 col-xs-4">
            <div class="card theme-bg2">
                <div class="card-block">
                    <div class="row d-flex align-items-center">
                        <div class="col-auto">
                            <i class="feather icon-thumbs-up f-40 text-white"></i>
                        </div>
                        <div class="col">
                            <h2 class="f-w-300 text-white">{{rupiah($sosmed["lastCounts"]->socmed_total)}}</h2>
                            <span class="d-block text-white">Followers Today</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xs-4">
                <div class="card theme-bg">
                    <div class="card-block">
                        <div class="row d-flex align-items-center">
                            <div class="col-auto">
                                <i class="feather icon-bar-chart-2 f-40 text-white"></i>
                            </div>
                            <div class="col">
                                <h2 class="f-w-300 text-white">{{$summary["count"]}}({{$summary["percent"]}})<sup style="font-size: 20px">%</sup></h2>
                                <span class="d-block text-white">Today Growth</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
  </div>
<hr>
{{-- END DETAIL --}}


{{-- FILTER --}}
<div class="row">
    <div class="col-md-8">
        <div class="small-card bg-default text-black">
                <div class="card-block">
                    <div id="form-search"  method="post" class="form-inline">
                        <label for="filter" class="form-label">Filter&nbsp;&nbsp;</label>
                        <div class="form-group">
                            <select name="filter" id="filter" class="form-control">
                                    <option value="week">Last 7 Days</option>
                                    <option value="month">This Month</option>
                                    <option value="range">Set Range</option>
                                </select>
                        </div>
                        <div class="form-group" style="display:none" id="date_range">
                                <div class="input-group mb3">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">From</span>
                                            </div>
                                        <input type="text" class="form-control" aria-describedby="basic-addon1" id="start_range">
                                    </div>
                                <div class="input-group mb3">
                                        <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon2">To</span>
                                            </div>
                                  <input type="text" class="form-control" aria-describedby="basic-addon2" id="end_range">
                                 </div>    
                                <!-- /.input group -->
                              </div>
    
                </div>
            </div>
          </div>
          <!-- /.col -->
    </div>
</div>
{{-- END FILTER --}}
<br>
{{-- GRAFIK --}}
<div class="row">
        <div class="col-lg-12">
                <!-- LINE CHART -->
                <div class="card card-info">
                  <div class="card-header with-border">
                    <h3 class="card-title">Growth Chart</h3>
                  </div>
                  <div class="card-body">
                    <div class="card-block">
                        <div id="Statistics-line" class="ChartShadow" style="height:450px;width=100px;"></div>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
        </div>
</div>
</div>
{{-- END GRAFIK --}}
@endsection

@push('script')
<!-- Am chart4 Js -->
<script src="{{config('app.url')}}/assets/assets/plugins/amchart/js/amcharts.js"></script>
<script src="{{config('app.url')}}/assets/assets/plugins/amchart/js/gauge.js"></script>
<script src="{{config('app.url')}}/assets/assets/plugins/amchart/js/serial.js"></script>
<script src="{{config('app.url')}}/assets/assets/plugins/amchart/js/light.js"></script>
<script src="{{config('app.url')}}/assets/assets/plugins/amchart/js/pie.min.js"></script>
<script src="{{config('app.url')}}/assets/assets/plugins/amchart/js/ammap.min.js"></script>
<script src="{{config('app.url')}}/assets/assets/plugins/amchart/js/usaLow.js"></script>
<script src="{{config('app.url')}}/assets/assets/plugins/amchart/js/radar.js"></script>
<script src="{{config('app.url')}}/assets/assets/plugins/amchart/js/worldLow.js"></script>
<script src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script src="{{config('app.url')}}/assets/assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

<!-- WaterBall Js -->
<script src="{{config('app.url')}}/assets/assets/plugins/waterball/js/createWaterBall-jquery.js"></script>
    <script type='text/javascript' src='{{config('app.url')}}/js/report.js'></script>
@endpush