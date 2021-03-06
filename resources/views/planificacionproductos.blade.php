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
                        <h2>Proyectos de Planificaci&oacute;n Anual</h2>
                    </div>
                </div>

                <hr />


<!--                <div class="row">-->
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">{{$indicador}}</span>
                        </div>
                        
                         <div class="panel-body">
                            <ul class="nav nav-pills">
                                <li><a href="{{url('/proyecto')}}">Plantilla</a>
                                </li>
                                <li><a href="{{url('/plantilla/'.$ideProyecto)}}">Metas</a>
                                </li>
                                <li><a href="{{url('/meta/'.$ideProyectoMeta)}}">Objetivos</a>
                                </li>
                                <li><a href="{{url('/objetivo/'.$ideObjetivoMeta)}}">&Aacute;reas de Atenci&oacute;n</a>
                                </li>
                                <li><a href="{{url('/area/'.$ideAreaObjetivo)}}">Indicadores</a>
                                </li>
                                <li class="active"><a href="#profile-pills" data-toggle="tab">Productos</a>
                                </li>
                            </ul>
                             @if($creaPlanificacion)
                             <button class="btn btn-success" id="btnAgregar"><i class="icon-plus icon-white" ></i>Agregar Producto</button>
                             @endif
                             <div class="table-responsive" id="tableContent">
                                 
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">Producto</th>
                                            <th style="text-align: center">Orden</th>
                                            @if($creaPlanificacion)
                                            <th style="text-align: center">Acciones</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($items))
                                            @for ($i=0;$i<count($items);$i++)
                                        <tr class="even gradeA" id="item{{$items[$i]->ide_producto_indicador}}">
                                            @if($ingresaPlan)
                                            <td><button id="btn{{$items[$i]->ide_producto_indicador}}" class="btn2 btn {{in_array($items[$i]->ide_producto_indicador,$ingresados)?'btn-success':'btn-primary'}} btn-round" value="{{$items[$i]->ide_producto_indicador}}"  title="{{$items[$i]->producto->descripcion}}">{{$items[$i]->producto->nombre}}</button></td>
                                            @else
                                            <td><label>{{$items[$i]->producto->nombre}}</label></td>
                                            @endif
                                            <td>{{$items[$i]->producto->orden}}</td>
                                            @if($creaPlanificacion)
                                            <td>                     
                                                <button class="btn btn-danger" value="{{$items[$i]->ide_producto_indicador}}"><i class="icon-remove icon-white"></i> Eliminar</button>   
                                            </td>
                                            @endif
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
                                            <h4 class="modal-title" id="H1">Eliminar Producto</h4>
                                        </div>
                                        <div class="modal-body">
                                            Esta seguro de eliminar el producto se borrar&aacute; toda la planificaci&oacute;n asociada.
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



<div class="col-lg-12">
                        <div class="modal fade" id="ingresarDetalleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Planficaci&oacute;n</h4>
                                        </div>
                                        <div class="modal-body">
                                       <form role="form" id="formAgregarDetalle">
                                           
                                           <div class="form-group">
                                                   <table class="table table-striped table-bordered table-hover" id="dataTableItems2">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">Ene-Mar</th>
                                            <th style="text-align: center">Abr-Jun</th>
                                            <th style="text-align: center">Jul-Sep</th>
                                            <th style="text-align: center">Oct-Dic</th>
                                            <th style="text-align: center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items2" name="lista-items">                                      
                                        <tr class="even gradeA">
                                            <td>
                                                <input maxlength="8" tabindex="1" autofocus="true" align="right" class="form-control TabOnEnter" type="text" data-mask="999999" id="primerTrim" />  
                                            </td>
                                            <td>
                                                <input maxlength="8" tabindex="2" align="right" class="form-control TabOnEnter" type="text" data-mask="999999" id="segundoTrim" />
                                            </td>
                                            <td>
                                                <input maxlength="8" tabindex="3" align="right" class="form-control TabOnEnter" type="text" data-mask="999999" id="tercerTrim"/>
                                            </td>
                                            <td>
                                                <input maxlength="8" tabindex="4" align="right" class="form-control TabOnEnter" type="text" data-mask="999999" id="cuartoTrim"/>
                                            </td>
                                            <td>
                                                <input align="right" class="form-control" disabled="true"  value="0" id="totalInput"/>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                                
                                           </div>
                                                
                                           <div class="form-group">
                                                <label>Proyecto</label>
                                                    <select tabindex="5" id="inProyecto" class="form-control">                                                   
                                                    </select>                                              
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
        <meta name="_url" content="{{url('planproducto')}}"/>
        <meta name="_urlTarget" content="{{url('producto')}}"/>
        <meta name="_proyecto" content="{{$ideProyecto}}"/>
        <meta name="_proyectometa" content="{{$ideProyectoMeta}}"/>
        <meta name="_proyectoobjetivo" content="{{$ideObjetivoMeta}}"/>
        <meta name="_proyectoarea" content="{{$ideAreaObjetivo}}"/>
        <meta name="_proyectoindicador" content="{{$ideIndicadorArea}}"/>
        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.planificacion.productos.js')}}"></script>
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection