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


                <div class="row">
                <div class="col-lg-12">
                      <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Soluciones Constructivas
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-pills">
                                <li><a href="{{url('/planificacion_anual')}}">Proyecto</a>
                                </li>
                                <li><a href="{{url('/planificacion_metas')}}">Metas</a>
                                </li>
                                <li><a href="{{url('/planificacion_objetivos')}}">Objetivos</a>
                                </li>
                                <li><a href="{{url('/planificacion_areas')}}">&Aacute;rea de Atenci&oacute;n</a>
                                </li>
                                <li class="active"><a href="#profile-pills" data-toggle="tab">Indicadores</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="profile-pills">
                                       <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>Indicador</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">                                      
                                        <tr class="even gradeA">
                                            <td><a href="{{url('/planificacion_productos')}}">Vivienda Nueva(Numero de Construcciones)</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a href="{{url('/planificacion_productos')}}">Vivienda Rehabilitada(Numero de Construcciones)</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a href="{{url('/planificacion_productos')}}">Mejoramiento Progresivo(Numero de Construcciones)</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a href="{{url('/planificacion_productos')}}">Reparaciones y Mejoras(Numero de construcciones)</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a href="{{url('/planificacion_productos')}}">Asistencia Tecnica Constructiva(Numero de familias)</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a href="{{url('/planificacion_productos')}}">Informacion General(Numero de individuos)</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a href="{{url('/planificacion_indicadores')}}">Capacitacion(Numero de individuos)</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                           
                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
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