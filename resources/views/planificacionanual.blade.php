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


<!--                <div class="row">-->
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Plantillas de Planificaci&oacute;n</span>
                        </div>
                        
                         <div class="panel-body">
                             <ul class="nav nav-pills">
                                <li class="active"><a href="#tableContent" data-toggle="tab">Plantillas</a>
                                </li>
                            </ul>
                             @if($creaPlanificacion)
                             <button class="btn btn-success" id="btnAgregar"><i class="icon-plus icon-white" ></i>Nueva Plantilla</button>
                             @endif
                             <div class="table-responsive" id="tableContent">
                                 
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Fecha Cierre</th>
                                            <th>Descripci&oacute;n</th>
                                            <th>Periodicidad</th>
                                            <th>Tasa</th>
                                            <th>Estado Plantilla</th>
                                            @if($ingresaPlan)
                                                <th>Estado Planificaci&oacute;n</th>
                                            @else
                                            <th>Acciones</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($items))
                                            @for ($i=0;$i<count($items);$i++)
                                        <tr class="even gradeA" id="item{{$items[$i]->ide_proyecto}}">
                                            <td>{{$items[$i]->fecha_proyecto}}</td>
                                            <td>{{$items[$i]->fecha_cierre}}</td>
                                            <td><a href="{{url('/plantilla/'.$items[$i]->ide_proyecto)}}">{{$items[$i]->descripcion}}</a></td>
                                            <td>{{$items[$i]->periodicidad->descripcion}}</td>
                                            <td>{{$items[$i]->tasa}}</td>
                                            <td>{{$items[$i]->estado}}</td>
                                            <td>
                                                @if($apruebaPlanificacion && $items[$i]->estado=='ABIERTO')
                                                <button class="btn btn-success btn-publicar" value="{{$items[$i]->ide_proyecto}}"><i class="icon-arrow-up icon-white" ></i> Publicar</button>
                                                <button class="btn btn-primary btn-editar" value="{{$items[$i]->ide_proyecto}}"><i class="icon-pencil icon-white" ></i> Editar</button>
                                                <button class="btn btn-danger" value="{{$items[$i]->ide_proyecto}}"><i class="icon-remove icon-white"></i> Eliminar</button>
                                                @endif
                                                @if($ingresaPlan && $items[$i]->estado!=='ABIERTO')
                                                <a href="{{asset('proyectodetalle/'.($items[$i]->ide_proyecto))}}">
                                                        <img src="{{asset('images/detail.png')}}" class="menu-imagen" alt="" title="Ver detalle planificaci&oacute;n"/></a>
                                                @endif
                                                @if($ingresaPlan && $items[$i]->estado=='PUBLICADO')
                                                    @if(isset($estadoRegion))                                                       
                                                        @if($estadoRegion=='ABIERTO')
                                                            <button class="btn btn-success btn-enviar" value="{{$items[$i]->ide_proyecto}}"><i class="icon-arrow-up icon-white" ></i> Enviar a Revisi&oacute;n</a></button>
                                                        @else
                                                            <p>{{$estadoRegion}}</p>
                                                        @endif
                                                    @endif                                                  
                                                @endif
                                            </td>
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
                                            <h4 class="modal-title" id="H1">Eliminar Plantilla</h4>
                                        </div>
                                        <div class="modal-body">
                                            Esta seguro de eliminar la plantilla.
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
                                            <div class="form-group">
                                                <label>Descripci&oacute;n</label>
                                                <input class="form-control" id="inDescripcion" required="true"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Periodicidad</label>
                                                @if (isset($periodos))
                                                    <select id="inPeriodo" class="form-control" value="5">
                                                           @for ($i=0;$i<count($periodos);$i++)
                                                               @if ($periodos[$i]->codigo_lista==='TRI')
                                                               <option value="{{$periodos[$i]->ide_lista}}" selected>{{$periodos[$i]->descripcion}}</option>
                                                               @else
                                                               <option value="{{$periodos[$i]->ide_lista}}">{{$periodos[$i]->descripcion}}</option>
                                                               @endif
                                                           @endfor
                                                    </select>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label>Tasa de Cambio</label>
                                                <input class="form-control" id="inTasa" required="true"/>
                                            </div>
                                            
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



@if($apruebaPlanificacion)
    <div class="modal fade" id="publicarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Publicar plantilla planificaci&oacute;n</h4>
                </div>
                <div class="modal-body">
               <form role="form" id="formPublicar">
                   <div class="form-group">
                       <p>
                           Esta seguro de publicar la plantilla de planificaci&oacute;n&quest; Ya no podr&aacute; modificar la plantilla. 
                       </p>
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnPublicar">Publicar</button>
                </div>
            </div>
        </div>
    </div>
@endif

@if($ingresaPlan)
    <div class="modal fade" id="enviarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Enviar planificaci&oacute;n a revisi&oacute;n</h4>
                </div>
                <div class="modal-body">
               <form role="form" id="formEnviar">
                   <div class="form-group">
                       <p>
                           Esta seguro de enviar la planificaci&oacute;n a revisi&oacute;n&quest; Ya no podr&aacute; modificar hasta que se le habilite. 
                       </p>
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnEnviar">Enviar</button>
                </div>
            </div>
        </div>
    </div>
@endif

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
        <meta name="_url" content="{{url('planproyecto')}}"/>
        <meta name="_urlTarget" content="{{url('plantilla')}}"/>
        <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.planificacion.js')}}"></script>
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection