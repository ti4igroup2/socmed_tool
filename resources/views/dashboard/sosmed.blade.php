@extends('layout.main')
@section('title','SOSMED MANAGER')
@section('content-header')
<div class="row align-items-center">
        <div class="col-md-12">
            <div class="page-header-title">
                <h3 class="m-b-10">SOSMED MANAGER</h3>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="feather icon-layers"></i></a></li>
                <li class="breadcrumb-item"><a href="#!">Sosmed List</a></li>
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
                                            <input type="text" id="search-name" class="form-control form-control-sm" placeholder="Name, Type" name="name" value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                        <label for="k" class="form-label">&nbsp;Group&nbsp;&nbsp;</label>
                                        
                                                <select name="fgroupid" id="fgroupid" class="form-control form-control-sm">
                                                    
                                                </select>
                                        </div>
                    
                                        <div class="form-group col-md-2">
                                                <label for="k" class="form-label">&nbsp;Status&nbsp;&nbsp;</label>
                                            <select name="status" id="status" class="form-control form-control-sm">
                                                <option value="">All</option>
                                                    <option value="1">ON</option>
                                                    <option value="0">OFF</option>
                                                </select>
                                        </div>
                                        <div class="form-group  mt-3">
                                                <button type="button" id="find" title="" data-placement="left" data-toggle="tooltip" data-original-title="Search" class="btn text-dark btn-info btn-loader mr-1"><i class="fa fa-search"></i> Search</button>
                                                <button type="button" id="reload" title="" data-placement="right" data-toggle="tooltip" data-original-title="Show All" class="btn text-dark btn-primary "><i class="fa fa-list-alt"></i> Show All</button>
                                                <button type="button" id="addnew" onclick="showOperation()" id="reload" class="btn btn-success text-dark"><i class="fa fa-plus-square"></i> New Data</button>
                                               
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
                      <th style="cursor:pointer" onclick="sorting('id','i_no')" style="width: 50px">No <i id="i_no" class="sortstatus fa fa-sort-down"></i></th>
                      <th style="cursor:pointer" onclick="sorting('socmed_name','i_socmed_name')">Name <i id="i_socmed_name" class=""></i></th>
                      <th>Informations</th>
                      <th style="cursor:pointer" onclick="sorting('socmed_total','i_socmed_total')">Last Counts <i id="i_socmed_total" class=""></i></th>
                      <th style="width: 40px">Status</th>
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
            <!-- /.card-body -->
          </div>
          <!-- /.card -->

        </div>
        <!-- /.col (left) -->


        <div class="col-md-12" id="operation" style="display:none">
        <div class="card card-primary" >
            <div class="card-header">
              <h3>Operation</h3>
            </div>
            <div class="card-body">
             <form class="form" name="socmedfrm" id="socemdfrm">
                 <input type="hidden" name="action" value="add">
                 <input type="hidden" name="id" value="">
                <div class="form-group">
                    <label for="socmed_name">Sosmed Name</label>
                    <input type="text" placeholder="Sosmed Name" name="socmed_name" id="socmed_name" class="form form-control">
                </div>

                <div class="form-group">
                    <label for="socmed_type">Sosmed Type</label>
                    <select class="form-control" name="socmed_type" id="socmed_type">
                        <option value="youtube">Youtube</option>
                        <option value="instagram">Instagram</option>
                        <option value="facebook">Facebook</option>
                        <option value="twitter">Twitter</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="socmed_url">Sosmed URL</label>
                    <input type="text" placeholder="Url inlcude https" name="socmed_url" id="socmed_url" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="socmed_group">Sosmed Group</label>
                    <select class="form-control" name="socmed_groupid" id="socmed_groupid">
                           
                    </select>
                </div>

                <div class="form-group">
                    <label for="socmed_status">Sosmed Status</label>
                    <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" value="1" id="socmed_status_on" name="socmed_status" class="custom-control-input">
                           <label class="custom-control-label" for="socmed_status_on">ON</label>
                     </div>
                     <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" value="0" id="socmed_status_off" name="socmed_status" class="custom-control-input">
                           <label class="custom-control-label" for="socmed_status_off">OFF</label>
                     </div>
                </div>
                <div class="card-header">
                 <div><button type="button" onclick="hideOperation()" class="btn btn-danger text-white"><i class="fas fa-arrow-alt-circle-left"></i> Cancel</button></div>
                        
                            <div class="card-header-right">
                                    <button type="button" class="btn btn-warning text-dark" onclick="this.form.reset();"><i class='fas fa-redo-alt'></i>Reset</button>
                                    <button type="submit" class="btn btn-success text-dark"><i class="fas fa-save"></i>Save</button>
                            </div>
                 </div>
         
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    {{-- END ROW --}}


   


@endsection

@push('script')
    <script type='text/javascript' src='{{config('app.url')}}/js/socmed.js'></script>
@endpush