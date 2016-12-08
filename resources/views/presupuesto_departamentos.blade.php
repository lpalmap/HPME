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
                                            <td style="text-align: center"><a href="{{url('planconsolidado/'.$items[$i]->ide_presupuesto_departamento)}}" >
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
@section('footer')
    @parent
        <meta name="_token" content="{!! csrf_token() !!}" />
        <script src="{{asset('assets/plugins/dataTables/jquery.dataTables.js')}}"></script>
        <script src="{{asset('assets/plugins/dataTables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.presupuesto.presupuestos.js')}}"></script>
@endsection