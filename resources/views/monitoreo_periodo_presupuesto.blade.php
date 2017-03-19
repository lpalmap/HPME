@extends('layouts.master')
@section('globalStyles')
    @parent
        <!-- PAGE LEVEL STYLES -->
    <link href="{{asset('assets/plugins/dataTables/dataTables.bootstrap.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-fileupload.min.css')}}"/>
    <!-- END PAGE LEVEL  STYLES -->
@endsection
@section('content')
<!--PAGE CONTENT -->
        <div id="content">

            <div class="inner">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Ejecuci&oacute;n Presupuesto/{{$periodo}}</h2>
                    </div>
                </div>

                <hr />


<!--                <div class="row">-->
                <div class="row">
                <div class="col-lg-12">
                    <a href="{{url('proyectocontador/'.$ideProyecto)}}">
                        <img src="{{asset('images/back.png')}}" class="menu-imagen-big" alt="" title="Atr&aacute;s"/></a>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Periodos Monitoreo</span>
                        </div>
                        <form role="form" id="formAgregarDetalle" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                                         <span id="archivoLabel" style="color: red; " ></span>
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <span class="btn btn-file btn-default">
                                        <span class="fileupload-new">Seleccionar Archivo</span>
                                    <span class="fileupload-exists">Cambiar</span>
                                    <input type="file" multiple="true" name="files" id="fileUpload"/>
                                </span>
                                <span class="fileupload-preview"></span>
                                <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
                                <input class="btn btn-primary" type="submit" name="action" value="Verificar" id="vista"/> 
                                <input class="btn btn-primary" type="submit" name="action" value="Subir" id="subir"/> 
                            </div>
                            </div>
                        </form>
                         <hr />
                         <span>Archivos Cargados</span>
                         <hr />
                         <div class="panel-body">
                            <table class="table" id="tabla_archivos">
                             <thead>
                                 <tr style="text-align: center">
                                     <th>Nombre</th>
                                     <th>Fecha/Hora</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 @for ($i=0;$i<count($archivos);$i++)
                                    <tr class="even gradeA">
                                        <td>{{$archivos[$i]->nombre}}</td>
                                        <td>{{$archivos[$i]->fecha}}</td>
                                    </tr>
                                 @endfor
                             </tbody>
                         </table>
                        </div>
<!--            </div>-->
            </div>
        </div>
       <!--END PAGE CONTENT -->
@endsection
@section('outsidewraper')
<div class="modal fade" id="detalleArchivosModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Archivos Cargados</h4>
                                        </div>
                                        <div class="modal-body">
                                       <form role="form"  method="POST" enctype="multipart/form-data">
                                                                                             
                                         <div class="form-group">  
                                                <div class="panel panel-default">
                                                 <div class="panel-heading">
                                                     Verificaci&oacute;n Archivo
                                                 </div>
                                                 <div class="panel-body">
                                                     <div class="form-group">
                                                        <label>Total filas archivo</label>
                                                        <input class="form-control" id="inFila" required="true"/>
                                                     </div>
                                                     <div class="form-group">
                                                        <label>Total filas encontradas</label>
                                                        <input class="form-control" id="inFilaEncontrada" required="true"/>
                                                     </div>
                                                     <div class="form-group">
                                                        <label>Total filas no encontradas</label>
                                                        <input class="form-control" id="inFilaNoEncontrada" required="true"/>
                                                     </div>
                                                     <div class="form-group">
                                                        <label>Total monto filas encontradas</label>
                                                        <input class="form-control" id="inMontoEncontrado" required="true"/>
                                                     </div>
                                                     <div class="form-group">
                                                        <label>Total monto filas no encontradas</label>
                                                        <input class="form-control" id="inMontoNoEncontrado" required="true"/>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                       </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


<div class="modal fade" id="confirmarModal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Subir Archivo</h4>
            </div>

            <div class="modal-body">
                <!-- The messages container -->
<!--                <div id="erroresContent"></div>-->
<ul style="list-style-type:circle" id="infoContent">Se carg&oac&oacute; correctamente el archivo de ejecuci&oacute;n para el periodo.</ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>


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
        <meta name="_token" content="{!! csrf_token() !!}" />
        <meta name="_urlUpload" content="{{url('upload')}}"/>
        <meta name="_proyecto" content="{{$ideProyecto}}"/>
        <meta name="_periodo" content="{{$idePeriodoMonitoreo}}"/>
        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.monitoreo.contador.js')}}"></script>
        <script src="{{asset('assets/plugins/jasny/js/bootstrap-fileupload.js')}}"></script>
<!--        <script src="{{asset('js/hpme.monitoreo.administracion.js')}}"></script>-->
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection