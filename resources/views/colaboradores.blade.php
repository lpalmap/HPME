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
                        <h2> Colaboradores/Proyectos</h2>
                    </div>
                </div>

                <hr />


                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <button class="btn btn-success" id="btnAgregar"><i class="icon-user icon-white" ></i> Agregar Colaborador</button>
                            <button class="btn btn-success" id="btnAgregarProyecto"><i class="icon-folder-open icon-white" ></i> Agregar Proyecto</button>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Nombre(s)</th>
                                            <th>Apellidos</th>
                                            <th>Puesto</th>
                                            <th>Departamento</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($colaboradores))
                                            @for ($i=0;$i<count($colaboradores);$i++)
                                        <tr class="even gradeA" id="usuario{{$colaboradores[$i]->ide_colaborador}}">
                                            <td>{{$colaboradores[$i]->tipo}}</td>
                                            <td>{{$colaboradores[$i]->nombres}}</td>
                                            <td>{{$colaboradores[$i]->apellidos}}</td>
                                            <td>{{isset($colaboradores[$i]->puesto)?$colaboradores[$i]->puesto->nombre:''}}</td>
                                            <td>{{$colaboradores[$i]->departamento->nombre}}</td>
                                            <td>
                                                <button class="btn btn-primary btn-editar {{$colaboradores[$i]->tipo=='Colaborador'?'btn-editar-colaborador':'btn-editar-proyecto'}}" value="{{$colaboradores[$i]->ide_colaborador}}"><i class="icon-pencil icon-white" ></i> Editar</button>
                                                <button class="btn btn-danger" value="{{$colaboradores[$i]->ide_colaborador}}"><i class="icon-remove icon-white"></i> Eliminar</button>
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
                        <div class="modal fade" id="buttonedModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="H1">Eliminar Colaborador/Proyecto</h4>
                                        </div>
                                        <div class="modal-body">
                                            Esta seguro de eliminar el colaborador/proyecto&quest;
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
                        <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="inputTitle"></h4>
                                        </div>
                                        <div class="modal-body">
                                            <form role="form" id="formAgregar">
                                            <div class="form-group">
                                                <label>Nombres</label>
                                                <input class="form-control" id="inNombres" required="true"/>
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Apellidos</label>
                                                <input class="form-control" id="inApellidos" required="true"/>
                                            </div>
                                            @if (isset($puestos))
                                            <div class="form-group">
                                                <label>Puesto</label>
                                                
                                                    <select id="inPuesto" class="form-control">
                                                        <option value="0"></option>
                                                           @for ($i=0;$i<count($puestos);$i++)
                                                               <option value="{{$puestos[$i]->ide_puesto}}">{{$puestos[$i]->nombre}}</option>
                                                           @endfor
                                                    </select>
                                                
                                            </div>                                            
                                            @endif
                                            @if (isset($departamentos))
                                            <div class="form-group">
                                                <label>Departamento</label>
                                                
                                                    <select id="inRol" class="form-control">
                                                        <option value="0"></option>
                                                           @for ($i=0;$i<count($departamentos);$i++)
                                                               <option value="{{$departamentos[$i]->ide_departamento}}">{{$departamentos[$i]->nombre}}</option>
                                                           @endfor
                                                    </select>
                                                
                                            </div>  
                                            @endif                                       
                                    </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
                                            <input type="hidden" id="ide_usuario" value="0"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                        <div class="modal fade" id="agregarProyecto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="inputTitleProyecto"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <form role="form" id="formAgregarProyecto">
                                                <div class="form-group">
                                                    <label>Nombre</label>
                                                    <input class="form-control" id="inNombreProyecto" required="true"/>
                                                </div>                                          
                                                @if (isset($departamentos))
                                                <div class="form-group">
                                                    <label>Departamento</label>

                                                        <select id="inDepartamentoProyecto" class="form-control">
                                                            <option value="0"></option>
                                                               @for ($i=0;$i<count($departamentos);$i++)
                                                                   <option value="{{$departamentos[$i]->ide_departamento}}">{{$departamentos[$i]->nombre}}</option>
                                                               @endfor
                                                        </select>

                                                </div>  
                                                @endif                                       
                                        </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                <button type="button" class="btn btn-primary" id="btnGuardarProyecto">Guardar</button>
                                                <input type="hidden" id="ide_col_proyecto" value="0"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
        <script src="{{asset('js/hpme.colaboradores.js')}}"></script>
@endsection