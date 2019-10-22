<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="{{auth()->user()["user_photo"]}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p>{{substr(auth()->user()["user_realname"],0,15)}}</p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online as {{auth()->user()["user_role"]}}</a>
            </div>
          </div>
          <!-- search form -->
          <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
              <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                  </span>
            </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview {{ Request::segment(1)=="socmed" || Request::segment(1)=="alexa" ? 'active' : '' }}">
              <a href="#">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu" >
                <li class="{{ Request::url() == url('/socmed') || Request::segment(1)=="socmed"  ? 'active' : '' }}"><a href="{{config('app.url')}}/socmed/"><i class="fa fa-circle-o"></i> Sosmed</a></li>
                <li class="{{ Request::url() == url('/alexa') || Request::segment(1)=="alexa"  ? 'active' : '' }}"><a href="{{config('app.url')}}/alexa/"><i class="fa fa-circle-o"></i> Alexa</a></li>
            </ul>
            </li>
            @if (auth()->user()["user_role"]=="admin")
          <li class="treeview {{Request::segment(1)=="user_allow" || Request::segment(1)=="group"  ? 'active' : '' || Request::segment(1)=="cron_email"  ? 'active' : '' || Request::segment(1)=="fbpage"  ? 'active' : ''}}">
                <a href="#">
                  <i class="fa fa-user"></i> <span>Configurations</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li class="{{ Request::url() == url('/group') ? 'active' : '' }}"><a href="{{config('app.url')}}/group"><i class="fa fa-circle-o"></i> Group</a></li>
                  <li class="{{ Request::url() == url('/fanspage') ? 'active' : '' }}"><a href="{{config('app.url')}}/fbpage"><i class="fa fa-circle-o"></i> Fanspage</a></li>
                  <li class="{{ Request::url() == url('/cron_email') ? 'active' : '' }}"><a href="{{config('app.url')}}/cron_email"><i class="fa fa-circle-o"></i> Email Report</a></li>
                  <li class="{{ Request::url() == url('/user_allow') ? 'active' : '' }}"><a href="{{config('app.url')}}/user_allow"><i class="fa fa-circle-o"></i> Allowed Users</a></li>
                </ul>
             </li>
            @endif
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>