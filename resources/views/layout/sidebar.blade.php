@if (request()->cookie('theme')=="dark")
<nav class="pcoded-navbar navbar-dark brand-dark">
@else
<nav class="pcoded-navbar navbar-light brand-light">
@endif
        <div class="navbar-wrapper">
            <div class="navbar-brand header-logo">
                <a href="index.html" class="b-brand">
                    <div class="b-bg">
                        <i class="feather icon-trending-up"></i>
                    </div>
                    <span class="b-title">SosmedTool|KLY</span>
                </a>
                <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
            </div>
            <div class="navbar-content scroll-div">
                <ul class="nav pcoded-inner-navbar">
                    <li class="nav-item pcoded-menu-caption">
                        <label>Navigation</label>
                    </li>
                    <li class="nav-item {{ Request::url()== url('/dashboard') ? 'active' : '' }} "> 
                        <a href="{{config('app.url')}}/dashboard" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                    </li>
                    <li  class="nav-item pcoded-hasmenu {{ Request::segment(1)=="detail_fbpage"  ? 'active pcoded-trigger' : ''  || Request::segment(1)=="fbpage"  ? 'active pcoded-trigger' : '' }} ">
                        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-layers"></i></span><span class="pcoded-mtext">Masters</span></a>
                        <ul class="pcoded-submenu">
                            <li class="{{ Request::url() == url('/fbpage') || Request::segment(1)=="detail_fbpage" ? 'active' : '' }}"><a href="{{config('app.url')}}/fbpage"><i class="fa fa-circle-o"></i>Fanspage</a></li>
                        </ul>
                    </li>
                    <li  class="nav-item pcoded-hasmenu {{ Request::segment(1)=="socmed" || Request::segment(1)=="alexa" ? 'active pcoded-trigger' : '' || Request::segment(1)=="post"  ? 'active pcoded-trigger' : '' }} ">
                        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-layers"></i></span><span class="pcoded-mtext">Reports</span></a>
                        <ul class="pcoded-submenu">
                            <li class="{{ Request::url() == url('/socmed') || Request::segment(1)=="socmed"  ? 'active' : '' }}"><a href="{{config('app.url')}}/socmed" class="">Sosmed</a></li>
                            <li class="{{ Request::url() == url('/alexa') || Request::segment(1)=="alexa"  ? 'active' : '' }}"><a href="{{config('app.url')}}/alexa" class="">Alexa</a></li>
                            <li class="{{ Request::url() == url('/post') ? 'active' : '' || Request::segment(1)=="post"  ? 'active' : ''  }}"><a href="{{config('app.url')}}/post"><i class="fa fa-circle-o"></i>Posts</a></li>
                        </ul>
                    </li>
                    @if (auth()->user()["user_role"]=="admin")
                    <li  class="nav-item pcoded-hasmenu {{Request::segment(1)=="user_allow" || Request::segment(1)=="group"  ? 'active pcoded-trigger' : '' || Request::segment(1)=="cron_email"  ? 'active pcoded-trigger' : '' }} ">
                        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-settings"></i></span><span class="pcoded-mtext">Configurations</span></a>
                        <ul class="pcoded-submenu">
                            <li class="{{ Request::url() == url('/group') ? 'active' : '' }}"><a href="{{config('app.url')}}/group" class="">Group</a></li>
                            <li class="{{ Request::url() == url('/cron_email') ? 'active' : '' }}"><a href="{{config('app.url')}}/cron_email" class="">Email Report</a></li>
                            <li class="{{ Request::url() == url('/user_allow') ? 'active' : '' }}"><a href="{{config('app.url')}}/user_allow" class="">Allowed Users</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>