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
                        <h2> Usuarios</h2>
                    </div>
                </div>

                <hr />


                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <button class="btn btn-success" id="btnAgregar"><i class="icon-user icon-white" ></i> Agregar Usuario</button>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Rol</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($usuarios))
                                            @for ($i=0;$i<count($usuarios);$i++)
                                        <tr class="even gradeA" id="usuario{{$usuarios[$i]->ide_usuario}}">
                                            <td>{{$usuarios[$i]->usuario}}</td>
                                            <td>{{$usuarios[$i]->nombres}}</td>
                                            <td>{{$usuarios[$i]->apellidos}}</td>
                                            @if(count($usuarios[$i]->roles)>0)
                                            <td>{{$usuarios[$i]->roles[0]->nombre}}</td>
                                            @else
                                            <td></td>
                                            @endif
                                            <td>
                                                <button class="btn btn-primary btn-editar" value="{{$usuarios[$i]->ide_usuario}}"><i class="icon-pencil icon-white" ></i> Editar</button>
                                                <button class="btn btn-danger" value="{{$usuarios[$i]->ide_usuario}}"><i class="icon-remove icon-white"></i> Eliminar</button>
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
                                            <h4 class="modal-title" id="H1">Eliminar Usuario</h4>
                                        </div>
                                        <div class="modal-body">
                                            Esta seguro de eliminar el usuario.
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
                                                <label>Usuario</label>
                                                <input class="form-control" id="inUsuario" required="true"/>
                                            </div>
                                            <div class="form-group">
                                                <label id="lbPassword"></label>
                                                <input class="form-control" id="inPassword" required="true" type="password"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Nombres</label>
                                                <input class="form-control" id="inNombres" required="true"/>
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Apellidos</label>
                                                <input class="form-control" id="inApellidos" required="true"/>
                                            </div>
                                            @if (isset($roles))
                                            <div class="form-group">
                                                <label>Rol</label>
                                                
                                                    <select id="inRol" class="form-control">
                                                        <option value="0"></option>
                                                           @for ($i=0;$i<count($roles);$i++)
                                                               <option value="{{$roles[$i]->ide_rol}}">{{$roles[$i]->nombre}}</option>
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
        <script src="{{asset('js/hpme.usuarios.js')}}"></script>
@endsection