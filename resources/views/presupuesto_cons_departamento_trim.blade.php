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
                        <h2>Presupuesto/{{$nombre}}</h2>
                    </div>
                </div>

                <hr />
                <div class="row">
                <div class="col-lg-12">
                    <a href="{{url('presupuestodepartamento/'.$idePresupuestoDepartamento)}}" >
                        <img src="{{asset('images/back.png')}}" class="menu-imagen-big" alt="" title="Atr&aacute;s"/></a>
<!--                                                <button class="btn btn-success" id="btnAgregar"><i class="icon-plus icon-white" ></i> Ver consolidado</button>-->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Detalle presupuesto trimestral</span>
                        </div>
                        
                         <div class="panel-body">
                             @if(isset($cuentas))                         
                             <div class="table-responsive" id="tableContent">
                                 
                                 <table class="tbl table table-bordered table-hover table-buget" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center"></th>
                                            <th style="text-align: center"></th>
                                            <th>%</th>
                                            <th style="text-align: center">Enero-Marzo</th>
                                            <th style="text-align: center">Abril-Junio</th>
                                            <th style="text-align: center">Julio-Septiembre</th>
                                            <th style="text-align: center">Octubre-Diciembre</th>
                                            <th style="text-align: center">Total</th>
                                        </tr>
                                    </thead>
                                        <tbody id="lista-items" name="lista-items">
                                        @for ($i=0;$i<count($cuentas);$i++)
                                            @if(isset($cuentas[$i]['nivel']))
                                                @if($cuentas[$i]['nivel']==0)
                                                    <tr class="success" style="text-align: center" >
                                                @else
                                                    @if($cuentas[$i]['nivel']==1)
                                                        <tr class="warning" style="text-align: center" >
                                                    @else
                                                        <tr class="info" style="text-align: center" >
                                                    @endif
                                                @endif
                                                
                                            @else
                                                <tr class="info" style="text-align: center" >
                                            @endif                                           
                                                @if(is_null($cuentas[$i]['cuenta']) || strlen($cuentas[$i]['cuenta'])==0)
                                                <td style="text-align: center">{{$cuentas[$i]['nombre']}}</td>
                                                <td></td>
                                                @else
                                                <td style="text-align: center">{{$cuentas[$i]['cuenta']}}</td>
                                                <td style="text-align: center">{{$cuentas[$i]['nombre']}}</td>
                                                @endif
                                                <td></td>
                                                <td>{{isset($cuentas[$i]['item1'])?$cuentas[$i]['item1']:'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item2'])?$cuentas[$i]['item2']:'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item3'])?$cuentas[$i]['item3']:'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item4'])?$cuentas[$i]['item4']:'0'}}</td>
                                                <td style="font-weight: bolder">{{isset($cuentas[$i]['total'])?$cuentas[$i]['total']:'0'}}</td>
                                            </tr>
                                        @endfor    
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <span>No se ingreso el presupuesto por cuentas.</span>
                            @endif                          
                        </div>
                        </div>
                        </div>
                        </div>    
            </div>
        </div>
       <!--END PAGE CONTENT -->
@endsection
@section('outsidewraper')

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
<!--        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>-->
<!--        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>-->
<!--         <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>-->
<!--        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>-->
<!--        <script src="{{asset('assets/plugins/dataTables/jquery.freezeheader.js')}}"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.presupuesto.consolidado.js')}}"></script>-->
@endsection