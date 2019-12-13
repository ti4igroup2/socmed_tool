@extends('layout.main')
@section('title','EMAIl REPORT SETTING')
@section('content-header')
<div class="row align-items-center">
        <div class="col-md-12">
            <div class="page-header-title">
                <h3 class="m-b-10">EMAIl REPORT SETTING</h3>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="feather icon-settings"></i></a></li>
                <li class="breadcrumb-item"><a href="#!">Email Report List</a></li>
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
                                <input type="text" id="search-name" class="form-control form-control-sm" placeholder="Email Subject" name="name" value="">
                            </div>

                            <div class="form-group col-md-12 mt-3">
                                  <button type="button" id="reload" title="" data-placement="right" data-toggle="tooltip" data-original-title="Show All" class="btn text-dark btn-light "><i class="fa fa-list-alt"></i> Show All</button>
                                <span style="float:right;">
                                    <button type="button" id="mailreport" data-toggle="modal" data-target="#modal-mail" class="btn btn-warning text-dark"><i class="fa fa-envelope"></i>   Send Report To</button>
                                    <button type="button" id="mailreportnow" class="btn btn-success text-dark"><i class="fa fa-envelope"></i>   Send To All Now</button>   
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
            <table id="socmed_data" class="table table-hover">
               <thead> 
                <tr>
                <th style="width: 10px">No</th>
                <th>Subject</th>
                <th>Time</th>
                <th>Recipients</th>
                <th>Status</th>
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
  <div class="col-md-12" id="operation" style="display:none" >
    <div class="card card-primary" >
        <div class="card-header">
          <h3>Operation</h3>
        </div>
        <div class="card-body">
       <form class="form" name="formCron" id="formCron">
           <input type="hidden" name="action" value="add">
           <input type="hidden" name="id" value="">
          <div class="form-group">
              <label for="email_subject">Subject</label>
              <input type="text" name="email_subject" placeholder="subject %DAY %MONTH %YEAR" id="email_subject" class="form form-control">
              <p class="text-small">
                <h6>Date Settings : <b class="text-success">%DAY</b>/ <b class="text-success">%MONTH</b>/ <b class="text-success">%YEAR</b>/ <b class="text-success">%DATE</b>
              </p>
          </div>
          <div class="form-group">
              <label for="email_cron">Mailing Time</label>
              <select class="form form-control" name="email_cron1" id="email_cron1">
                  <option value="everyday">Everyday</option>
                  <option value="1">Monday</option>
                  <option value="2">Tuesday</option>
                  <option value="3">Wednesday</option>
                  <option value="4">Thursday</option>
                  <option value="5">Friday</option>
                  <option value="6">Saturday</option>
                  <option value="7">Sunday</option>
              </select>
          </div>
          <div class="detail_cron form-group">
                <label for="email_cron2">Detail Time</label>
                <div class="row">
                    <div class="col-sm-6">
                            <div class="input-group">
                                   <input type="time" class="form-control" name="email_cron2" id="email_cron2">
                            </div>
                    </div>
                </div>
            </div>
          <div class="form-group">
            <label for="email_reciepent">Recipients</label>
            <textarea class="form form-control" cols="10" rows="10" name="email_recipient" id="email_recipient" placeholder="Email1@mail.com&#10;Email2@mail.com&#10;Email3@mail.com"></textarea>
        </div>
     
        <div class="form-group">
            <label for="email_grouplist1">Group List</label><br>
            <select class="form-control select2 " name="email_grouplist1[]" id="email_grouplist1" multiple="" tabindex="-1" aria-hidden="true">
                @foreach ($group as $d)
                   <option value="{{$d->id}}"> {{$d->group_name}} </option>
                @endforeach
            </select>
        </div>

    <div class="form-group">
        <label for="email_status">Sosmed Status</label>
        <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" value="1" id="email_status_on" name="email_status" class="custom-control-input">
               <label class="custom-control-label" for="email_status_on">ON</label>
         </div>
         <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" value="0" id="email_status_off" name="email_status" class="custom-control-input">
               <label class="custom-control-label" for="email_status_off">OFF</label>
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

   <div class="modal modal-default fade" id="modal-mail">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Recipient email</h4>
            </div>
            <div class="modal-body">
              <p>Multiple Recipients can be separated by comma(,)</p>
              <input type="text" id="emailto" name="emailto" class="form form-control" required placeholder="a@kly.id,b@kly.id">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Close</button>
              <button type="button" id="send"  class="btn btn-primary">Send Now</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
@endsection

@push('script')
    <script type='text/javascript' src='{{config('app.url')}}/js/cron_email.js'></script>
    @if (\Session::has('success'))
    <script>$.notify("Data Updated Succesfully","success");</script>
    @endif
@endpush