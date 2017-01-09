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
                    @if(isset($rol) && ($rol=='AFILIADO'||$ingresaPlan))
                    <a href="{{url('proyecto')}}" >
                        <img src="{{asset('images/back.png')}}" class="menu-imagen-big" alt="" title="Atr&aacute;s"/></a>
                    @else
                        <a href="{{url('planificaciones')}}" >
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
                            <a href="{{url('observaciones/'.$ideProyectoRegion)}}" >
                                <img src="{{asset('images/observaciones.png')}}" class="menu-imagen-big" alt="" title="Observaciones"/></a>
                            &nbsp;
                            &nbsp;
                            @if(isset($rol) && $rol=='COORDINADOR' && $estado!='APROBADO')
                            <button  id="btnAprobarPlan" value="{{$ideProyectoRegion}}">
                                <img src="{{asset('images/recomendation3.png')}}" class="menu-imagen-big" alt="" title="Aprobar Planificaci&oacute;n"/></button>
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
                                                <th style="text-align: right">{{$encabezado}}</th>
                                            <?php
                                                }
                                            ?>
                                            <th style="text-align: right">Total</th>
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
                                                                    }                                                                    
                                                    ?>
                                                        <tr class="info {{$total>0?'':'goodbye'}}" style="text-align: center" >
                                                            <td>{{$objetivo['objetivo']->nombre}}</td>
                                                            <td>{{$indicador['indicador']->nombre}}</td>
                                                            <td>{{$detalle['detalle']->proyecto}}</td>
                                                            <td>{{$producto['producto']->nombre}}</td>
                                                            <?php
                                                                foreach ($valores as $valor){                                                                    
                                                            ?>
                                                            <td style="text-align: right;background: #BDD7EE;">{{intval($valor->valor)}}</td>
                                                            <?php
                                                                }
                                                            ?>
                                                            <td style="text-align: right;background: greenyellow;">{{$total}}</td>
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
<ul style="list-style-type:circle" id="erroresContent">Se ocultaron todos los productos con planificaci&oacute;n 0.</ul>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
    @parent
    <meta name="_token" content="{!! csrf_token() !!}" />
    <meta name="_urlTarget" content="{{url('planregion')}}"/>
    <meta name="_ideProyectoRegion" content="{{$ideProyectoRegion}}"/>
    <script src="{{asset('js/hpme.planificacion.consolidado.js')}}"></script>
<!--        <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.planificacion.js')}}"></script>-->
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection
