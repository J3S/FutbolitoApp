<!-- 
    Vista para crear un partido.
    Contiene el formulario para crear un nuevo partido.
    También muestra todos los equipos disponibles para agregar equipo local
    y visitante dependiendo del torneo seleccionado.
-->

@extends('layouts.master')

@section('title', 'Crear Partido')

@section('contentHeaderTitle', 'Crear Partido')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{{ url('partido') }}">Partido</a></li>
    <li class="active">Crear</li>
@endsection
<style>
    .form-group .required:after {
        position:absolute;
        content:'*';
        color:red;
        margin-left: 5px;
        top:0;
    }

    .campoRequerido {
        color:red; 
        font-style:italic; 
        font-size:0.9em;
    }
</style>
@section('content')
    <div class="col-xs-12">
        <div class="col-xs-2"></div>
        <!-- Alert que muestra todos los errores en los campos si falla al hacer post -->
        <div class="col-xs-8">
            @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="col-xs-2"></div>
    </div>
   <div class="col-xs-2"></div>
    <div class="col-xs-8">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Crear Partido</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{!!route('partido.store')!!}" method="post">
            {!! csrf_field() !!}
                <div class="box-body">
                    <div class="form-group col-xs-12 col-sm-6">
                        <label class="required" for="torneo">Torneo</label>
                        <select class="form-control" id="torneo" name="torneo">
                            <option selected="selected"></option>
                            @foreach($categorias as $categoria)
                                @foreach($torneos as $torneo)
                                    @if($categoria->id == $torneo->id_categoria)
                                        <option value="{{ $torneo['id'] }}" id="{{ $torneo['anio'] }}">{{ $categoria['nombre'] }} {{ $torneo['anio'] }}</option>
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="required" for="jornada">Jornada #</label>
                            <input type="number" class="form-control" id="jornada" value="{{ old('jornada') }}" name="jornada" min="1" max="100" placeholder="Ingrese jornada del partido">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="arbitro">Arbitro</label>
                        <input type="text" class="form-control" id="arbitro" value="{{ old('arbitro') }}" name="arbitro" placeholder="Ingrese arbitro">
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label class="required" for="fecha">Fecha del partido</label>
                        <input type="datetime-local" class="form-control" id="fecha" value="{{ old('fecha') }}" name="fecha" placeholder="Ingrese fecha del partido">
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="required" for="lugar">Lugar</label>
                            <input type="text" class="form-control" id="lugar" name="lugar" value="{{ old('lugar') }}" placeholder="Ingrese lugar del partido">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <input type="text" class="form-control" id="observaciones" value="{{ old('observaciones') }}" name="observaciones" placeholder="Ingrese observaciones del partido">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 separador text-center">
                        <label class="header-group" for="listaEquipo">Equipos</label>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label class="required" for="equipo_local">Equipo local</label>
                        <select class="form-control" id="equipo_local" name="equipo_local">
                            @foreach($equipos as $equipo)
                            <option value="{{ $equipo['id'] }}">{{ $equipo['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label class="required" for="equipo_visitante">Equipo visitante</label>
                        <select class="form-control" id="equipo_visitante" name="equipo_visitante">
                            @foreach($equipos as $equipo)
                            <option value="{{ $equipo['id'] }}">{{ $equipo['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                   <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="required" for="gol_local">Goles local</label>
                            <input type="number" class="form-control" value="{{ old('gol_local') }}" id="gol_local" name="gol_local" min="0" max="100" placeholder="Ingrese goles de equipo local">
                        </div>
                    </div>
                   <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="required" for="gol_visitante">Goles visitante</label>
                            <input type="number" class="form-control" value="{{ old('gol_visitante') }}" id="gol_visitante" min="0" max="100" name="gol_visitante" placeholder="Ingrese goles de equipo visitante">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <label class="campoRequerido">(*) Campo requerido</label>
                    </div>
                </div><!-- /.box-body -->

              <div class="box-footer">
                <div class="col-xs-2"></div>
                <div class="col-xs-8">
                    <div class="col-xs-4">
                        <a class="btn btn-primary" href="{{ route("partido.index") }}">Cancelar</a>
                    </div>
                    <div class="col-xs-4 pull-right">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </div>
                <div class="col-xs-2"></div>
              </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
    <div class="col-xs-2"></div>
    <script>
        // Carga dinámica de los equipos dependiendo del torneo seleccionado
        document.getElementById("torneo").addEventListener("change", llenarEquiposSelect);
        // Funcion que filtra los equipos locales y visitantes dependiendo del torneo seleccionado
        function llenarEquiposSelect() {
            var equipos = <?php echo json_encode($equipos); ?>;
            var torneoEquipos = <?php echo json_encode($torneoEquipos); ?>;
            var torneo = $("#torneo option:selected").attr("value");
            var anio = $("#torneo option:selected").attr("id");
            var fechaInicial = anio+"-01-01T00:00:00";
            var fechaFinal = anio+"-12-31T23:59:59";
            $('#fecha').attr('min', fechaInicial);
            $('#fecha').attr('max', fechaFinal);
            $('#equipo_local').find('option').remove().end();
            $('#equipo_visitante').find('option').remove().end();

            // Agrego solo los equipos que participan en el torneo seleccionado
            for (var i = 0; i < torneoEquipos.length; ++i){
                if(torneoEquipos[i]['id_torneo'] == torneo){
                    for(var j = 0; j < equipos.length; ++j){
                        if(equipos[j]['id'] == torneoEquipos[i]['id_equipo']){
                            $('#equipo_local').append($('<option value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));
                            $('#equipo_visitante').append($('<option value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));
                        }
                    }
                }
            }
            
        }

        window.onload = llenarEquiposSelect;
    </script>
@endsection