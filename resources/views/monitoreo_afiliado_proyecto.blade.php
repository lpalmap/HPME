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
                        <h2>{{$proyecto}}</h2>
                    </div>
                </div>

                <hr />


<!--                <div class="row">-->
                <div class="row">
                <div class="col-lg-12">
                    <a href="{{url('monitoreoafiliado')}}">
                        <img src="{{asset('images/back.png')}}" class="menu-imagen" alt="" title="Atr&aacute;s"/></a>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Periodos Monitoreo</span>
                        </div>
                        
                         <div class="panel-body">
                             
                             <div class="table-responsive" id="tableContent">
                                 
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>Fecha Habilitaci&oacute;n</th>
                                            <th>Fecha Cierre</th>
                                            <th>Periodo</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($items))
                                            @for ($i=0;$i<count($items);$i++)
                                                <tr class="even gradeA" id="item{{$items[$i]->ide_periodo_monitoreo}}">
                                                    <td>{{$items[$i]->fecha_habilitacion}}</td>
                                                    <td>{{$items[$i]->fecha_cierre}}</td>
                                                    <td><a href="{{url('/monitoreoafiliadodetalle/'.$items[$i]->ide_periodo_monitoreo)}}">{{$items[$i]->descripcion}}</a></td>
                                                    <td>{{$items[$i]->estado}}</td>
                                                    @if($items[$i]->estado==='ABIERTO')
                                                    <td></td>
                                                    @else
                                                    <td><button type="button" class="btn btn-primary btn-habilitar" value="{{$items[$i]->ide_periodo_monitoreo}}">Habilitar</button></td>   
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
<div class="modal fade" id="iniciarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Iniciar Monitoreo</h4>
                </div>
                <div class="modal-body">
               <form role="form" id="formPublicar">
                   <div class="form-group">
                       <p>
                           Esta seguro iniciar el proceso de monitoreo del proyecto de planificaci&oacute;n&quest; 
                       </p>
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnIniciar" value="{{$ideProyecto}}">Iniciar</button>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="habilitarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Habilitar Monitoreo</h4>
                </div>
                <div class="modal-body">
               <form role="form">
                   <div class="form-group">
                       <p>
                           Esta seguro habilitar el periodo, se podr&aacute;n hacer modificaciones a los datos ingresados&quest; 
                       </p>
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnHabilitar">Habilitar</button>
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
        <meta name="_urlTarget" content="{{url('adminmonitoreo')}}"/>
        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
<!--        <script src="{{asset('js/hpme.monitoreo.proyectos.js')}}"></script>-->
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection