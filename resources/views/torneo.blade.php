@extends('layouts.master')

@section('title', 'Torneo')

@section('contentHeaderTitle', 'Torneo')

@section('contentHeaderBreadcrumb')
    <li><a href="/"><i class="fa fa-user"></i> Home</a></li>
    <li class="active">Torneo</li>
@endsection

@section('content')
    <div class="col-xs-12" style="padding-bottom: 15px;">
        <button type="button" class="btn btn-success" onclick="window.location='{{ route("torneo.create") }}'"><i class="fa fa-plus"></i> Nuevo Torneo</button>
    </div>
    <div class="col-xs-12">
    {{--*/ $widget = 0 /*--}}
    @foreach($torneos as $torneo)
        @if($widget % 3 == 0)
            <div class="row">
        @endif
        <div class="col-md-4">
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-blue">
                    <div class="widget-user-image col-xs-4" style="padding-left: 0px;">
                        <div class="col-xs-6" style="padding-left: 0px;">
                            <button type="button" class="btn btn-warning"><i class="fa fa-pencil"></i></button>
                        </div>
                        <div class="col-xs-6" style="padding-left: 0px;">
                            <button type="button" class="btn btn-danger"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <h3 class="widget-torneo text-right">{!! $torneo['categoria'] !!}</h3>
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <li><a href="#">Equipos <span class="pull-right badge bg-blue">31</span></a></li>
                        <li><a href="#">Partidos <span class="pull-right badge bg-aqua">5</span></a></li>
                        <li><a href="#">Fecha de inicio <span class="pull-right badge bg-green">{!! $torneo['fechaInicio'] !!}</span></a></li>
                        <li><a href="#">Fecha de fin <span class="pull-right badge bg-red">{!! $torneo['fechaFin'] !!}</span></a></li>
                    </ul>
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