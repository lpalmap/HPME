@extends('layouts.master')
@section('globalStyles')
    @parent
        <!-- PAGE LEVEL STYLES -->
<!--    <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />-->
        <link rel="stylesheet" href="{{asset('assets/css/bootstrap-fileupload.min.css')}}"/>
    <!-- END PAGE LEVEL  STYLES -->
@endsection
@section('content')
<!--PAGE CONTENT -->
        <div id="content">

            <div class="inner">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Monitoreo por Regi&oacute;n</h2>
                    </div>
                </div>

                <hr />
                <div class="row">
                <div class="col-lg-12">
                    @if($vistaPrivilegio)
                    <a href="{{url('monitoreoproyecto')}}" >
                        <img src="{{asset('images/back.png')}}" class="menu-imagen-big" alt="" title="Atr&aacute;s"/></a>
                    @else
                        <a href="{{url('monitoreoafiliado/'.$ideProyectoPlanificacion)}}" >
                        <img src="{{asset('images/back.png')}}" class="menu-imagen-big" alt="" title="Atr&aacute;s"/></a>
                    @endif
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Regi&oacute;n {{$region}}/{{isset($plantilla)?$plantilla['proyecto']:''}}</span>                                
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            &nbsp;
                            <a href="{{url('monobservaciones/'.$idePeriodoRegion)}}" >
                                <img src="{{asset('images/observaciones.png')}}" class="menu-imagen-big" alt="" title="Observaciones"/></a>
                            &nbsp;
                            &nbsp;
                            @if($estado!='APROBADO')
                            <button  id="btnAprobarPlan" value="{{$ideProyectoRegion}}">
                                <img src="{{asset('images/recomendation3.png')}}" class="menu-imagen-big" alt="" title="Aprobar Ejecuci&oacute;n"/></button>
                            @endif
                            <button id="cleanVacio">
                                <img src="{{asset('images/clean.png')}}" class="menu-imagen-big" alt="" title="Ocultar productos con planificaci&oacute;n 0"/></button>                            
                            <div style="float: right"><span style="font-weight: bolder;">{{$estado}}</span></div>
                        </div>
                        
                         <div class="panel-body">
                             @if(isset($plantilla))                         
                             <div class="table-responsive" id="tableContent">
                                 
                                 <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left">Meta/Area/Objetivo</th>
                                            <th style="text-align: left">Indicador</th>
                                            <th style="text-align: left">Proyecto</th>
                                            <th style="text-align: left">Producto</th>
                                            <?php
                                                foreach ($encabezados as $encabezado){
                                            ?>
                                            <th  colspan="2" style="text-align: center">{{$encabezado}}</th>
                                            <?php
                                                }
                                            ?>
                                            <th>Acciones</th>
                                        </tr>
                                        <tr>
                                            <th colspan="4"></th>
                                            <?php
                                                foreach ($encabezados as $encabezado){
                                            ?>
                                            <th style="text-align: center">Planificado</th>
                                            <th style="text-align: center">Ejecutado</th>
                                            <?php
                                                }
                                            ?>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        <?php
                                            $metas=$plantilla['metas'];
                                            foreach ($metas as $meta){
                                                $objetivos=$meta['objetivos'];
                                        ?>
                                            <tr class="warning" style="text-align: center" >
                                                <td style="background: darkblue;font-weight: bolder;color: white">{{$meta['meta']->nombre}}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <?php 
                                                    for($f=0;$f<$num_items;$f++){
                                                        echo '<td></td>';
                                                        echo '<td></td>';
                                                    } 
                                                ?>
                                                <td></td>
                                            </tr>
                                            <?php 
                                                foreach($objetivos as $objetivo){
                                                    $areas=$objetivo['areas'];
                                                    foreach($areas as $area){
                                                        $indicadores=$area['indicadores'];
                                            ?>
                                                <tr class="success" style="text-align: center" >
                                                    <td style="background:  #008dc5;font-weight: bolder;color: white">{{$area['area']->nombre}}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <?php 
                                                        for($f=0;$f<$num_items;$f++){
                                                            echo '<td></td>';
                                                            echo '<td></td>';
                                                        } 
                                                    ?>
                                                    <td></td>
                                                </tr>
                                                    <?php
                                                        foreach($indicadores as $indicador){
                                                            $productos=$indicador['productos'];
                                                            foreach($productos as $producto){
                                                                $detalles=$producto['detalles'];
                                                                foreach ($detalles as $detalle){
                                                                    $total=0;
                                                                    $valores=$detalle['valores'];
                                                                    foreach ($valores as $valor){
                                                                        $total=$total+$valor->valor;
                                                                        break;
                                                                    }                                                                    
                                                    ?>
                                                        <tr class="info {{$total>0?'':'goodbye'}}" style="text-align: center" >
                                                            <td>{{$objetivo['objetivo']->nombre}}</td>
                                                            <td>{{$indicador['indicador']->nombre}}</td>
                                                            <td>{{$detalle['detalle']->proyecto}}</td>
                                                            <td>{{$producto['producto']->nombre}}</td>
                                                            <?php
                                                                $ideDetalle=null;
                                                                foreach ($valores as $valor){
                                                                    $ideDetalle=$valor->ide_region_producto_detalle;
                                                            ?>
                                                            <td style="text-align: right;background: #BDD7EE;">{{intval($valor->valor)}}</td>
                                                            <td style="text-align: right;background:  #f4c37d" id="ejecutado{{$ideDetalle}}" >{{number_format($valor->ejecutado,(fmod($valor->ejecutado, 1) !== 0.00)?2:0)}}</td>
                                                            <?php
                                                                    break;
                                                                }
                                                            ?>
                                                            <td>
                                                                @if($ingresaMon)
                                                                <button class="btn-editar-valor" value="{{$ideDetalle}}">
                                <img src="{{asset('images/editar.png')}}" class="menu-imagen" alt="" title="Editar"/></button>
                                                                @endif
                                                                @if($vistaPrivilegio && $producto['producto']->requiere_comprobantes==='S')
                                                                    <button class="btn-editar-valor" value="{{$ideDetalle}}">
                                <img src="{{asset('images/files.png')}}" class="menu-imagen" alt="" title="Ver archivos adjuntos"/></button>
                                                                @endif
                                                                <input type="hidden" id="comprobante{{$ideDetalle}}" value="{{$producto['producto']->requiere_comprobantes}}"/>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                        }}}
                                                    ?>
                                                <?php                                              
                                                }}
                                                ?>    
                                        <?php        
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <span>No hay proyectos de planificaci&oacute;n por regi&oacute;n ingresados</span>
                            @endif                          
                        </div>
                        </div>
                        </div>
                        </div>    
            </div>
        </div>
       <!--END PAGE CONTENT -->
@endsection
@section('outsidewraper')
<div class="modal fade" id="aprobarPlanificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Aprobar Planificaci&oacute;n</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="formAprobar">
                    <div class="form-group">
                        <label>Esta seguro de aprobar la planificaci&oacute;n para la regi&oacute;n&quest;</label>
                    </div>                                   
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" tabindex="6" class="btn btn-primary" id="btnAprobar">Aprobar</button>
                <input type="hidden" id="ide_item2" value="0"/>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmacionModal" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Mensaje</h4>
            </div>

            <div class="modal-body">
                <!-- The messages container -->
<!--                <div id="erroresContent"></div>-->
<ul style="list-style-type:circle" id="infoContent">Se ocultaron todos los productos con planificaci&oacute;n 0.</ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ingresarDetalleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Planficaci&oacute;n/Ejecuci&oacute;n</h4>
                                        </div>
                                        <div class="modal-body">
                                       <form role="form" id="formAgregarDetalle" method="POST" enctype="multipart/form-data">
                                           <div class="form-group">
                                                   <table class="table table-striped table-bordered table-hover" id="dataTableItems2">
                                    <thead>
                                        <tr>
                                            <th colspan="2" style="text-align: center">Enero-Marzo</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center">Planificado</th>
                                            <th style="text-align: center">Ejecutado</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items2" name="lista-items">                                      
                                        <tr class="even gradeA">
                                            <td>
                                                <input maxlength="13"  readonly="true"  tabindex="1" autofocus="true" style="text-align: right;" class="form-control TabOnEnter" type="text" data-mask="999999" id="planificado" value="0" />  
                                            </td>
                                            <td>
                                                <input maxlength="13" {{$estado==='ABIERTO'?'':'disabled'}} tabindex="1" autofocus="true" align="right" style="text-align: right;" class="form-control TabOnEnter" type="text" data-mask="999999" id="ejecutado" value="0" />  
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                                
                                           </div>
                                                
                                 
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
                                <input class="btn btn-primary" type="submit" value="Subir Archivos" id="subirArchivo"/> 
                            </div>
                    </div>
                                           
                                         <div class="form-group">  
                                                <div class="panel panel-default">
                                                 <div class="panel-heading">
                                                     Archivos Cargados
                                                 </div>
                                                 <div class="panel-body">
                                                     <div class="table-responsive">
                                                         <table class="table" id="tabla_archivos">
                                                             <thead>
                                                                 <tr style="text-align: center">
                                                                     <th>Nombre</th>
                                                                     <th>Fecha/Hora</th>
                                                                     <th>Descargar</th>
                                                                 </tr>
                                                             </thead>
                                                             <tbody>
                                                             </tbody>
                                                         </table>
                                                     </div>
                                                 </div>
                                             </div>
                                         </div>
                                       </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="button" tabindex="6" class="btn btn-primary" id="btnGuardarDetalle">Guardar</button>
                                            <input type="hidden" id="ide_item2" value="0"/>
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
    <meta name="_urlTarget" content="{{url('periodoregion')}}"/>
    <meta name="_urlUpload" content="{{url('fileupload')}}"/>
    <meta name="_urlDownload" content="{{url('filedownload/monitoreo')}}"/> 
    <meta name="_urlDelete" content="{{url('filedelete')}}"/> 
    <meta name="_ideProyectoRegion" content="{{$ideProyectoRegion}}"/>
    <meta name="_periodo" content="{{$periodo}}"/>
    <meta name="_periodoRegion" content="{{$idePeriodoRegion}}"/>
    <meta name="_download" content="{{asset('images/download2.png')}}"/>
    <meta name="_delete" content="{{asset('images/delete.png')}}"/>
    <script src="{{asset('js/hpme.monitoreo.region.js')}}"></script>
    <script src="{{asset('assets/plugins/jasny/js/bootstrap-fileupload.js')}}"></script>
<!--        <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.planificacion.js')}}"></script>-->
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection