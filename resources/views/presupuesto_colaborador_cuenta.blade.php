@extends('layouts.master')
@section('globalStyles')
    @parent
        <!-- PAGE LEVEL STYLES -->
    <link href="{{asset('assets/plugins/dataTables/dataTables.bootstrap.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/theme.css')}}" rel="stylesheet" />
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
                            <ul class="nav nav-pills">
                                <li class="active"><a href="{{url('presupuestos')}}">Presupuestos</a>
                                </li>
                                <li class="active"><a href="{{url('presupuestos/'.$ideProyectoPresupuesto)}}">Departamentos</a>
                                </li>
                                <li class="active"><a href="{{url('departamento/'.$idePresupuestoDepartamento)}}">Colaboradores/Proyectos</a>
                                </li>
                                <li class="active"><a href="" data-toggle="tab">{{$nombreColaborador}}</a>
                            </ul>
                            <hr />
                            <a class="btn btn-success btn-small btn-line" href="{{url('/colaborador/'.$idePresupuestoColaborador.'/cuenta')}}">Cuentas</a>
                            @for ($p=0;$p<count($parents);$p++)
                                &nbsp;>&nbsp;<a class="btn btn-success btn-small btn-line" href="{{url('/colaborador/'.$idePresupuestoColaborador.'/cuenta/'.($parents[$p]->ide_cuenta))}}">{{$parents[$p]->nombre}}</a>
                            @endfor                            
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">Cuenta</th>
                                            <th style="text-align: center">Nombre</th>
                                            <th style="text-align: center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($cuentas))
                                            @for ($i=0;$i<count($cuentas);$i++)
                                        <tr class="even gradeA" id="item{{$cuentas[$i]->ide_cuenta}}">
                                            <td style="text-align: center;font-weight: bolder">{{$cuentas[$i]->cuenta}}</td>
                                            @if(!isset($cuentas[$i]->hijas) || $cuentas[$i]->hijas>0)
                                            <td class="table-center"><a title="{{$cuentas[$i]->descripcion}}" href="{{url('/colaborador/'.$idePresupuestoColaborador.'/cuenta/'.($cuentas[$i]->ide_cuenta))}}">{{$cuentas[$i]->nombre}}</a></td>
                                            @else
                                            <td><button class="btn2 btn {{in_array($cuentas[$i]->ide_cuenta,$ingresadas)?'btn-success':'btn-primary'}} btn-round btn-cuenta" id="btn{{$cuentas[$i]->ide_cuenta}}" value="{{$cuentas[$i]->ide_cuenta}}"  title="{{$cuentas[$i]->descripcion}}">{{$cuentas[$i]->nombre}}</button></td>
                                            @endif
                                            <td class="table-center">
<!--                                                <button class="btn btn-primary btn-editar" value="{{$cuentas[$i]->ide_cuenta}}"><i class="icon-pencil icon-white" ></i> Editar</button>
                                                <button class="btn btn-danger" value="{{$cuentas[$i]->ide_cuenta}}"><i class="icon-remove icon-white"></i> Eliminar</button>-->
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
                        <div class="modal fade" id="agregarEditarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="inputTitle"></h4>
                                        </div>
                                        <div class="modal-body">
                                       <form role="form" id="formAgregar">
                                           <div><span style="font-weight: bolder">Monto:</span><input id="itemReplicar" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/>&nbsp;&nbsp;<span>Replicar monto en todos los meses</span><input type="checkbox" id="replicar" value="1"/></div>
                                           <hr/>
                                           <table class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center">Trimestre</th>
                                                        <th style="text-align: center">Mes</th>
                                                        <th style="text-align: center">Monto</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td rowspan="3" style="vertical-align: middle">1er. Trimestre</td>
                                                        <td style="text-align: left"><div>&nbsp;&nbsp;<input class="ck-action" type="checkbox" name="ckItem" value="1"/><span>Enero</span></div></td>
                                                        <td><input class="input-action" id="itemVal1" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left"><div>&nbsp;&nbsp;<input class="ck-action" type="checkbox" name="ckItem" value="2"/><span>Febrero</span></div></td>
                                                        <td><input class="input-action" id="itemVal2" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left"><div>&nbsp;&nbsp;<input class="ck-action" type="checkbox" name="ckItem" value="3"/><span>Marzo</span></div></td>
                                                        <td><input class="input-action" id="itemVal3" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td rowspan="3" style="vertical-align: middle">2do. Trimestre</td>
                                                        <td style="text-align: left"><div>&nbsp;&nbsp;<input class="ck-action" type="checkbox" name="ckItem" value="4"/><span>Abril</span></div></td>
                                                        <td><input class="input-action" id="itemVal4" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left"><div>&nbsp;&nbsp;<input class="ck-action" type="checkbox" name="ckItem" value="5"/><span>Mayo</span></div></td>
                                                        <td><input class="input-action" id="itemVal5" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left"><div>&nbsp;&nbsp;<input class="ck-action" type="checkbox" name="ckItem" value="6"/><span>Junio</span></div></td>
                                                        <td><input class="input-action" id="itemVal6" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td rowspan="3" style="vertical-align: middle">3er. Trimestre</td>
                                                        <td style="text-align: left"><div>&nbsp;&nbsp;<input class="ck-action"type="checkbox" name="ckItem" value="7"/><span>Julio</span></div></td>
                                                        <td><input class="input-action" id="itemVal7" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left"><div>&nbsp;&nbsp;<input class="ck-action" type="checkbox" name="ckItem" value="8"/><span>Agosto</span></div></td>
                                                        <td><input class="input-action" id="itemVal8" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left"><div>&nbsp;&nbsp;<input class="ck-action" type="checkbox" name="ckItem" value="9"/><span>Septiembre</span></div></td>
                                                        <td><input class="input-action" id="itemVal9" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td rowspan="3" style="vertical-align: middle">4to. Trimestre</td>
                                                        <td style="text-align: left"><div>&nbsp;&nbsp;<input class="ck-action" type="checkbox" name="ckItem" value="10"/><span>Octubre</span></div></td>
                                                        <td><input class="input-action" id="itemVal10" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left"><div>&nbsp;&nbsp;<input class="ck-action" type="checkbox" name="ckItem" value="11"/><span>Noviembre</span></div></td>
                                                        <td><input class="input-action" id="itemVal11" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: left"><div>&nbsp;&nbsp;<input class="ck-action" type="checkbox" name="ckItem" value="12"/><span>Diciembre</span></div></td>
                                                        <td><input class="input-action" id="itemVal12" maxlength="12" style="text-align: right;width: 100px;"  type="text" value=""/></td>
                                                    </tr>
                                                    <tr style="font-weight: bolder">
<!--                                                        <td></td>-->
                                                        <td colspan="2" style="text-align: right">Total</td>
                                                        <td><input id="total" disabled="true" maxlength="13" style="text-align: right;width: 110px;"  type="text" value=""/></td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
        <meta name="_presupuestoColaborador" content="{{$idePresupuestoColaborador}}" />
        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.presupuesto.cuentas.js')}}"></script>
@endsection