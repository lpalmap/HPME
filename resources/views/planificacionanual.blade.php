@extends('layouts.master')
@section('globalStyles')
    @parent
        <!-- PAGE LEVEL STYLES -->
    <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- END PAGE LEVEL  STYLES -->
@endsection
@section('content')
<!--PAGE CONTENT -->
        <div id="content">

            <div class="inner">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Proyectos de Planificaci&oacute;n Anual</h2>
                    </div>
                </div>

                <hr />


<!--                <div class="row">-->
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Plantillas de Planificaci&oacute;n</span>
                        </div>
                        
                         <div class="panel-body">
                             <ul class="nav nav-pills">
                                <li class="active"><a href="#tableContent" data-toggle="tab">Plantillas</a>
                                </li>
                            </ul>
                             <div class="table-responsive" id="tableContent">
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Descripci&oacute;n</th>
                                            <th>Usuario</th>
                                            <th>Periodo Planificaci&oacute;n</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($items))
                                            @for ($i=0;$i<count($items);$i++)
                                        <tr class="even gradeA" id="item{{$items[$i]->ide_proyecto}}">
                                            <td>{{$items[$i]->fecha_proyecto}}</td>
                                            <td><a href="{{url('/planificacion_metas')}}">{{$items[$i]->descripcion}}</a></td>
                                            <td>lpalma</td>
                                            <td>Trimestral</td>
                                            <td>
                                                <a href="{{url('/planificacion_metas')}}" class="btn btn-primary btn-editar" value="{{$items[$i]->ide_proyecto}}"><i class="icon-pencil icon-white" ></i> Editar</a>
                                                <button class="btn btn-danger" value="{{$items[$i]->ide_proyecto}}"><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
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
        <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection