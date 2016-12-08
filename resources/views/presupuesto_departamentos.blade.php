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
                        <h2>Presupuesto</h2>
                    </div>
                </div>

                <hr />


<!--                <div class="row">-->
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Presupuesto por Departamento</span>
                        </div>
                        
                         <div class="panel-body">
                             <ul class="nav nav-pills">
                                <li class="active"><a href="{{url('presupuestos')}}">Presupuestos</a>
                                </li>
                                <li class="active"><a href="" data-toggle="tab">Departamentos</a>
                                </li>
                                
                            </ul>
                             <div class="table-responsive" id="tableContent">
                                 
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">Fecha Ingreso</th>
                                            <th style="text-align: center">Fecha Aprobaci&oacute;n</th>
                                            <th style="text-align: center">Departamento</th>
                                            <th style="text-align: center">Estado</th>
                                            <th style="text-align: center">Acciones</th>    
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($items))
                                            @for ($i=0;$i<count($items);$i++)
                                        <tr class="even gradeA" id="item{{$items[$i]->ide_presupuesto_departamento}}">
                                            <td style="text-align: center">{{$items[$i]->fecha_ingreso}}</td>
                                            <td style="text-align: center">{{$items[$i]->fecha_aprobacion}}</td>
                                            <td style="text-align: center"><a href="{{url('/departamento/'.$items[$i]->ide_presupuesto_departamento)}}">{{$items[$i]->nombre}}</a></td>
                                            <td style="text-align: center">{{$items[$i]->estado}}</td>
                                            <td style="text-align: center">
                                                @if($items[$i]->estado=='ABIERTO')
                                                    <button class="btn btn-success btn-enviar" value="{{$items[$i]->ide_presupuesto_departamento}}"><i class="icon-arrow-up icon-white" ></i> Enviar a Revisi&oacute;n</a></button>
                                                @endif
                                                <a href="{{url('presupuestodepartamento/'.$items[$i]->ide_presupuesto_departamento)}}" >
                                                <img src="{{asset('images/detail.png')}}" class="menu-imagen" alt="" title="Ver presupuesto departamento"/></a></td>
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
                    <h4 class="modal-title">Enviar presupuesto a revisi&oacute;n</h4>
                </div>
                <div class="modal-body">
               <form role="form" id="formEnviar">
                   <div class="form-group">
                       <p>
                           Esta seguro de enviar presupuesto a revisi&oacute;n&quest; Ya no podr&aacute; modificar hasta que se le habilite. 
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


@section('footer')
    @parent
        <meta name="_token" content="{!! csrf_token() !!}" />
        <meta name="_urlTarget" content="{{url('presupuestos')}}" />
        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.presupuesto.presupuestos.js')}}"></script>
@endsection