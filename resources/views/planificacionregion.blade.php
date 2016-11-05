@extends('layouts.master')
@section('globalStyles')
    @parent
        <!-- PAGE LEVEL STYLES -->
<!--    <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />-->
    <!-- END PAGE LEVEL  STYLES -->
@endsection
@section('content')
<!--PAGE CONTENT -->
        <div id="content">

            <div class="inner">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Planificaci&oacute;n por Regi&oacute;n</h2>
                    </div>
                </div>

                <hr />
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Regiones</span>
                        </div>
                        
                         <div class="panel-body">
                             <div class="table-responsive" id="tableContent">
                                 
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>Regi&oacute;n</th>
                                            <th>Usuario</th>
                                            <th>Fecha Ingreso</th>
                                            <th>Fecha Aprobaci&oacute;n</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        <tr class="active">
                                            <td>Regi&oacute;n</td>
                                            <td>Usuario</td>
                                            <td>Fecha Ingreso</td>
                                            <td>Fecha Aprobaci&oacute;n</td>
                                            <td>Estado</td>
                                        </tr>
                                        <tr class="success">
                                            <td>Regi&oacute;n</td>
                                            <td>Usuario</td>
                                            <td>Fecha Ingreso</td>
                                            <td>Fecha Aprobaci&oacute;n</td>
                                            <td>Estado</td>
                                        </tr>
                                        <tr class="warning">
                                            <td>Regi&oacute;n</td>
                                            <td>Usuario</td>
                                            <td>Fecha Ingreso</td>
                                            <td>Fecha Aprobaci&oacute;n</td>
                                            <td>Estado</td>
                                        </tr>
                                        <tr class="danger">
                                            <td>Regi&oacute;n</td>
                                            <td>Usuario</td>
                                            <td>Fecha Ingreso</td>
                                            <td>Fecha Aprobaci&oacute;n</td>
                                            <td>Estado</td>
                                        </tr>
                                        <tr class="info">
                                            <td>Regi&oacute;n</td>
                                            <td>Usuario</td>
                                            <td>Fecha Ingreso</td>
                                            <td>Fecha Aprobaci&oacute;n</td>
                                            <td>Estado</td>
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
       <!--END PAGE CONTENT -->
@endsection
@section('outsidewraper')

<!-- Modal for displaying the messages -->
<div class="modal fade" id="erroresModal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Errores</h4>
            </div>

            <div class="modal-body">
                <!-- The messages container -->
<!--                <div id="erroresContent"></div>-->
                   <ul style="list-style-type:circle" id="erroresContent"></ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('footer')
    @parent
<!--        <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.planificacion.js')}}"></script>-->
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection
