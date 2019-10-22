@extends('layout.main')
@section('title',"Alexa Growth Report")
@section('content-header')

        <div class="col-md-12">
            <div class="page-header-title">
                <h3 class="m-b-10">ALEXA GROWTH REPORT</h3>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                <li class="breadcrumb-item"><a href="/alexa">Alexa List</a></li>
                <li class="breadcrumb-item active"><a href="#!">Alexa Growth Report</a></li>
            </ul>
        </div>

@endsection
@section('content')
  <div class="row">
      <input type="hidden" id="param_id" value="{{request()->route('id')}}">
      <div class="col-lg-4 col-xs-4">
        <div class="callout callout-default">
            <h4>{{$alexa["alexa_name"]}} ({{$alexa["groupMaster"]->group_name}})</h4>
            <p>{{$alexa["alexa_url"]}} <a target="_blank" class="text-black" href="{{$alexa["alexa_url"]}}"><i class="fa fa-external-link"></i></a></p>
          </div>
      </div>
      <div class="col-lg-4 col-xs-4">
            <div class="card theme-bg2">
                <div class="card-block">
                    <div class="row d-flex align-items-center">
                        <div class="col-auto">
                            <i class="feather icon-bar-chart-2 f-40 text-white"></i>
                        </div>
                        <div class="col">
                            <h2 class="f-w-300 text-white">{{rupiah($alexa["lastCounts"]->alexa_rank)}} | {{$summary["rank"]}}({{$summary["rank_percent"]}})<sup style="font-size: 20px">%</sup></h2>
                            <span class="d-block text-white">Today Rank Global</span>
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
                                <h2 class="f-w-300 text-white">{{rupiah($alexa["lastCounts"]->alexa_local_rank)}} | {{$summary["local_rank"]}}({{$summary["local_rank_percent"]}})<sup style="font-size: 20px">%</sup></h2>
                                <span class="d-block text-white">Today Rank Local</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
  

  </div>    

<hr>


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
                        <h3 class="card-title">Global Growth Chart</h3>
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
    <div class="row">
            <div class="col-lg-12">
                    <!-- LINE CHART -->
                    <div class="card card-info">
                      <div class="card-header with-border">
                        <h3 class="card-title">Local Growth Chart</h3>
                      </div>
                      <div class="card-body">
                        <div class="card-block">
                            <div id="Statistics-line2" class="ChartShadow" style="height:450px;width=100px;"></div>
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
    <script type='text/javascript' src='{{config('app.url')}}/js/reportAlexa.js'></script>
@endpush