@extends('layout.main')
@section('title','DASHBOARD')
@section('content-header')
<div class="row align-items-center">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i> Dashboard</a></li>
            </ul>
        </div>
    </div>
@endsection


@section('content')


    {{-- ROW --}}
    <div class="row" >
        <div id="tabledata" class="col-md-12">
            <div class="card card-danger">
              <div class="card-block table-responsive">
                  <table class="table table-hove" width="100%">
                    <tr>
                        <td class="col-md-9"><h5><i class="fab fa-facebook"></i> FANSPAGE POPULAR POST</h5></td>
                        <td  class="col-md-3"><h5><i class="fas fa-user-edit"></i> FANSPAGE POST CREATOR </h5></td>
                    </tr>
                    <tr>
                        <td class="col-md-9" id="mostPopular"></td>
                        <td class="col-md-3" id="mostCreator"></td>
                    </tr>
                  </table>
              </div>
              <!-- /.box-body -->
            </div>
        </div>
                    
                    <div class="col-sm-4 m-b-30">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                             <li class="nav-item">
                                 <a class="nav-link active show" ><i class="fab fa-facebook"></i> FACEBOOK LIKES</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                         <div id="facebookrank" class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                            
                            </div>
                            </div>
                     </div>
                     <div class="col-sm-4 m-b-30">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                             <li class="nav-item">
                                 <a class="nav-link active show" ><i class="fab fa-twitter"></i> TWITTER FOLLOWER</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                         <div id="twitterrank" class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                            
                            </div>
                            </div>
                     </div>
                     <div class="col-sm-4 m-b-30">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                             <li class="nav-item">
                                 <a class="nav-link active show" ><i class="fab fa-instagram"></i> INSTAGRAM FOLLOWER</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                         <div id="instagramrank" class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                            
                            </div>
                            </div>
                     </div>
                     <div class="col-sm-4 m-b-30">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                             <li class="nav-item">
                                 <a class="nav-link active show" ><i class="fab fa-youtube"></i> YOUTUBE SUBSCRIBER</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                         <div id="youtuberank" class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                            
                            </div>
                            </div>
                     </div>
                     <div class="col-sm-8 m-b-30">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                             <li class="nav-item">
                                 <a class="nav-link active show" ><i class="fas fa-link"></i> ALEXA DOMAIN RANK</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                         <div id="alexarank" class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                            
                        </div>
    
                            </div>
                     </div>
                        



          <!-- /.box -->

        </div>
        <!-- /.col (left) -->
    {{-- END ROW --}}


@endsection

@push('script')
    <script type='text/javascript' src='{{config('app.url')}}/js/dashboard.js'></script>
@endpush