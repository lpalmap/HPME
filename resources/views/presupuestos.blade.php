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
                        <h2>Presupuesto por Departamento</h2>
                    </div>
                </div>

                <hr />
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Departamentos {{isset($proyecto)?$proyecto:''}}</span>
                            <div style="float: right"><span style="font-weight: bolder;">{{isset($estado)?$estado:''}}</span></div>
                        </div>
                        
                         <div class="panel-body">
                             @if(isset($regiones))
<!--                                       <a href="{{url('planconsolidado/'.$ideProyectoPresupuesto)}}" >
                                                <img src="{{asset('images/consolidado.png')}}" class="menu-imagen-big" alt="" title="Ver planificac&oacute;n consolidada"/></a>-->
                                                &nbsp;
                                                &nbsp;
                                @if($estado=='PUBLICADO')                
                                <button id="btnCerrar" value="{{$ideProyectoPresupuesto}}">                      
                                <img src="{{asset('images/plan_cerrar.png')}}" class="menu-imagen-big" value="{{$ideProyectoPresupuesto}}" title="Cerrar Presupuesto"/></button>
                                @endif
                             <div class="table-responsive" id="tableContent">
                                 
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">Departamento</th>
                                            <th style="text-align: center">Fecha Ingreso</th>
                                            <th style="text-align: center">Fecha Aprobaci&oacute;n</th>
                                            <th style="text-align: center">Estado</th>
                                            <th style="text-align: center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @for ($i=0;$i<count($regiones);$i++)
                                        @if($regiones[$i]->estado=='ENVIADO')
                                            <tr class="danger" style="text-align: center" >
                                        @else
                                            @if($regiones[$i]->estado=='APROBADO')
                                            <tr class="success" style="text-align: center" >
                                            @else
                                            <tr class="info" style="text-align: center" >
                                            @endif
                                        @endif
                                                <td>{{$regiones[$i]->nombre}}</td>
                                                <td>{{$regiones[$i]->fecha_ingreso}}</td>
                                                <td>{{$regiones[$i]->fecha_aprobacion}}</td>
                                                <td>{{$regiones[$i]->estado}}</td>
                                                <td><a href="{{asset('presupuestodepartamento/'.($regiones[$i]->ide_presupuesto_departamento))}}" >
                                                <img src="{{asset('images/detail.png')}}" class="menu-imagen" alt="" title="Ver detalle presupuesto"/></a></td>
                                            </tr>
                                        @endfor
<!--                                        <tr class="active">
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
                                        </tr>-->
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <span>No hay presupuestos por departamento ingresados</span>
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
<div class="modal fade" id="cerrarPlanificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Cerrar Presupuesto</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="formCerrar">
                    <div class="form-group">
                        <label>Esta seguro de cerrar el presupuesto&quest;Lo directores no podr&aacute;n modificar la informaci&oacute;n ingresada.</label>
                    </div>                                   
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" tabindex="6" class="btn btn-primary" id="btnAceptarCerrar">Cerrar</button>
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
    <script src="{{asset('js/hpme.presupuesto.operaciones.js')}}"></script>
    <meta name="_urlTarget" content="{{url('presupuesto')}}" />
<!--        <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.planificacion.js')}}"></script>-->
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection