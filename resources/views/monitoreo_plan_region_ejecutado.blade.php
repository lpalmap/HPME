@extends('layouts.master')
@section('globalStyles')
    @parent
        <!-- PAGE LEVEL STYLES -->
<!--    <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />-->
    <!-- END PAGE LEVEL  STYLES -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
/*        #graficaModal .modal-dialog {
            width: 80%;
        }*/
        #myModal1 .modal-dialog {
/*            width: 80%;*/
            width: 1200px;
        }
    </style>
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
                    @if($ingresaPlan)
                    <a href="{{url('plandetalle/'.$ideProyectoRegion)}}" >
                        <img src="{{asset('images/back.png')}}" class="menu-imagen-big" alt="" title="Atr&aacute;s"/></a>
                    @else
                        <a href="{{url('planificaciones')}}" >
                        <img src="{{asset('images/back.png')}}" class="menu-imagen-big" alt="" title="Atr&aacute;s"/></a>
                    @endif
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Regi&oacute;n {{$region}}/{{isset($plantilla)?$plantilla['proyecto']:''}}</span>
                            <div style="float: right"><span style="font-weight: bolder;">{{$estado}}</span></div>
                        </div>
                        
                         <div class="panel-body">
                             @if(isset($plantilla))                         
                             <div class="table-responsive" id="tableContent">
                                 
                                 <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left">Meta/Area/Objetivo</th>
                                            <th style="text-align: left">Proyecto</th>
                                            <th style="text-align: left">Producto</th>
                                            <?php
                                                foreach ($encabezados as $encabezado){
                                            ?>
                                                <th  colspan="2" style="text-align: center">{{$encabezado}}</th>
                                            <?php
                                                }
                                            ?>
                                                <th colspan="2" style="text-align: center">Total</th>
                                                <th style="text-align: center">Graficar</th>
                                        </tr>
                                        <tr>
                                            <th colspan="3"></th>
                                            <?php
                                                foreach ($encabezados as $encabezado){
                                            ?>
                                            <th style="text-align: center">Plan</th>
                                            <th style="text-align: center">Eje</th>
                                            <?php
                                                }
                                            ?>
                                            <th style="text-align: center">Plan</th>
                                            <th style="text-align: center">Eje</th>
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
                                                <?php 
                                                    for($f=0;$f<$num_items;$f++){
                                                        echo '<td></td>';
                                                        echo '<td></td>';
                                                    } 
                                                ?>
                                                <td></td>
                                                <td></td>
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
                                                    <?php 
                                                        for($f=0;$f<$num_items;$f++){
                                                            echo '<td></td>';
                                                            echo '<td></td>';
                                                        } 
                                                    ?>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                    <?php
                                                        foreach($indicadores as $indicador){
                                                            $productos=$indicador['productos'];
                                                            foreach($productos as $producto){
                                                                $detalles=$producto['detalles'];
                                                                foreach ($detalles as $detalle){
                                                                    $total=0;
                                                                    $ejecutado=0.0;
                                                                    $valores=$detalle['valores'];
                                                                    foreach ($valores as $valor){
                                                                        $total=$total+$valor->valor;
                                                                        $ejecutado=$ejecutado+$valor->ejecutado;
                                                                    }                                                                    
                                                    ?>
                                                        <tr class="info {{$total>0?'':'goodbye'}}" style="text-align: center" >
                                                            <td>{{$objetivo['objetivo']->nombre}}</td>
<!--                                                            <td>{{$indicador['indicador']->nombre}}</td>-->
                                                            <td>{{$detalle['detalle']->proyecto}}</td>
                                                            <td>{{$producto['producto']->nombre}}</td>
                                                            <?php
                                                                foreach ($valores as $valor){                                                                    
                                                            ?>
                                                            <td style="text-align: right;background: #BDD7EE;">{{number_format(intval($valor->valor))}}</td>
                                                            <td style="text-align: right;background:  #f4c37d">{{isset($valor->ejecutado)?number_format($valor->ejecutado,(fmod($valor->ejecutado, 1) !== 0.00)?2:0):'0'}}</td>
                                                            <?php
                                                                }
                                                            ?>
                                                            <td style="text-align: right;background: greenyellow;">{{number_format($total)}}</td>
                                                            <td style="text-align: right;background: #f4c37d;">{{number_format($ejecutado,(fmod($ejecutado, 1) !== 0.00)?2:0)}}</td>
                                                            <td>
                                                                <button class="btn-graficar" value="{{$detalle['detalle']->ide_region_producto}}"><img src="{{asset('images/grafico2.png')}}" class="menu-imagen" alt="" title="Graficar"/></but
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
<div class="modal fade" id="graficaModal" style="width: 900px" tabindex="-1" role="dialog" aria-labelledby="Login" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Graficar</h4>
            </div>

            <div class="modal-body">
                   <div id="piechart1" style="width: 900px; height: 500px;"></div> 
                   <div id="columnchart_material1" style="width: 900px; height: 500px;"></div> 
                   
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

<center>
  <!-- Modal -->
  <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Modal title</h4>
        </div>
        <div class="modal-body">
          <div id="piechart" style="width: 900px; height: 500px"></div> 
                   <div id="columnchart_material" style="width: 900px; height: 500px;"></div> 
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</center>

@endsection
@section('footer')
    @parent
    <meta name="_token" content="{!! csrf_token() !!}" />
    <meta name="_urlTarget" content="{{url('regionproductochart')}}"/>
    <meta name="_ideProyectoRegion" content="{{$ideProyectoRegion}}"/>
    <script src="{{asset('js/hpme.planificacion.consolidado.js')}}"></script>
    <script src="{{asset('js/hpme.monitoreo.region.ejecucion.js')}}"></script>   
<!--        <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.planificacion.js')}}"></script>-->
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection