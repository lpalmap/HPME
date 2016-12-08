@extends('layouts.master')
@section('globalStyles')
    @parent
    <link rel="stylesheet" href="{{asset('assets/plugins/timeline/timeline.css')}}" />
        <!-- PAGE LEVEL STYLES -->
<!--    <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />-->
    <!-- END PAGE LEVEL  STYLES -->
@endsection
@section('content')
<!--PAGE CONTENT -->
        <div id="content">

            <div class="inner">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Observaciones <span style="font-weight: bolder;">{{$nombreProyecto}}/{{$nombre}}</span></h2>
                    </div>
                </div>

                <hr />
                <div class="row">
                <div class="col-lg-12">
                    
                    <a href="{{url('presupuestodepartamento/'.$idePresupuestoDepartamento)}}" >
                    <img src="{{asset('images/back.png')}}" class="menu-imagen-big" alt="" title="Atr&aacute;s"/></a>  
                    
                     
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">
                                @if($estado!='APROBADO')
                                <button id="btnAgregar" value="{{$idePresupuestoDepartamento}}">
                                    <img src="{{asset('images/add_mail.png')}}" class="menu-imagen-big" alt="" title="Agregar Observaci&oacute;n"/></button>
                                @endif
                                @if($rol=='DIRECTOR ADMIN Y FINANZAS' && !is_null($estadoBitacora) && $estadoBitacora=='ABIERTO')
                                <button id="btnCerrar" value="{{$idePresupuestoDepartamento}}">
                                    <img src="{{asset('images/ok.png')}}" class="menu-imagen-big" alt="" title="Marcar observaciones como resultas."/></button>
                                @endif
                            </span>
                        </div>
                        
                         <div class="panel-body">
                             <ul class="timeline" id="listaMensajes">
                                 @for ($i=0;$i<count($mensajes);$i++)
                                     @if($mensajes[$i]->ide_usuario==$usuario)
                                        <li>
                                        <div class="timeline-badge danger">
                                     @else
                                        <li class="timeline-inverted">
                                        <div class="timeline-badge success">
                                     @endif
                                     <i class="icon-envelope-alt"></i>
                                        </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">{{$mensajes[$i]->usuario->nombres.' '.$mensajes[$i]->usuario->apellidos.' ('.$mensajes[$i]->usuario->usuario.')'}}</h4>
                                            </div>
                                            <div class="timeline-body">
                                                <p>{{$mensajes[$i]->mensaje}}</p>
                                            </div>
                                        </div>
                                    </li>
                                 @endfor         
                                </ul>                         
                        </div>
                        </div>
                        </div>
                        </div>    
            </div>
        </div>
       <!--END PAGE CONTENT -->
@endsection
@section('outsidewraper')
<div class="modal fade" id="agregarObservacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Agregar Mensaje</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="formAgregar">
                    <div class="form-group">
                        <label>Mensaje</label>
                        <textarea class="form-control" id="inMensaje" rows="3" style="width: 100%"></textarea>
                    </div>                                   
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" tabindex="6" class="btn btn-primary" id="btnGuardar">Guardar</button>
                <input type="hidden" id="ide_item2" value="0"/>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cerrarObservacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Marcar Observaciones</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="formCerrar">
                    <div class="form-group">
                        <label>Esta seguro de marcar como resueltas las observaciones&quest;</label>
                    </div>                                   
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" tabindex="6" class="btn btn-primary" id="btnMarcar">Marcar como Resuelto</button>
                <input type="hidden" id="ide_item2" value="0"/>
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
    <meta name="_urlTarget" content="{{url('observacionespresupuesto')}}"/>
    <meta name="_usuario" content="{{$usuario}}"/>
<!--        <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.planificacion.js')}}"></script>-->
        <script src="{{asset('js/hpme.presupuesto.observaciones.js')}}"></script>
@endsection
