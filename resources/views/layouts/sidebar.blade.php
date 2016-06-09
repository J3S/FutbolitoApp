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
                <a href="{{ url('jugador') }}">
                    <i class="fa fa-user"></i> <span>Jugadores</span>
                </a>
            </li>
            <li>
                <a href="{!!route('equipo.index')!!}">
                    <i class="fa fa-users"></i> <span>Equipos</span>
                </a>
            </li>
            <li>
            <li>
                <a href="{{ url('torneo') }}">
                    <i class="fa fa-calendar"></i> <span>Torneos</span>
                </a>
            </li>
             <li>
                <a href="{{ url('partido') }}">
                    <i class="fa fa-futbol-o"></i> <span>Partidos</span>
                </a>
            </li>
            <li class="treeview
                @if(Request::is('resultado/crear') OR Request::is('resultado/editar'))
                    active
                @endif">
                <a href="#">
                    <i class="fa fa-book"></i> <span>Resultados</span> <i class="fa fa-angle-left pull-right"></i>
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
