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
                        <h2>{{$proyecto}}/{{$region}}</h2>
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
                                                    <td>{{$items[$i]->periodo->fecha_habilitacion}}</td>
                                                    <td>{{$items[$i]->periodo->fecha_cierre}}</td>
                                                    <td><a href="{{url('/periodoregion/'.$items[$i]->ide_periodo_region)}}">{{$items[$i]->periodo->descripcion}}</a></td>
                                                    <td>{{$items[$i]->estado}}</td>
                                                    <td>
                                                        @if($items[$i]->estado=='ABIERTO')
                                                            <button class="btn btn-success btn-enviar" value="{{$items[$i]->ide_periodo_region}}"><i class="icon-arrow-up icon-white" ></i> Enviar a Revisi&oacute;n</a></button>
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
<div class="modal fade" id="enviarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Enviar ejecuci&oacute;n a revisi&oacute;n</h4>
                </div>
                <div class="modal-body">
               <form role="form" id="formEnviar">
                   <div class="form-group">
                       <p>
                           Esta seguro de enviar la ejecuci&oacute;n del periodo a revisi&oacute;n&quest; Ya no podr&aacute; modificar hasta que se le habilite. 
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
        <meta name="_urlTarget" content="{{url('periodoregion')}}"/>
        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.monitoreo.operaciones.js')}}"></script>
<!--        <script src="{{asset('js/hpme.monitoreo.proyectos.js')}}"></script>-->
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection