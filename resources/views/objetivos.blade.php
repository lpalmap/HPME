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
                        <h2>Objetivos</h2>
                    </div>
                </div>

                <hr />


                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <button class="btn btn-success" id="btnAgregar"><i class="icon-plus icon-white" ></i> Agregar Objetivo</button>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripci&oacute;n</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($items))
                                            @for ($i=0;$i<count($items);$i++)
                                        <tr class="even gradeA" id="item{{$items[$i]->ide_objetivo}}">
                                            <td>{{$items[$i]->nombre}}</td>
                                            <td>{{$items[$i]->descripcion}}</td>
                                            <td>
                                                <button class="btn btn-primary btn-editar" value="{{$items[$i]->ide_objetivo}}"><i class="icon-pencil icon-white" ></i> Editar</button>
                                                <button class="btn btn-danger" value="{{$items[$i]->ide_objetivo}}"><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                            @endfor
                                        @endif
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
<div class="col-lg-12">
                        <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="H1">Eliminar Objetivo</h4>
                                        </div>
                                        <div class="modal-body">
                                            Esta seguro de eliminar el objetivo.
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
                                                <label>Nombre</label>
                                                <input class="form-control" id="inNombre" required="true"/>
                                                </div>
                                            <div class="form-group">
                                                <label>Descripci&oacute;n</label>
<!--                                                <input class="form-control" id="inDescripcion" required="true"/>-->
                                                <textarea class="form-control" id="inDescripcion" rows="3" style="width: 100%"></textarea>
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
        <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.objetivos.js')}}"></script>
@endsection