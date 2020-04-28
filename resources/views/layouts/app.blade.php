<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 3 | Dashboard</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <script src="{{ mix('/js/app.js') }}" defer></script>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse">
<div id="app">
    <div class="wrapper">
        @guest
            <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            </nav>
        @else
        <!-- Navbar -->
            <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                    </li>
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">

                    <li class="nav-item">
                        <a id="navbarDropdown" class="nav-link" href="#" role="button" data-toggle="dropdown">
                            {{ Auth::user()->name }} |
                        </a>
                    </li>
                    <!-- Notifications Dropdown Menu -->
                    <li class="nav-item">
                        <a class="nav-link text-danger"
                           data-slide="true"
                           href="{{ route('logout') }}"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            Salir&nbsp;<i class="fa fa-sign-out"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->
        @endguest

        @guest
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <aside class="main-sidebar sidebar-dark-primary elevation-4">
                    <a href="{{route('dashboard')}}" class="brand-link">
                        <img src="{{asset('img/AdminLTELogo.png')}}" alt="AdminLTE Logo"
                             class="brand-image img-circle elevation-3"
                             style="opacity: .8">
                        <span class="brand-text font-weight-light">Aplicacion</span>
                    </a>
                </aside>
            </aside>
    @else
        <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="{{route('dashboard')}}" class="brand-link">
                    <img src="{{asset('img/AdminLTELogo.png')}}" alt="AdminLTE Logo"
                         class="brand-image img-circle elevation-3"
                         style="opacity: .8">
                    <span class="brand-text font-weight-light">Aplicacion</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                            data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class
                   with font-awesome or any other icon font library -->
                            <li class="nav-item">
                                <a href="pages/widgets.html" class="nav-link">
                                    <i class="nav-icon fa fa-th"></i>
                                    <p>Indicadores</p>
                                </a>
                            </li>
                            @if(auth()->user()->can(\App\Constants\PermissionsConstants::THIRD_LIST))
                                <li class="nav-item">
                                    <a href="{{route('thirds.index')}}" class="nav-link">
                                        <i class="nav-icon fa fa-male"></i>
                                        <p>Terceros</p>
                                    </a>
                                </li>
                            @endif


                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-sitemap"></i>
                                    <p>Inventario<i class="right fa fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="pages/charts/chartjs.html" class="nav-link">
                                            <i class="fa fa-circle-o nav-icon"></i>
                                            <p>Productos</p>
                                        </a>
                                    </li>
                                    @if(auth()->user()->can(\App\Constants\PermissionsConstants::INVENTORY_CATEGORY_LIST))
                                        <li class="nav-item">
                                            <a href="{{route('inventory.category.index')}}" class="nav-link">
                                                <i class="fa fa-circle-o nav-icon"></i>
                                                <p>Categorias</p>
                                            </a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->can(\App\Constants\PermissionsConstants::WAREHOUSE_LIST))
                                        <li class="nav-item">
                                            <a href="{{route('warehouses.index')}}" class="nav-link">
                                                <i class="fa fa-circle-o nav-icon"></i>
                                                <p>Bodegas</p>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>

                            <li class="nav-item has-treeview">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fa fa-pie-chart"></i>
                                    <p>Transacciones<i class="right fa fa-angle-left"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="pages/charts/chartjs.html" class="nav-link">
                                            <i class="fa fa-circle-o nav-icon"></i>
                                            <p>Compras/ gastos</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="pages/charts/flot.html" class="nav-link">
                                            <i class="fa fa-circle-o nav-icon"></i>
                                            <p>Inventario</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="pages/charts/flot.html" class="nav-link">
                                            <i class="fa fa-circle-o nav-icon"></i>
                                            <p>Bancos/ Cajas</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="pages/charts/flot.html" class="nav-link">
                                            <i class="fa fa-circle-o nav-icon"></i>
                                            <p>Cuanto me deben</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="pages/charts/flot.html" class="nav-link">
                                            <i class="fa fa-circle-o nav-icon"></i>
                                            <p>Cuanto estoy debiendo</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fa fa-edit"></i>
                                    <p>Nómina</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fa fa-table"></i>
                                    <p>Reportes</p>
                                </a>
                            </li>
                            @if(auth()->user()->can(\App\Constants\PermissionsConstants::USER_LIST) || auth()->user()->can(\App\Constants\PermissionsConstants::ROLE_LIST))
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fa fa-plus-square-o"></i>
                                        <p>Configuracion<i class="right fa fa-angle-left"></i></p>
                                    </a>
                                    @if(auth()->user()->can(\App\Constants\PermissionsConstants::USER_LIST))
                                        <ul class="nav nav-treeview">
                                            <li class="nav-item">
                                                <a href="{{route('users.index')}}" class="nav-link">
                                                    <i class="fa fa-circle-o nav-icon"></i>
                                                    <p>Usuarios</p>
                                                </a>
                                            </li>
                                        </ul>
                                    @endif
                                    @if(auth()->user()->can(\App\Constants\PermissionsConstants::ROLE_LIST))
                                        <ul class="nav nav-treeview">
                                            <li class="nav-item">
                                                <a href="{{route('roles.index')}}" class="nav-link">
                                                    <i class="fa fa-circle-o nav-icon"></i>
                                                    <p>Roles</p>
                                                </a>
                                            </li>
                                        </ul>
                                    @endif
                                </li>
                            @endif
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
            </aside>
    @endguest
    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Main content -->
            <section class="content mt-3">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> @yield('title')
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @yield('content')
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

            </section>
        </div><!-- /.container-fluid -->
        <!-- /.content -->
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2020 Juan Camilo Rosillo Martinez.</strong>
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>

<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)

</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.js') }}"></script>
@yield('scripts')

</body>
</html>
