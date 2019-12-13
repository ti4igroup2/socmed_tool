@extends('layout.main')
@section('title','ALEXA MANAGER')
@section('content-header')
<div class="row align-items-center">
        <div class="col-md-12">
            <div class="page-header-title">
                <h3 class="m-b-10">ALEXA MANAGER</h3>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="feather icon-layers"></i></a></li>
                <li class="breadcrumb-item"><a href="#!">Alexa List</a></li>
            </ul>
        </div>
    </div>
@endsection


@section('content')
<div class="col-md-12" id="filterForm">
    <div class="card card-default">
            <div class="card-block table-responsive">
                <div class="form-row">
                            <div class="form-group col-md-4">
                            <label for="k" class="form-label">&nbsp;Find&nbsp;&nbsp;</label>
                                <input type="text" id="search-name" class="form-control form-control-sm" placeholder="Name" name="name" value="">
                            </div>
                            <div class="form-group col-md-4">
                            <label for="k" class="form-label">&nbsp;Group&nbsp;&nbsp;</label>
                            
                                    <select name="fgroupid" id="fgroupid" class="form-control form-control-sm">
                                        
                                    </select>
                            </div>
                            
        
                            <div class="form-group col-md-12 mt-3">
                                    <button type="button" id="reload" title="" data-placement="right" data-toggle="tooltip" data-original-title="Show All" class="btn text-dark btn-light "><i class="fa fa-list-alt"></i> Show All</button>
                                    <span style="float:right;">
                                        <button type="button" id="addnew" onclick="showOperation()" id="reload" class="btn btn-success text-dark"><i class="fa fa-plus-square"></i> New Data</button>
                                        <button type="button" id="find" title="" data-placement="left" data-toggle="tooltip" data-original-title="Search" class="btn text-dark btn-primary btn-loader mr-1"><i class="fa fa-search"></i> Search</button>
                                    </span>
                                </div>
                    </div>
                </div>
                </div>
                
            </div>
            </div>
    </div>
</div>
  

    {{-- ROW --}}
    <div class="row" >
        <div id="tabledata" class="col-md-12">
          <div class="card card-danger">
            <div class="card-block table-responsive">
                <table id="alexa_data" class="table table-hover">
                   <thead> 
                    <tr>
                      <th style="cursor:pointer" onclick="sorting('id','i_id')" class="text-center" rowspan="2" style="width: 50px">No <i id="i_id" class="sortstatus fa fa-sort-down"></i></th>
                      <th style="cursor:pointer" onclick="sorting('alexa_name','i_alexa_name')" class="text-center" rowspan="2">Name <i id='i_alexa_name' class=""></i></th>
                      <th class="text-center" rowspan="2">Informations</th>
                      <th class="text-center" colspan="2">Rank</th>
                      <th class="text-center" rowspan="2">Action</th>
                    </tr>
                    <tr>
                        <th style="cursor:pointer" onclick="sorting('alexa_rank','i_alexa_rank')" class="text-center">Global <i id="i_alexa_rank" class=" "></i></th>
                        <th style="cursor:pointer" onclick="sorting('alexa_local_rank','i_alexa_local_rank')" class="text-center">Local <i id="i_alexa_local_rank" class=" "></i></th>
                    </tr>
                   </thead>
                   {{-- <p id="tableLoading" style="display:none" class="loading_run"></p> --}}
                   <tbody id="tableBody">
                    
                   </tbody>
                  </table>
                  <div id="pager mt-1">
                        <ul id="pagination" class="pagination-sm" style="margin-left:15px !important;"></ul>
                </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (left) -->

        <div class="col-md-12" id="operation" style="display:none" >
            <div class="card card-primary" >
                <div class="card-header">
                  <h3>Operation</h3>
                </div>
                <div class="card-body">
             <form class="form" name="alexafrm" id="alexafrm">
                 <input type="hidden" name="action" value="add">
                 <input type="hidden" name="id" value="">
                <div class="form-group">
                    <label for="socmed_name">Alexa Name</label>
                    <input type="text" placeholder="Alexa Name" name="alexa_name" id="alexa_name" class="form form-control">
                </div>

                <div class="form-group">
                    <label for="socmed_url">Alexa URL</label>
                    <input type="text" placeholder="domain.com" name="alexa_url" id="alexa_url" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="socmed_group">Alexa Group</label>
                    <select class="form-control" name="alexa_groupid" id="alexa_groupid">
                           
                    </select>
                </div>

                <div class="card-header">
                        <div><button type="button" onclick="hideOperation()" class="btn btn-danger text-white"><i class="fas fa-arrow-alt-circle-left"></i> Cancel</button></div>
                               
                                   <div class="card-header-right">
                                           <button type="button" class="btn btn-warning text-dark" onclick="this.form.reset();"><i class='fas fa-redo-alt'></i>Reset</button>
                                           <button type="submit" class="btn btn-success text-dark"><i class="fas fa-save"></i>Save</button>
                                   </div>
                        </div>
             </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          </div>
          <!-- /.box -->
    
      </div>
      <div class="row">


        <div class="col-lg-12">
                <!-- LINE CHART -->
                <div class="card card-info">
                  <div class="card-header with-border">
                    <h3 class="card-title">Local Growth Chart</h3>
                  </div>
                  <div class="card-body">
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
                    <div class="card-block">
                        <div id="Statistics-line2" class="ChartShadow" style="height:450px;width=100px;"></div>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
        </div>
</div>
    {{-- END ROW --}}


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

<script src="{{config('app.url')}}/js/lodash.js"></script>
<script src="{{config('app.url')}}/assets/assets/plugins/waterball/js/createWaterBall-jquery.js"></script>
    <script type='text/javascript' src='{{config('app.url')}}/js/alexa.js'></script>
@endpush