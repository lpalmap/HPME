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
                        <h2>Cuentas</h2>
                    </div>
                </div>

                <hr />


                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <button class="btn btn-success" id="btnAgregar"><i class="icon-plus icon-white" ></i> Agregar Cuenta</button>
                            <a class="btn btn-success btn-small btn-line" href="{{url('/cuentas')}}">Cuentas</a>
                            @for ($p=0;$p<count($parents);$p++)
                                &nbsp;>&nbsp;<a class="btn btn-success btn-small btn-line" href="{{url('/cuentas/'.($parents[$p]->ide_cuenta))}}">{{$parents[$p]->nombre}}</a>
                            @endfor                            
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>Cuenta</th>
                                            <th>Nombre</th>
                                            <th>Descripci&oacute;n</th>
                                            <th>Consolida</th>
                                            <th>Estado</th>
                                            <th>C&oacute;digo Interno</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($cuentas))
                                            @for ($i=0;$i<count($cuentas);$i++)
                                        <tr class="even gradeA" id="item{{$cuentas[$i]->ide_cuenta}}">
                                            <td>{{$cuentas[$i]->cuenta}}</td>
                                            <td><a href="{{url('/cuentas/'.($cuentas[$i]->ide_cuenta))}}">{{$cuentas[$i]->nombre}}</a></td>
                                            <td>{{$cuentas[$i]->descripcion}}</td>
                                            <td>{{$cuentas[$i]->ind_consolidar=='S'?'SI':'NO'}}</td>
                                            <td>{{$cuentas[$i]->estado=='ACTIVA'?'Activa':'Inactiva'}}</td>
                                            <td>{{$cuentas[$i]->codigo_interno}}</td>
                                            <td>
                                                <button class="btn btn-primary btn-editar" value="{{$cuentas[$i]->ide_cuenta}}"><i class="icon-pencil icon-white" ></i> Editar</button>
                                                <button class="btn btn-danger" value="{{$cuentas[$i]->ide_cuenta}}"><i class="icon-remove icon-white"></i> Eliminar</button>
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
                                            <h4 class="modal-title" id="H1">Eliminar Cuenta</h4>
                                        </div>
                                        <div class="modal-body">
                                            Esta seguro de eliminar la cuenta.
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
                                                <label>#Cuenta</label>
                                                <input class="form-control" id="inCuenta" required="true"/>
                                            </div>
                                           <div class="form-group">
                                                <label>Nombre</label>
                                                <input class="form-control" id="inNombre" required="true"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Descripci&oacute;n</label>
<!--                                                <input class="form-control" id="inDescripcion" required="true"/>-->
                                                <textarea class="form-control" id="inDescripcion" rows="3" style="width: 100%"></textarea>
                                             </div>
                                           <div class="form-group">
                                               <label>Consolidar hasta &eacute;ste nivel</label>
<!--                                                <input class="form-control" id="inDescripcion" required="true"/>-->
                                                <input class="uniform" type="checkbox" id="inConsolidar"/>
                                           </div>
                                           <div class="form-group">
                                               <label>Estado</label>
<!--                                                <input class="form-control" id="inDescripcion" required="true"/>-->
                                                <select id="inEstado" class="form-control">
                                                    <option value="ACTIVA" id="inActiva">Activa</option>
                                                    <option value="INACTIVA" id="inInactiva">Inactiva</option>
                                                </select>
                                           </div>  
                                           <div class="form-group">
                                               <label>C&oacute;digo Interno</label>
                                                <input class="form-control" id="inCodigo" required="true"/>
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
        <meta name="_url" content="{{url('cuenta')}}" />
        <meta name="_urlTarget" content="{{url('cuentas')}}" />
        <meta name="_cuentaPadre" content="{{$ideCuentaPadre}}" />
        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.cuentas.js')}}"></script>
@endsection