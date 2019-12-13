@if (request()->cookie('theme')=="dark")
<header class="navbar pcoded-header navbar-expand-lg navbar-light header-dark">
@else
<header class="navbar pcoded-header navbar-expand-lg navbar-light header-default">
@endif
        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse1" href="#!"><span></span></a>
            <a href="index.html" class="b-brand">
                   <div class="b-bg">
                       <i class="feather icon-trending-up"></i>
                   </div>
                   <span class="b-title">Datta Able</span>
               </a>
        </div>
        <a class="mobile-menu" id="mobile-header" href="#!">
            <i class="feather icon-more-horizontal"></i>
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li><a href="#!" class="full-screen" onclick="javascript:toggleFullScreen()"><i class="feather icon-maximize"></i></a></li>
                <li class="nav-item">
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li>
                        <div class="form-group">
                                <div class="switch switch-default d-inline m-r-10">
                                    <input type="checkbox" id="switch-p-1" checked>
                                    <label for="switch-p-1" class="cr"></label>
                                </div>
                                <!-- <label>Click Me</label> -->
                            </div>
                </li>
                <li>
                    <div class="dropdown drp-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon feather icon-settings"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-notification">
                            <div class="pro-head">
                                <img src="{{auth()->user()["user_photo"]}}" class="img-radius" alt="User-Profile-Image">
                                <span>{{substr(auth()->user()["user_realname"],0,15)}}</span>
                                <a href="{{route('logout')}}" class="dud-logout" title="Logout">
                                    <i class="feather icon-log-out"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </header>
    <!-- [ Header ] end -->

    <!-- [ chat user list ] start -->
    <section class="header-user-list">
        <div class="h-list-header">
            <div class="input-group">
                <input type="text" id="search-friends" class="form-control" placeholder="Search Friend . . .">
            </div>
        </div>
        <div class="h-list-body">
            <a href="#!" class="h-close-text"><i class="feather icon-chevrons-right"></i></a>
            <div class="main-friend-cont scroll-div">
                <div class="main-friend-list">
                   
                </div>
            </div>
        </div>
    </section>
    <!-- [ chat user list ] end -->

    <!-- [ chat message ] start -->
    <section class="header-chat">
        <div class="h-list-header">
        </div>
        <div class="h-list-body">
            <div class="main-chat-cont scroll-div">
               
            </div>
        </div>
        <div class="h-list-footer">
        </div>
    </section>