@extends('layout.main')
@section('title',"FansPage Manager")
@section('content-header')
<div class="row align-items-center">
    <div class="col-md-12">
        <div class="page-header-title">
            <h3 class="m-b-10">FANSPAGE MANAGER</h3>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="feather icon-layers"></i></a></li>
            <li class="breadcrumb-item"><a href="#!">FansPage List</a></li>
        </ul>
    </div>
</div>
@endsection
@section('content')
<div class="col-md-12" id="filterForm">
    <div class="card card-default">
            <div class="card-block table-responsive">
                <div class="form-row">
                            <div class="form-group col-md-8">
                            <label for="k" class="form-label">&nbsp;Find&nbsp;&nbsp;</label>
                                <input type="text" id="search-name" class="form-control form-control-sm" placeholder="FansPage Name" name="name" value="">
                            </div>

                            <div class="form-group col-md-12 mt-3">
                                    <button type="button" id="reload" title="" data-placement="right" data-toggle="tooltip" data-original-title="Show All" class="btn text-dark btn-light "><i class="fa fa-list-alt"></i> Show All</button>
                                    
                                    <span style="float:right;">
                                        @if (auth()->user()["user_role"]=="admin")
                                        <a href="login/facebook" class="btn btn-success text-dark"><i class="fa fa-plus-square"></i> Re-List Owned Fanspage</a>
                                        @endif
                                        <button type="button" id="find" title="" data-placement="left" data-toggle="tooltip" data-original-title="Search" class="btn text-dark btn-info btn-loader mr-1"><i class="fa fa-search"></i> Search</button>
                                    </span>
                                </div>
                    </div>
                </div>
                </div>
                
            </div>
            </div>
    </div>
</div>



<div class="row" >
    <div id="tabledata" class="col-md-12">
      <div class="card card-danger">
        <div class="card-block table-responsive">
            <table id="socmed_data" class="table table-hover">
               <thead> 
                <tr>
                    <th width="10%">No</th>
                    <th>Fanspage ID</th>
                    <th>Fanspage Name </th>
                    <th>Fanspage Status</th>
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
    <script type='text/javascript' src='{{config('app.url')}}/js/fanspage.js'></script>
@endpush