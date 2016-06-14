

<!-- 
    Vista para editar un jugador.
    Contiene el formulario para editar un jugador que se muestra 
    al escoger el jugador a editar dentro de la lista de jugadores.
    Carga los datos que están registrados en la base de datos.
    
-->

@extends('layouts.master')

@section('title', 'Editar Jugador')

@section('contentHeaderTitle', 'Editar Jugador')

@section('contentHeaderBreadcrumb')
    <li><a href="#"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="#">Jugador</a></li>
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
                <h3 class="box-title">Editar Jugador</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="/jugador/{{ $jugador->id }}" method="POST">
                <input name="_method" type="hidden" value="PUT">
                {!! csrf_field() !!}
                <div class="box-body">
                    
                  <div class="form-group col-xs-12">
                        <label for="inputNombre">Nombres</label>
                        <input type="text" class="form-control" id="inputNombre" value="{{ $jugador['nombres'] }}" name="nombres" placeholder="Ingrese nombre">
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="inputApellido">Apellidos</label>
                        <input type="text" class="form-control" id="inputApellido" value="{{ $jugador['apellidos'] }}" name="apellidos" placeholder="Ingrese apellido">
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="fecha_nac">Fecha Nacimiento</label>
                        <input type="date" class="form-control" id="fecha_nac" value="{{ $jugador['fecha_nac'] }}" name="fecha_nac" placeholder="Ingrese fecha de nacimiento">
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="inputCedula">C&eacute;dula</label>
                        <input type="text" class="form-control" id="inputCedula" value="{{ $jugador['identificacion'] }}" name="identificacion" placeholder="Ingrese c&eacute;dula">
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label for="inputPeso">Rol</label>
                            <input type="text" class="form-control" id="inputRol" value="{{ $jugador['rol'] }}" name="rol" placeholder="Ej: Defensa">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputCorreo">Email</label>
                            <input type="email" class="form-control" id="inputEmail" value="{{ $jugador['email'] }}" name="email" placeholder="ejemplo@gmail.com">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="inputPeso">Tel&eacute;fono</label>
                            <input type="text" class="form-control" id="inputTelefono" value="{{ $jugador['telefono'] }}" name="telefono" placeholder="Ingrese Telefono">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label for="peso">Peso (Kg)</label>
                            <input type="number" min="0" step="0.1" class="form-control" id="peso" value="{{ $jugador['peso'] }}" name="peso" placeholder="Ej: 70.5">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-4">
                        <div class="form-group">
                            <label for="inputNumCam">N&uacute;mero de camiseta</label>
                            <input type="number" min="0" class="form-control" id="inputNumCam" value="{{ $jugador['num_camiseta'] }}" name="num_camiseta" placeholder="Ingrese N&uacute;mero">
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-4">
                        <label for="categoria">Categor&iacute;a</label>
                        <!-- Campo para seleccionar la categoría del jugador -->
                        <select class="form-control input" id="categoria" name="categoria">
                            @foreach($categorias as $categoria)
                                @if($categoria->nombre == $jugador->categoria)
                                    <option selected="selected" value="{{ $categoria['nombre'] }}">{{ $categoria->nombre}}</option>
                                @else
                                    <option value="{{ $categoria['nombre'] }}">{{ $categoria->nombre}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xs-12 col-sm-4">
                        <label for="equipo">Equipo</label>
                        <!-- Campo para seleccionar el equipo para busqueda del jugador -->
                        <select class="form-control input" id="equipo" name="equipo">
                            @foreach($equipos as $equipo)
                                @if($equipo->id == $jugador->id_equipo)
                                    <option selected="selected" value="{{ $equipo['id'] }}">{{ $equipo->nombre}}</option>
                                @else
                                    <option value="{{ $equipo['id'] }}">{{ $equipo->nombre}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>   
                  
                </div><!-- /.box-body -->

              <div class="box-footer">
                <div class="col-xs-2"></div>
                <div class="col-xs-8">
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

<!-- inicio de la sección de scripts -->
<script>
 function init(){
 // Listener 'click' para boton Reestablecer. Se encarga de asignar los valores originales del partido en todos los campos del formulario.
            $('#reestablecerBtn').click(function(){
                var equipos = <?php echo json_encode($equipos); ?>;
                var jugador = <?php echo json_encode($jugador); ?>;
                var categorias = <?php echo json_encode($categorias); ?>;

                for (var j=0; j<equipos.length; j++) {
                    if (jugador['id_equipo'] == equipos[j]['id']) {
                        $('#equipo option:contains('+equipos[j]['nombre']+')').prop({selected: true});
                    }
                }

                for (var j=0; j<categorias.length; j++) {
                    if (jugador['categoria'] == categorias[j]['id']) {
                        $('#categoria option:contains('+categoria[j]['nombre']+')').prop({selected: true});
                    }
                }
                    
               // llenarEquiposSelect();
                $('#inputNombre').val(jugador['nombres']);
                $('#inputApellido').val(jugador['apellidos']);
                var fecha = jugador['fecha_nac'].replace(" ", "T");
                $('#fecha_nac').val(fecha);
                $('#inputCedula').val(jugador['identificacion']);
                $('#inputRol').val(jugador['rol']);
                $('#inputEmail').val(jugador['email']);
                $('#inputTelefono').val(jugador['telefono']);
                $('#peso').val(jugador['peso']);
                $('#inputNumCam').val(jugador['num_camiseta']);
                $('#categoria').val(jugador['categoria']);

            });//end listener de botón de reestablecer datos de jugador

}//end init()

        window.onload = init;

</script>
@endsection