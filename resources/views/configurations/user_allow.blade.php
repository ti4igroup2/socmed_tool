@extends('layout.main')
@section('title','ALLOWED USER MANAGER')
@section('content-header')
<div class="row align-items-center">
        <div class="col-md-12">
            <div class="page-header-title">
                <h3 class="m-b-10">Allowed User Manager</h3>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="feather icon-settings"></i></a></li>
                <li class="breadcrumb-item"><a href="#!">Allowed User</a></li>
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
                                <input type="text" id="search-name" class="form-control form-control-sm" placeholder="Name, Type" name="name" value="">
                            </div>
                            <div class="form-group col-md-12 mt-3">
                                    <button type="button" id="reload" title="" data-placement="right" data-toggle="tooltip" data-original-title="Show All" class="btn text-dark btn-light "><i class="fa fa-list-alt"></i> Show All</button>
                                    <span style="float:right;">
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
            <div id="tabledata" class="col-md-8">
              <div class="card card-danger">
                <div class="card-block table-responsive">
                    <table id="alexa_data" class="table table-hover">
                       <thead> 
                        <tr >
                            <th style="width: 10px">No</th>
                            <th>Email Address</th>
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
        <div class="col-md-4" id="operation" >
                <div class="card card-primary" >
                    <div class="card-header">
                      <h3>Operation</h3>
                    </div>
                    <div class="card-body">
             <form class="form" name="formEmail" id="formEmail">
                 <input type="hidden" name="action" value="add">
                 <input type="hidden" name="id" value="">
                <div class="form-group">
                    <label for="socmed_name">User Email</label>
                    <input type="text" name="user_email" placeholder="Email" id="user_email" class="form form-control">
                </div>
                <div class="form-group">
                    <label for="user_role">User Role</label>
                    <select class="form form-control" name="user_role" id="user_role">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="card-header">
                    
                               
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
    <script type='text/javascript' src='{{config('app.url')}}/js/allowed_user.js'></script>
@endpush