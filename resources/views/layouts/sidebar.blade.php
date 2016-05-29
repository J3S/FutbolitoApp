<!-- Left side column contains sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="treeview 
                @if(Request::is('jugador/crear') OR Request::is('jugador/editar') OR Request::is('jugador/desactivar'))
                    active
                @endif">
                <a href="#">
                    <i class="fa fa-user"></i> <span>Jugador</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li {{ Request::is('jugador/crear') ? ' class=active' : '' }}><a href="{{ url('jugador/crear') }}"><i class="fa fa-plus"></i> Crear</a></li>
                    <li {{ Request::is('jugador/editar') ? ' class=active' : '' }}><a href="{{ url('jugador/editar') }}"><i class="fa fa-cog"></i> Editar</a></li>
                    <li {{ Request::is('jugador/desactivar') ? ' class=active' : '' }}><a href="{{ url('jugador/desactivar') }}"><i class="fa fa-ban"></i> Desactivar</a></li>
                </ul>
            </li>
            <li class="treeview
                @if(Request::is('equipo/crear') OR Request::is('equipo/editar') OR Request::is('equipo/desactivar'))
                    active
                @endif">
                <a href="#">
                    <i class="fa fa-users"></i> <span>Equipo</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li {{ Request::is('equipo/crear') ? ' class=active' : '' }}><a href="{{ url('equipo/crear') }}"><i class="fa fa-plus"></i> Crear</a></li>
                    <li {{ Request::is('equipo/editar') ? ' class=active' : '' }}><a href="{{ url('equipo/editar') }}"><i class="fa fa-cog"></i> Editar</a></li>
                    <li {{ Request::is('equipo/desactivar') ? ' class=active' : '' }}><a href="{{ url('equipo/desactivar') }}"><i class="fa fa-ban"></i> Desactivar</a></li>
                </ul>
            </li>
            <li>
            <li>
                <a href="{{ url('torneo') }}">
                    <i class="fa fa-calendar"></i> <span>Torneo</span>
                </a>
            </li>
            <li class="treeview
                @if(Request::is('partido/crear') OR Request::is('partido/editar') OR Request::is('partido/desactivar'))
                    active
                @endif">
                <a href="#">
                    <i class="fa fa-futbol-o"></i> <span>Partido</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li {{ Request::is('partido/crear') ? ' class=active' : '' }}><a href="{{ url('partido/crear') }}"><i class="fa fa-plus"></i> Crear</a></li>
                    <li {{ Request::is('partido/editar') ? ' class=active' : '' }}><a href="{{ url('partido/editar') }}"><i class="fa fa-cog"></i> Editar</a></li>
                    <li {{ Request::is('partido/desactivar') ? ' class=active' : '' }}><a href="{{ url('partido/desactivar') }}"><i class="fa fa-ban"></i> Desactivar</a></li>
                </ul>
            </li>
            <li class="treeview
                @if(Request::is('resultado/crear') OR Request::is('resultado/editar'))
                    active
                @endif">
                <a href="#">
                    <i class="fa fa-book"></i> <span>Resultado</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li {{ Request::is('resultado/crear') ? ' class=active' : '' }}><a href="{{ url('resultado/crear') }}"><i class="fa fa-plus"></i> Crear</a></li>
                    <li {{ Request::is('resultado/editar') ? ' class=active' : '' }}><a href="{{ url('resultado/editar') }}"><i class="fa fa-cog"></i> Editar</a></li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>