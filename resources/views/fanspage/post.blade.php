@extends('layout.main')
@section('title',"Post Manager")
@section('content-header')
<div class="row align-items-center">
    <div class="col-md-12">
        <div class="page-header-title">
            <h3 class="m-b-10">POST MANAGER</h3>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="feather icon-layers"></i></a></li>
            <li class="breadcrumb-item"><a href="#!">Post List</a></li>
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
                  <label for="k" class="form-label">&nbsp;Find&nbsp;&nbsp;&nbsp;</label>
                      <input type="text" id="search-name" class="form-control input-sm" placeholder="Content Message, Creator" name="name" value="">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="fanspage" class="form-label">&nbsp;Fanspage&nbsp;&nbsp;&nbsp;</label>
                        <select  name="fanspage" class="select2 form-control input-sm" id="fanspage">
                            
                        </select>
                    </div>    
                    <div class="form-group col-md-4">
                      <label for="fanspage" class="form-label">&nbsp;Creator&nbsp;&nbsp;&nbsp;</label>
                          <select  name="creator" class="select2 form-control input-sm" id="creator">
                              
                          </select>
                      </div>     
                  <div class="form-group col-md-4">
                  <label for="kw" class="form-label">&nbsp;Created Time&nbsp;&nbsp;&nbsp;</label>
                      <input type="text" id="filter-date" class="form-control input-sm" placeholder="Created Time" name="name" value="">
                  </div>   

                  
                
                  <div class="form-group col-md-12 mt-3">
                      <button type="button" id="reload" title="" data-placement="right" data-toggle="tooltip" data-original-title="Show All" class="btn btn-sm text-dark btn-light "><i class="fa fa-list-alt"></i> Show All</button>
                    <span style="float:right;">
                      <button type="button" id="find" class="btn btn-sm btn-primary text-dark  btn-flat"><i class="fa fa-search"></i> Search</button>
                    </span>
                  </div>
                  
              
          </div>
      </div>
    </div>
    <!-- /.col -->
  </div>
  <div class="row" >
    <div id="tabledata" class="col-lg-12">
      <div class="card card-danger">
        <div class="card-block table-responsive">
            <table id="socmed_data" class="table table-hover">
               <thead> 
                <tr>
                    <th>No </th>
                    <th>Photo </th>
                    <th>Message</th>
                    <th>Detail</th>
                    <th>Reaction</th>
                    <th>Creator</th>
                    <th style="cursor:pointer" onclick="sorting('created_time','i_created_time')">Created Time <i id="i_created_time" class="sortstatus fa fa-sort-down"></i></th>
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
       
</div>  
@endsection
@push('script')
<script src="http://momentjs.com/downloads/moment-with-locales.min.js"></script>
<script src="{{config('app.url')}}/assets/assets/plugins/material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

<!-- WaterBall Js -->
<script src="{{config('app.url')}}/assets/assets/plugins/waterball/js/createWaterBall-jquery.js"></script>
    <script type='text/javascript' src='{{config('app.url')}}/js/postall.js'></script>
@endpush