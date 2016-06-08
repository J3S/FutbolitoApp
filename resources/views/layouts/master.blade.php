<!DOCTYPE html>
<html>
    <head>
        @include('layouts.head')
        <style media="screen">
            html, body{height: 100% !important;}
            .content-wrapper{
                height: 100%;
            }
            .wrapper{
                /*height: 100%;*/
            }
        </style>
    </head>
    <body class="hold-transition skin-green sidebar-mini">
        <div class="wrapper">

            @include('layouts.header')

            @include('layouts.sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="row">
                    <div class="col-xs-12">
                        <ol class="breadcrumb">
                            @yield('contentHeaderBreadcrumb')
                        </ol>
                    </div>
                </div><!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    @yield('content')
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->

            <footer class="main-footer col-xs-12">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.3.0
                </div>
                <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights reserved.
            </footer>

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Home tab content -->
                    <div class="tab-pane" id="control-sidebar-home-tab">

                    </div><!-- /.tab-pane -->

                </div>
            </aside><!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
               immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div><!-- ./wrapper -->

        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <!-- FastClick -->
        <script src="../../plugins/fastclick/fastclick.min.js"></script>
        <!-- AdminLTE App -->
        <script type="text/javascript" src="{!! asset('js/app.min.js') !!}"></script>
        <!-- AdminLTE for demo purposes -->
        <script type="text/javascript" src="{!! asset('js/demo.js') !!}"></script>
        @yield('scriptsPersonales')
    </body>
</html>
