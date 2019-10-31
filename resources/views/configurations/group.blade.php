@extends('layout.main')
@section('title','GROUP MANAGER')
@section('content-header')
<div class="row align-items-center">
        <div class="col-md-12">
            <div class="page-header-title">
                <h3 class="m-b-10">GROUP MANAGER</h3>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="feather icon-settings"></i></a></li>
                <li class="breadcrumb-item"><a href="#!">Group List</a></li>
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
                                    <input type="text" id="search-name" class="form-control form-control-sm" placeholder="Group Name" name="name" value="">
                                </div>
                        
                                <div class="form-group col-md-2">
                                        <label for="k" class="form-label">&nbsp;Status&nbsp;&nbsp;</label>
                                    <select name="status" id="status" class="form-control form-control-sm">
                                        <option value="">All</option>
                                            <option value="1">ON</option>
                                            <option value="0">OFF</option>
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
      <!-- /.col -->

    {{-- ROW --}}
    <div class="row" >
            <div id="tabledata" class="col-md-12">
              <div class="card card-danger">
                <div class="card-block table-responsive">
                    <table id="socmed_data" class="table table-hover">
                       <thead> 
                        <tr>
                      <th style="cursor:pointer" onclick="sorting('group_order','i_group_order')" style="width: 100px">Order <i id="i_group_order" class=" sortstatus fa fa-sort-down "></i></th>
                      <th style="cursor:pointer" onclick="sorting('group_name','i_group_name')" >Group Name <i id="i_group_name" class=" "></i></th>
                      <th>Detail</th>
                      <th>Action</th>
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
        <div class="col-md-12" id="operation" style="display:none">
                <div class="card card-primary" >
                    <div class="card-header">
                      <h3>Operation</h3>
                    </div>
                    <div class="card-body">
             <form class="form" name="formGroup" id="formGroup">
                 <input type="hidden" name="action" value="add">
                 <input type="hidden" name="id" value="">
                <div class="form-group">
                    <label for="socmed_name">Group Name</label>
                    <input type="text" name="group_name" placeholder="Group Name" id="group_name" class="form form-control">
                </div>
                <div class="form-group">
                    <label for="group_type">Group Type</label>
                    <select class="form form-control" name="group_type" id="group_type">
                        <option value="socmed">Socmed</option>
                        <option value="alexa">Alexa</option>
                    </select>
                </div>

                <div class="form-group">
                        <label for="socmed_status">Group Status</label>
                        <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="1" id="group_status_on" name="group_status" class="custom-control-input">
                               <label class="custom-control-label" for="group_status_on">ON</label>
                         </div>
                         <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" value="0" id="group_status_off" name="group_status" class="custom-control-input">
                               <label class="custom-control-label" for="group_status_off">OFF</label>
                         </div>
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
        <!-- /.col (right) -->
      </div>
    {{-- END ROW --}}


@endsection

@push('script')
    <script type='text/javascript' src='{{config('app.url')}}/js/group.js'></script>
@endpush