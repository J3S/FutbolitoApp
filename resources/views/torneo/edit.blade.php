<!-- 
    Vista para editar un torneo.
    Contiene el formulario para editar un nuevo torneo.
    Carga los datos que están registrados en la base de datos.
    También muestra todos los equipos disponibles para agregar a un torneo
    dependiendo de la categoría seleccionada.
-->

<!-- Plantilla a usar -->
@extends('layouts.master')

<!-- Agregar el título de la página -->
@section('title', 'Editar Torneo')

<!-- Agregar los elementos del breadcrumb -->
@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{{ url('torneo') }}">Torneo</a></li>
    <li class="active">Editar</li>
@endsection

<!-- Agregar el contenido de la página -->
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
        <!-- Formulario -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar torneo</h3>
            </div><!-- /.box-header -->
            <form role="form" action="/torneo/{{ $torneo->id }}" method="POST" id="formCrearTorneo">
                {{ method_field('PUT') }}
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="form-group">
                            <!-- Campo año -->
                            <div class="col-md-2">
                                <label for="anio">A&ntilde;o</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" class="form-control" id="anio" name="anio" placeholder="A&ntilde;o" value="{{ $torneo->anio }}">
                            </div>

                            <!-- Campo categoría -->
                            <div class="col-md-2">
                                <label for="categoria">Categor&iacute;a</label>
                            </div>
                            <div class="col-md-3">
                                <!-- Se carga las categorías recibidas del servidor -->
                                <select class="form-control" id="categoria" name="categoria">
                                    @if(count($categorias) != 0)
                                        @foreach($categorias as $categoria)
                                            @if($categoria->id == $torneo->id_categoria)
                                                <option selected="selected">{{ $categoria->nombre }}</option>
                                            @else
                                                <option>{{ $categoria->nombre }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                            <option>No se ha registrado ninguna categor&iacute;a</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <label>Equipos disponibles</label>
                        </div>
                        <!-- Equipos que pueden ser agregados al torneo dependiendo de la categoría -->
                        <div class="col-md-3">
                            <select class="form-control equipo-seleccionado" id="equipos">
                                @if(count($equiposxcategorias[$categorias[0]->nombre]) != 0)
                                    @foreach($equiposxcategorias[$categorias[0]->nombre] as $equipos)
                                        <option>{{ $equipos->nombre }}</option>
                                    @endforeach
                                @else
                                        <option>No se ha registrado ninguna categor&iacute;a</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary" id="agregarEquipo">Agregar equipo</button>
                        </div>
                        <div class="col-md-1"></div>
                        <div class="col-md-2">
                            <a type="button" class="btn btn-primary" id="agregarTodos">Agregar a todos</a>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Tabla que contiene a todos los equipos que van a ser agregados al torneo -->
                            <h5><b>Equipos Agregados</b></h5>
                            <div class="table-responsive">
                                <table class="table table-hover" id="equiposAgregados">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="datosEquipo">
                                        @if(count($equiposAgregados) != 0)
                                            @foreach($equiposAgregados as $equipoAgregado)
                                                <tr>
                                                    <td>{{ $equipoAgregado->nombre }}</td>
                                                    <td><button type="button" class="btn btn-danger btn-xs" onclick="eliminarRow(this)" data-input="{{ $equipoAgregado->nombre }}"><i class="fa fa-minus"></i> Quitar</button></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <a class="btn btn-primary" href="{{ route("torneo.index") }}">Cancelar</a>
                            <a class="btn btn-primary" id="reestablecer">Reestablecer</a>
                            <button type="submit" class="btn btn-success">Actualizar</button>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
                @if(count($equiposAgregados) != 0)
                    @foreach($equiposAgregados as $equipoAgregado)
                        <input type="hidden" id="{{ $equipoAgregado->nombre }}" name="{{ $equipoAgregado->nombre }}" value="{{ $equipoAgregado->nombre }}"/>
                    @endforeach
                @endif
            </form>
        </div><!-- /.box -->
    </div><!--/.col (middle) -->
    <div class="col-xs-2"></div>
@endsection

@section('scriptsPersonales')
    <script>
        document.getElementById("agregarEquipo").addEventListener("click", agregarEquipoTabla);
        document.getElementById("agregarTodos").addEventListener("click", agregarTodosTabla);
        document.getElementById("categoria").addEventListener("click", llenarEquiposSelect);
        document.getElementById("reestablecer").addEventListener("click", reestablecerCampos);
        window.onload = llenarEquiposSelect;

        function crearRowEquipoTabla(valor) {
            // Agregar el equipo en la tabla
            $('#datosEquipo').append('<tr><td>' + valor + '</td><td><button type="button" class="btn btn-danger btn-xs" onclick="eliminarRow(this)" data-input="' + valor + '"><i class="fa fa-minus"></i> Quitar</button></td></tr>');
        }

        function crearInputEquipo(valor) {
            // Agregar el campo input que será enviado en el post(con el nombre del equipo).
            $('#formCrearTorneo').append('<input type="hidden" id="' + valor + '" name="' + valor + '" value="' + valor + '"/>');
        }

        function eliminarEquiposTabla() {
            //Si hay equipos agregados los elimino.
            $('#equiposAgregados > tbody  > tr').each(function() {
                $this = $(this)
                var inputElementID = $this.children().eq(1).children().data('input');
                $('#'+inputElementID).remove();
            });
            $("#equiposAgregados > tbody").html("");
        }

        // Agrega el equipo seleccionado a la tabla.
        function agregarEquipoTabla() {
            if($( "#equipos option:selected" ).text().length != 0){
                // Verificación si el equipo ya está presente en la tabla de equipos para el torneo.
                if ($('#'+$( "#equipos option:selected" ).text()).length == 0) {
                    crearRowEquipoTabla($( "#equipos option:selected" ).text());
                    crearInputEquipo($( "#equipos option:selected" ).text());
                } else {
                    alert('Este equipo ya ha sido agregado');
                } 
            }
        }

        // Agrega a todos los equipos de la categoría seleccionada a la tabla.
        function agregarTodosTabla() {
            eliminarEquiposTabla();
            // Obtención de todos los equipos divididos por categorías.
            var equiposCategoria = <?php echo json_encode($equiposxcategorias); ?>;
            var categoria = $("#categoria option:selected").text();
            var index;
            // Llenado de la tabla y creación de los campos input ocultos.
            for (index = 0; index < equiposCategoria[categoria].length; ++index) {
                crearRowEquipoTabla(equiposCategoria[categoria][index]['nombre']);
                crearInputEquipo(equiposCategoria[categoria][index]['nombre']);
            }
        }

        // Carga dinámica de los equipos dependiendo de la categoría seleccionada.
        function llenarEquiposSelect() {
            // Obtención de todos los equipos divididos por categorías.
            var equiposCategoria = <?php echo json_encode($equiposxcategorias); ?>;
            var categoria = $("#categoria option:selected").text();
            $('#equipos').find('option').remove().end();
            var index;
            // LLenado del select de equipos.
            for (index = 0; index < equiposCategoria[categoria].length; ++index) {
                $('#equipos').append($('<option>' + equiposCategoria[categoria][index]['nombre'] + '</option>'));
            }
        }

        // Eliminación de la fila y del input que contiene al equipo que se desea quitar de la tabla de equipos para el torneo.
        function eliminarRow(buttonTriggered) {
            $(buttonTriggered).parent().parent().remove();
            var inputElementID = $(buttonTriggered).data('input');      
            $('#'+inputElementID).remove();
        }

        // Reestablecimiento de los campos originales.
        function reestablecerCampos() {
            // Reestablecimiento del año original.
            $('#anio').val(<?php echo $torneo->anio; ?>);
            // Reestablecimiento de la categoría original.
            var categorias = <?php echo json_encode($categorias); ?>;
            var index;
            var categoriaIndexSelect;
            var idCategoriaTorneo = <?php echo $torneo->id_categoria; ?>;
            for (index = 0; index < categorias.length; ++index) {
                if (categorias[index]['id'] === idCategoriaTorneo)
                    categoriaIndexSelect = index;
            }
            document.getElementById("categoria").selectedIndex = categoriaIndexSelect;
            // Llenado de la tabla con los equipos originalmente agregados.
            llenarEquiposSelect();
            eliminarEquiposTabla();
            var equiposAgregados = <?php echo json_encode($equiposAgregados); ?>;
            for (index = 0; index < equiposAgregados.length; ++index) {
                crearRowEquipoTabla(equiposAgregados[index]['nombre']);
                crearInputEquipo(equiposAgregados[index]['nombre']);
            }
        }
    </script>
@endsection