<!-- 
    Vista para editar un partido.
    Contiene el formulario para editar un partido.
    Carga los datos que están registrados en la base de datos.
    También muestra todos los equipos disponibles para modificar
    equipo local o visitante dependiendo del torneo seleccionado.
-->

@extends('layouts.master')

@section('title', 'Editar Partido')

@section('contentHeaderTitle', 'Editar Partido')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{{ url('partido') }}">Partido</a></li>
    <li class="active">Editar</li>
@endsection

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
                <h3 class="box-title">Editar Partido</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="/partido/{{ $partido->id }}" method="POST">
                <input name="_method" type="hidden" value="PUT">
                {!! csrf_field() !!}
                <div class="box-body">
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="torneo">Torneo</label>
                        <select class="form-control" id="torneo" name="torneo">
                            @foreach($categorias as $categoria)
                                @foreach($torneos as $torneo)
                                    @if($categoria->id == $torneo->id_categoria)
                                        @if($torneo->id == $partido->id_torneo)
                                            <option selected="selected" value="{{ $torneo['id'] }}">{{ $categoria['nombre'] }} {{ $torneo['anio'] }}</option>
                                        @else
                                            <option value="{{ $torneo['id'] }}">{{ $categoria['nombre'] }} {{ $torneo['anio'] }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                     <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="jornada">Jornada #</label>
                            <input type="number" value="{{ $partido['jornada'] }}" min="0" class="form-control" id="jornada" name="jornada" placeholder="Ingrese jornada del partido">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="arbitro">Arbitro</label>
                        <input type="text" value="{{ $partido['arbitro'] }}" class="form-control" id="arbitro" name="arbitro" placeholder="Ingrese arbitro">
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="fecha">Fecha del partido</label>
                        <input type="datetime-local" value="{{ $date }}" class="form-control" id="fecha" name="fecha" >
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" value="{{ $partido['lugar'] }}" class="form-control" id="lugar" name="lugar" placeholder="Ingrese lugar del partido">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <input type="text" value="{{ $partido['observacion'] }}"  class="form-control" id="observaciones" name="observaciones" placeholder="Ingrese observaciones del partido">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 separador text-center">
                        <label class="header-group" for="listaEquipo">Equipos</label>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="equipo_local">Equipo local</label>
                        <select class="form-control" id="equipo_local" name="equipo_local">
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-6">
                        <label for="equipo_visitante">Equipo visitante</label>
                        <select class="form-control" id="equipo_visitante" name="equipo_visitante">
                        </select>
                    </div>
                   <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="gol_local">Goles local</label>
                            <input type="number" value="{{ $partido['gol_local'] }}" min="0" class="form-control" id="gol_local" name="gol_local">
                        </div>
                    </div>
                   <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="gol_visitante">Goles visitante</label>
                            <input type="number" value="{{ $partido['gol_visitante'] }}" min="0" class="form-control" id="gol_visitante" name="gol_visitante">
                        </div>
                    </div>
                </div><!-- /.box-body -->

              <div class="box-footer">
                <div class="col-xs-2"></div>
                <div class="col-xs-12">
                    <div class="col-xs-4">
                        <button type="button" id="reestablecerBtn" class="btn btn-primary">Reestablecer</button>
                    </div>
                    <div class="col-xs-4 pull-right">
                        <button type="submit" class="btn btn-success">Actualizar</button>
                    </div>
                </div>
                <div class="col-xs-2"></div>
              </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
    <div class="col-xs-2"></div>
    <script>
    
        function init(){
            
            llenarEquiposSelect();

            document.getElementById("torneo").addEventListener("change", llenarEquiposSelect);

            // Funcion que filtra los equipos locales y visitantes dependiendo del torneo seleccionado
            function llenarEquiposSelect() {

                var partido = <?php echo json_encode($partido); ?>;
                var equipos = <?php echo json_encode($equipos); ?>;
                var torneoEquipos = <?php echo json_encode($torneoEquipos); ?>;
                var torneo = $("#torneo option:selected").attr("value");
                $('#equipo_local').find('option').remove().end();
                $('#equipo_visitante').find('option').remove().end();

                if ($("#torneo option:selected").val() == "") {
                    for (var j = 0; j < equipos.length; ++j) {
                        $('#equipo_local').append($('<option value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));
                        $('#equipo_visitante').append($('<option value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));
                    }
                }
                else {
                    // Agrego solo los partidos que participan en el torneo seleccionado
                    for (var i = 0; i < torneoEquipos.length; ++i) {
                        if (torneoEquipos[i]['id_torneo'] == torneo) {
                            for (var j = 0; j < equipos.length; ++j) {
                                if (equipos[j]['id'] == torneoEquipos[i]['id_equipo']) {
                                    if (equipos[j]['nombre'].localeCompare(partido['equipo_local']) == 0) {
                                        $('#equipo_local').append($('<option selected="selected" value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));    
                                    }
                                    else {
                                       $('#equipo_local').append($('<option value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>')); 
                                    }
                                    if (equipos[j]['nombre'].localeCompare(partido['equipo_visitante']) == 0) {
                                        $('#equipo_visitante').append($('<option selected="selected" value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));
                                    }
                                    else {
                                        $('#equipo_visitante').append($('<option value="'+equipos[j]['id']+'">'+equipos[j]['nombre']+'</option>'));
                                    }
                                }
                            }
                        }
                    }
                }
            }//end llenarEquiposSelect()

            // Listener 'click' para boton Reestablecer. Se encarga de asignar los valores originales del partido en todos los campos del formulario.
            $('#reestablecerBtn').click(function(){
                var torneos = <?php echo json_encode($torneos); ?>;
                var partido = <?php echo json_encode($partido); ?>;
                var categorias = <?php echo json_encode($categorias); ?>;

                for (var i=0; i<categorias.length; i++) {
                    for (var j=0; j<torneos.length; j++) {
                        if (torneos[j]['id_categoria'] == categorias[i]['id']) {
                            if (partido['id_torneo'] == torneos[j]['id']) {
                                $('#torneo option:contains('+categorias[i]['nombre']+' '+torneos[j]['anio']+')').prop({selected: true});
                            }
                        }
                    }
                }

                llenarEquiposSelect();
                $('#gol_local').val(partido['gol_local']);
                $('#gol_visitante').val(partido['gol_visitante']);
                $('#observaciones').val(partido['observacion']);
                $('#lugar').val(partido['lugar']);
                var fecha = partido['fecha'].replace(" ", "T");
                $('#fecha').val(fecha);
                $('#jornada').val(partido['jornada']);
                $('#arbitro').val(partido['arbitro']);
            });//end reestablecerBtn listener

        }//end init()

        window.onload = init;

    </script>
@endsection