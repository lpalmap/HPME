@extends('layouts.master')
@section('globalStyles')
    @parent
        <!-- PAGE LEVEL STYLES -->
    <link href="{{asset('assets/plugins/dataTables/dataTables.bootstrap.css')}}" rel="stylesheet" />
    <!-- END PAGE LEVEL  STYLES -->
@endsection
@section('content')
<!--PAGE CONTENT -->
        <div id="content">

            <div class="inner">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Administraci&oacute;n de Monitoreo</h2>
                    </div>
                </div>

                <hr />


<!--                <div class="row">-->
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Proyectos de Planificaci&oacute;n</span>
                        </div>
                        
                         <div class="panel-body">
                             <div class="table-responsive" id="tableContent">
                                 
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Fecha Cierre</th>
                                            <th>Descripci&oacute;n</th>
                                            <th>Periodicidad</th>
                                            <th>Estado Plantilla</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($items))
                                            @for ($i=0;$i<count($items);$i++)
                                        <tr class="even gradeA" id="item{{$items[$i]->ide_proyecto}}">
                                            <td>{{$items[$i]->fecha_proyecto}}</td>
                                            <td>{{$items[$i]->fecha_cierre}}</td>
                                            <td><a href="{{url('/adminmonitoreo/'.$items[$i]->ide_proyecto)}}">{{$items[$i]->descripcion}}</a></td>
                                            <td>{{$items[$i]->periodicidad->descripcion}}</td>
                                            <td>{{$items[$i]->estado}}</td>
                                        </tr>
                                            @endfor
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                           
                        </div>
<!--            </div>-->
            </div>
        </div>
       <!--END PAGE CONTENT -->
@endsection
@section('footer')
    @parent
        <meta name="_token" content="{!! csrf_token() !!}" />
        <meta name="_url" content="{{url('planproyecto')}}"/>
        <meta name="_urlTarget" content="{{url('plantilla')}}"/>
        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.monitoreo.administracion.js')}}"></script>
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection