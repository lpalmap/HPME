@extends('layouts.master')
@section('content')
<!--PAGE CONTENT -->
        <div id="content">

            <div class="inner">
                <div class="row">
                    <div class="col-lg-12">
                        <h2> Perfil</h2>
                    </div>
                </div>

                <hr />


                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <button class="btn btn-success" id="btnActualizar"><i class="icon-user icon-white" ></i> Actualizar Datos</button>
                        </div>
                        <div class="panel-body">
                                            <form role="form" id="formAgregar">
                                            <div class="form-group">
                                                <label>Usuario</label>
                                                <input class="form-control" id="inUsuario" required="true" readonly="true" value="{{$usuario->usuario}}"/>
                                            </div>
                                            <div class="form-group">
                                                <label id="lbPassword">Contrase&ntilde;a (*Dejar en blanco si no desea modificar)</label>
                                                <input class="form-control" id="inPassword" required="true" type="password" autocomplete="false"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Nombres</label>
                                                <input class="form-control" id="inNombres" required="true" value="{{$usuario->nombres}}"/>
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Apellidos</label>
                                                <input class="form-control" id="inApellidos" required="true" value="{{$usuario->apellidos}}"/>
                                            </div>
                                                <div class="form-group">
                                                <label>Email</label>
                                                <input class="form-control" id="inEmail" required="true" placeholder="Email" value="{{$usuario->email}}"/>
                                            </div>
                                            @if (count($usuario->roles)>0)
                                            <div class="form-group">
                                                <label>Rol</label>
                                                <input class="form-control" id="inRol" required="true" readonly="true" value="{{$usuario->roles[0]->nombre}}"/>                                                
                                            </div>  
                                            @endif                                       
                                    </form>                           
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
                        <div class="modal fade" id="confirmarModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="H1">Confirmar</h4>
                                        </div>
                                        <div class="modal-body">
                                            Desea guardar los cambios realizados.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
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
        <meta name="_urlTarget" content="{{url('perfil/update')}}" />
        <script src="{{asset('js/hpme.perfil.js')}}"></script>
@endsection