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
                        <h2>Ejecuci&oacute;n Presupuesto/{{$nombre}}</h2>
                    </div>
                </div>

                <hr />
                <div class="row">
                <div class="col-lg-12">
                    @if($vistaGeneral)
                        <a href="{{url('presupuestosdepartamento')}}" >
                        <img src="{{asset('images/back.png')}}" class="menu-imagen-big" alt="" title="Atr&aacute;s"/></a>
                    @else
                        <a href="{{url('departamento/'.$idePresupuestoDepartamento)}}" >
                        <img src="{{asset('images/back.png')}}" class="menu-imagen-big" alt="" title="Atr&aacute;s"/></a>   
                    @endif
                    
<!--                                                <button class="btn btn-success" id="btnAgregar"><i class="icon-plus icon-white" ></i> Ver consolidado</button>-->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">Detalle presupuesto</span><a href="{{url('presupuestodepartamentotrim/'.$idePresupuestoDepartamento)}}" >
                        <img src="{{asset('images/quarter.png')}}" class="menu-imagen-big" alt="" title="Ver por trimestre"/></a>
                            <div style="float: right"><span style="font-weight: bolder;">{{$estado}}</span></div>                            
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
                                            <th colspan="2">Enero</th>
                                            <th colspan="2">Febrero</th>
                                            <th colspan="2">Marzo</th>
                                            <th colspan="2">Abril</th>
                                            <th colspan="2">Mayo</th>
                                            <th colspan="2">Junio</th>
                                            <th colspan="2">Julio</th>
                                            <th colspan="2">Agosto</th>
                                            <th colspan="2">Septiembre</th>
                                            <th colspan="2">Octubre</th>
                                            <th colspan="2">Noviembre</th>
                                            <th colspan="2" >Diciembre</th>
                                            <th colspan="2">Total</th>
                                        </tr>
                                         <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
                                            <th>Plan</th>
                                            <th>Ejec</th>
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
                                                <td style="font-weight: bolder">{{isset($cuentas[$i]['porc'])?number_format($cuentas[$i]['porc'],2):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item1'])?number_format($cuentas[$i]['item1'],(fmod($cuentas[$i]['item1'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ej1'])?number_format($cuentas[$i]['ej1'],(fmod($cuentas[$i]['ej1'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item2'])?number_format($cuentas[$i]['item2'],(fmod($cuentas[$i]['item2'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ej2'])?number_format($cuentas[$i]['ej2'],(fmod($cuentas[$i]['ej2'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item3'])?number_format($cuentas[$i]['item3'],(fmod($cuentas[$i]['item3'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ej3'])?number_format($cuentas[$i]['ej3'],(fmod($cuentas[$i]['ej3'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item4'])?number_format($cuentas[$i]['item4'],(fmod($cuentas[$i]['item4'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ej4'])?number_format($cuentas[$i]['ej4'],(fmod($cuentas[$i]['ej4'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item5'])?number_format($cuentas[$i]['item5'],(fmod($cuentas[$i]['item5'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ej5'])?number_format($cuentas[$i]['ej5'],(fmod($cuentas[$i]['ej5'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item6'])?number_format($cuentas[$i]['item6'],(fmod($cuentas[$i]['item6'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ej6'])?number_format($cuentas[$i]['ej6'],(fmod($cuentas[$i]['ej6'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item7'])?number_format($cuentas[$i]['item7'],(fmod($cuentas[$i]['item7'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ej7'])?number_format($cuentas[$i]['ej7'],(fmod($cuentas[$i]['ej7'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item8'])?number_format($cuentas[$i]['item8'],(fmod($cuentas[$i]['item8'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ej8'])?number_format($cuentas[$i]['ej8'],(fmod($cuentas[$i]['ej8'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item9'])?number_format($cuentas[$i]['item9'],(fmod($cuentas[$i]['item9'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ej9'])?number_format($cuentas[$i]['ej9'],(fmod($cuentas[$i]['ej9'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item10'])?number_format($cuentas[$i]['item10'],(fmod($cuentas[$i]['item10'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ej10'])?number_format($cuentas[$i]['ej10'],(fmod($cuentas[$i]['ej10'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item11'])?number_format($cuentas[$i]['item11'],(fmod($cuentas[$i]['item11'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ej11'])?number_format($cuentas[$i]['ej11'],(fmod($cuentas[$i]['ej11'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['item12'])?number_format($cuentas[$i]['item12'],(fmod($cuentas[$i]['item12'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ej12'])?number_format($cuentas[$i]['ej12'],(fmod($cuentas[$i]['ej12'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td style="font-weight: bolder">{{isset($cuentas[$i]['total'])?number_format($cuentas[$i]['total'],(fmod($cuentas[$i]['total'], 1) !== 0.00)?2:0):'0'}}</td>
                                                <td>{{isset($cuentas[$i]['ejtotal'])?number_format($cuentas[$i]['ejtotal'],(fmod($cuentas[$i]['ejtotal'], 1) !== 0.00)?2:0):'0'}}</td>
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
<div class="modal fade" id="aprobarPlanificacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Aprobar Presupuesto Departamento</h4>
            </div>
            <div class="modal-body">
                <form role="form" id="formAprobar">
                    <div class="form-group">
                        <label>Esta seguro de aprobar el presupuesto para el departamento&quest;</label>
                    </div>                                   
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" tabindex="6" class="btn btn-primary" id="btnAprobar">Aprobar</button>
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
     <meta name="_urlTarget" content="{{url('presupuestos')}}"/>
     <meta name="_idePresupuestoDepartamento" content="{{$idePresupuestoDepartamento}}"/>
     <script src="{{asset('js/hpme.presupuesto.consolidado.js')}}"></script>
<!--        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>-->
<!--        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>-->
<!--         <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>-->
<!--        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>-->
<!--        <script src="{{asset('assets/plugins/dataTables/jquery.freezeheader.js')}}"></script>-->
<!--        <script src="{{asset('js/hpme.lang.js')}}"></script>-->
<!--        <script src="{{asset('js/hpme.presupuesto.consolidado.js')}}"></script>-->
@endsection