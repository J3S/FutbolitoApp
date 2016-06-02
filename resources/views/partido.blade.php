@extends('layouts.master')

@section('title', 'Partido')

@section('contentHeaderTitle', 'Partido')

@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li class="active">Partido</li>
@endsection

@section('content')
    <div class="col-xs-12" style="padding-bottom: 15px;">
        <form>
            <button type="button" id="nuevoPartidoButton" class="btn btn-success" onclick="window.location='{{ route("partido.create") }}'"><i class="fa fa-plus"></i> Nuevo Partido</button>
        </form>
    </div>

	<div class="col-xs-8">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Buscar Partido por Fecha</h3>
			</div><!-- /.box-header -->
            <form role="form" action="/selectPartido" method="post">
            {!! csrf_field() !!}
				<div class="box-body">
					<div class="form-group col-xs-12 col-sm-4">
						<label for="iniPartido">Fecha inicio</label>
						<input type="datetime-local" class="form-control" id="iniPartido" name="ini_partido">
					</div>
					<div class="form-group col-xs-12 col-sm-4">
						<label for="finPartido">Fecha fin</label>
						<input type="datetime-local" class="form-control" id="finPartido" name="fin_partido">
					</div>
					<div class="col-xs-12" >
	                    <button type="submit" class="btn btn-success">Buscar</button>
	                </div>
				</div>
			</form>
		</div>
	</div>
@endsection