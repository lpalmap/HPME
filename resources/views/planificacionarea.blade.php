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
                            <span style="font-weight: bold">{{$objetivo}}</span>
                        </div>
                        
                         <div class="panel-body">
                            <ul class="nav nav-pills">
                                <li><a href="{{url('/proyecto')}}">Plantilla</a>
                                </li>
                                <li><a href="{{url('/plantilla/'.$ideProyecto)}}">Metas</a>
                                </li>
                                <li><a href="{{url('/meta/'.$ideProyectoMeta)}}">Objetivos</a>
                                </li>
                                <li class="active"><a href="#profile-pills" data-toggle="tab">&Aacute;reas de Atenci&oacute;n</a>
                                </li>
                            </ul>
                             @if($creaPlanificacion)
                             <button class="btn btn-success" id="btnAgregar"><i class="icon-plus icon-white" ></i>Agregar &Aacute;rea</button>
                             @endif
                             <div class="table-responsive" id="tableContent">
                                 
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>&Aacute;rea de Atenci&oacute;n</th>
                                            <th>Orden</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($items))
                                            @for ($i=0;$i<count($items);$i++)
                                        <tr class="even gradeA" id="item{{$items[$i]->ide_area_objetivo}}">
                                            <td><a href="{{url('/area/'.$items[$i]->ide_area_objetivo)}}" title="{{$items[$i]->area->descripcion}}">{{$items[$i]->area->nombre}}</a></td>
                                            <td>{{$items[$i]->area->orden}}</td>
                                            <td>
                                                @if($creaPlanificacion)
                                                <button class="btn btn-danger" value="{{$items[$i]->ide_area_objetivo}}"><i class="icon-remove icon-white"></i> Eliminar</button>
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
                                            <h4 class="modal-title" id="H1">Eliminar &Aacute;rea de Atenci&oacute;n</h4>
                                        </div>
                                        <div class="modal-body">
                                            Esta seguro de eliminar el &Aacute;rea de Atenci&oacute;n se borrar&aacute; toda la planificaci&oacute;n asociada.
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
        <meta name="_url" content="{{url('planarea')}}"/>
        <meta name="_urlTarget" content="{{url('area')}}"/>
        <meta name="_proyecto" content="{{$ideProyecto}}"/>
        <meta name="_proyectometa" content="{{$ideProyectoMeta}}"/>
        <meta name="_proyectoobjetivo" content="{{$ideObjetivoMeta}}"/>
        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.planificacion.areas.js')}}"></script>
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection