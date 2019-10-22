<!DOCTYPE html>
<html>
@include('layouts.header')
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> Profile
          </a>
		  <a href="#" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Log Out
          </a>
        </div>
      </li>
    </ul>
  </nav>

  @include('layouts.sidebar')
  
  <div class="content-wrapper">
    <div class="content-header">
      @yield('content-header')
    </div>

    <section class="content">
    @yield('content')
    </section>
	
  </div>

 @include('layouts.footer')
  
</body>
</html>
