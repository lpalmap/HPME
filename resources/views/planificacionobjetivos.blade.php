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
                            Construir Impacto en la Comunidad
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-pills">
                                <li><a href="{{url('/planificacion_anual')}}">Proyecto</a>
                                </li>
                                <li><a href="{{url('/planificacion_metas')}}">Metas</a>
                                </li>
                                <li class="active"><a href="#profile-pills" data-toggle="tab">Objetivos</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="profile-pills">
                                       <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>Objetivo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">                                      
                                        <tr class="even gradeA">
                                            <td><a href="{{url('/planificacion_areas')}}">Financiar la misión</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a href="{{url('/planificacion_areas')}}">Movilizar corazones, las manos y las voces de los voluntarios por la causa de la vivienda adecuada y asequible</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a href="{{url('/planificacion_areas')}}">Ser una voz relevante que contribuye a sensibilizar sobre la vivienda adecuada como factor crítico para romper el ciclo de la pobreza</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a href="{{url('/planificacion_areas')}}">Servir a las familias a través de la construcción sostenible y servicios de acceso a la vivienda (SAV)</a></td>
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