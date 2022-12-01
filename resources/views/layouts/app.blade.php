<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Warungku</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{  asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{  asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{  asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{  asset('dist/css/style.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    @yield('style')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">

<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">


        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
            <!-- <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Home</a>
            </li> -->
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-info elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
      <img class="logo-menu ml-2 img-circle elevation-3" src="{{ asset('dist/img/logo-bg.png') }}" alt="Warungku"

          style="opacity: .8"
           >
      <span class="brand-text font-weight-light"><b>WARUNG</b>KU</span>
    </a>

    @if(auth()->user()->warung)
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3">
        <div class="info">
          <a href="#" class="d-block">{{ auth()->user()->warung->nama }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column"
        data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

            <li class="nav-item">
                <a href="{{ route('barang.index') }}" class="nav-link {{ Route::is('barang.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-archive"></i>
                    <p>
                        Master Barang
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('pembelian.index') }}" class="nav-link {{ Route::is('pembelian.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-shopping-bag"></i>
                    <p>
                        Pembelian Barang
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('transaksi', ['type' => 'offline']) }}" class="nav-link {{ request()->routeIs('transaksi') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <p>
                        Jual Barang
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('laporan') }}" class="nav-link {{ request()->routeIs('laporan') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-receipt"></i>
                    <p>
                        Laporan Penjualan
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('stok') }}" class="nav-link {{ request()->routeIs('stok') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <p>
                        Stok Barang
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('harga_dasar', ['type' => 'active']) }}" class="nav-link {{ request()->routeIs('harga_dasar') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-money-bill-alt"></i>
                    <p>
                        Harga Dasar Barang
                    </p>
                </a>
            </li>

            <li class="nav-item has-treeview {{ Route::is('partner.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>
                        Kolaborasi
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('partner.search') }}" class="nav-link {{ request()->routeIs('partner.search') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Cari Partner</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('partner.list-confirmation') }}" class="nav-link {{ request()->routeIs('partner.list-confirmation') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Permintaan Kolaborasi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('partner.me') }}" class="nav-link {{ request()->routeIs('partner.me') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Partner Saya</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
              <a  href="{{ route('warung.index') }}" class="nav-link {{ request()->routeIs('warung.edit') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home"></i>
                <p>
                  Warungku
                </p>
              </a>
            </li>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
    @endif
  </aside>
        @yield('content')

</div>
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="{{ asset('dist/js/demo.js') }}"></script> -->
@yield('script')
</body>
</html>
