<!-- Left side column contains sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <br/>
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
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
