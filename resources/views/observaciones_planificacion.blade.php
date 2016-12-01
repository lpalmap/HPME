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
                        <h2>Observaciones <span style="font-weight: bolder;">{{$nombreProyecto}}/{{$nombreRegion}}</span></h2>
                    </div>
                </div>

                <hr />
                <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span style="font-weight: bold">
                                <a href="{{url('planificaciones')}}" >
                                    <img src="{{asset('images/add_mail.png')}}" class="menu-imagen-big" alt="" title="Agregar Observaci&oacute;n"/></a>
                            </span>
                        </div>
                        
                         <div class="panel-body">
                                                           <ul class="timeline">
                                    <li>
                                        <div class="timeline-badge danger">
                                            <i class="icon-envelope-alt"></i>
                                        </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">Jorge Chavez(jchavez)</h4>
                                            </div>
                                            <div class="timeline-body">
                                                <p>Revisar planificaci&oacute;n ingresada para el producto estufa en la meta Impacto en la comunidad </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="timeline-inverted">
                                        <div class="timeline-badge success">
                                            <i class="icon-envelope-alt"></i>
                                        </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">Rudy Waldemar Cruz(rcruz)</h4>
                                            </div>
                                            <div class="timeline-body">
                                                <p>Se cambio el monto ingresado en la planificaci&oacute;n</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="timeline-badge danger">
                                            <i class="icon-envelope-alt"></i>
                                        </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">Jorge Chavez(jchavez)</h4>
                                            </div>
                                            <div class="timeline-body">
                                                <p>Corregir la asignaci&oacute;n el producto Letrina debe ir en otro proyecto</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="timeline-inverted">
                                        <div class="timeline-badge success">
                                            <i class="icon-envelope-alt"></i>
                                        </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">Rudy Waldemar Cruz(rcruz)</h4>
                                            </div>
                                            <div class="timeline-body">
                                                <p>Se cambio el producto al proyeto GT10101</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="timeline-inverted">
                                        <div class="timeline-badge success">
                                            <i class="icon-envelope-alt"></i>
                                        </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">Rudy Waldemar Cruz(rcruz)</h4>
                                            </div>
                                            <div class="timeline-body">
                                                <p>Correcci&oacute;n se cambio al proyecto GT10102</p>
                                            </div>
                                        </div>
                                    </li>
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
    <meta name="_urlTarget" content="{{url('area')}}"/>
<!--        <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.planificacion.js')}}"></script>-->
<!--        <script src="{{asset('js/hpme.proyectos.js')}}"></script>-->
@endsection
