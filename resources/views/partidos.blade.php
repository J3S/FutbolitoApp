@extends('layouts.master')

@section('title', 'Partido')

@section('contentHeaderTitle', 'Partido')

@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li><a href="{{ url('partido') }}"> Partido</a></li>
    <li class="active">Seleccionar partido</li>
@endsection

@section('content')
    <div class="col-xs-12">
    {{--*/ $widget = 0 /*--}}
    @foreach($partidos as $partido)
        @if($widget % 3 == 0)
            <div class="row">
        @endif
        <div class="col-md-4">
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-blue">
                    <h3 class="widget-torneo">{!! $partido['fecha'] !!}</h3>
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Fecha <span class="pull-right badge bg-green">{!! $partido['fecha'] !!}</span></a></li>
                    </ul>
                    <div class="col-md-6">
                       
                    </div>
                    <div class="col-md-6">
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- /.widget-user -->
        @if($widget % 3 == 2)
            </div>
        @endif
        <?php $widget++; ?>
    @endforeach
    </div>
@endsection