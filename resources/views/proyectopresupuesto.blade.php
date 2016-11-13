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
                            <span style="font-weight: bold">Proyectos de Presupuesto</span>
                        </div>
                        
                         <div class="panel-body">
                             <ul class="nav nav-pills">
                                <li class="active"><a href="#tableContent" data-toggle="tab">Presupuestos</a>
                                </li>
                            </ul>
                             <div class="table-responsive" id="tableContent">
                                 
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Fecha Cierre</th>
                                            <th>Descripci&oacute;n</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($items))
                                            @for ($i=0;$i<count($items);$i++)
                                        <tr class="even gradeA" id="item{{$items[$i]->ide_proyecto}}">
                                            <td>{{$items[$i]->fecha_proyecto}}</td>
                                            <td>{{$items[$i]->fecha_cierre}}</td>
                                            <td><a href="{{url('/presupuestos/'.$items[$i]->ide_proyecto_presupuesto)}}">{{$items[$i]->descripcion}}</a></td>
                                            <td>{{$items[$i]->estado}}</td>
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