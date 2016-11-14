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
                        <h2>Presupuesto</h2>
                    </div>
                </div>

                <hr />


<!--                <div class="row">-->
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Presupuesto por Colaborador</span>
                        </div>
                        
                         <div class="panel-body">
                             <ul class="nav nav-pills">
                                <li class="active"><a href="{{url('presupuestos')}}">Presupuestos</a>
                                </li>
                                <li class="active"><a href="{{url('presupuestos/'.$ideProyectoPresupuesto)}}">Departamentos</a>
                                </li>
                                <li class="active"><a href="" data-toggle="tab">Colaboradores</a>
                                </li>
                            </ul>
                             <hr />
                             <button class="btn btn-success" id="btnAgregar"><i class="icon-user icon-white" ></i> Agregar Presupuesto Colaborador</button>
                             <div class="table-responsive" id="tableContent">
                                 
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">Fecha Ingreso</th>
                                            <th style="text-align: center">Colaborador</th>
                                            <th style="text-align: center">Acciones</th>    
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($items))
                                            @for ($i=0;$i<count($items);$i++)
                                        <tr class="even gradeA" id="item{{$items[$i]->ide_presupuesto_colaborador}}">
                                            <td style="text-align: center">{{$items[$i]->fecha_ingreso}}</td>
                                            <td style="text-align: center"><a href="{{url('/colaborador/'.$items[$i]->ide_presupuesto_colaborador.'/cuenta')}}">{{$items[$i]->nombres.' '.$items[$i]->apellidos}}</a></td>
                                            <td style="text-align: center">
                                                <button title="Eliminar" class="btn btn-danger btnEliminarItem" value="{{$items[$i]->ide_presupuesto_colaborador}}"><i class="icon-remove icon-white"></i></button>&nbsp;&nbsp;&nbsp;
                                                <a href="{{url('planconsolidado/'.$items[$i]->ide_presupuesto_colaborador)}}" >
                                                <img src="{{asset('images/consolidado.png')}}" class="menu-imagen" alt="" title="Ver resumen consolidado"/></a></td>
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
@section('outsidewraper')
                    <div class="col-lg-12">
                        <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="H1">Eliminar Presupuesto Colaborador</h4>
                                        </div>
                                        <div class="modal-body">
                                            Esta seguro de eliminar el presupuesto del colaborador.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="btnEliminar">Eliminar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
     
                        
                <div class="col-lg-12">
                        <div class="modal fade" id="agregarEditarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="inputTitle"></h4>
                                        </div>
                                        <div class="modal-body">
                                        <form role="form" id="formAgregar">
                                            <select id="inColaborador" class="form-control">
                                            </select>
                                        </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
                                            <input type="hidden" id="ide_item" value="0"/>
                                        </div>
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
        <meta name="_url" content="{{url('departamento')}}" />
        <meta name="_departamento" content="{{$idePresupuestoDepartamento}}" />
        <meta name="_urlTarget" content="{{url('colaborador')}}" />
        <meta name="_imgConsolidado" content="{{asset('images/consolidado.png')}}" />
        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.presupuesto.colaboradores.js')}}"></script>
@endsection