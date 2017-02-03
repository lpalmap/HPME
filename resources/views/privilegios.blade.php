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
                        <h2>Privilegios</h2>
                    </div>
                </div>

                <hr />


                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>Roles/Privilegios</h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripci&oacute;n</th>
                                            <th>Privilegios</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($items))
                                            @for ($i=0;$i<count($items);$i++)
                                        <tr class="even gradeA" id="item{{$items[$i]->ide_rol}}">
                                            <td>{{$items[$i]->nombre}}</td>
                                            <td>{{$items[$i]->descripcion}}</td>
                                            <td>
                                                <button  class="btn-editar" value="{{$items[$i]->ide_rol}}" title="Ver privilegios">
                                                <img src="{{asset('images/acceso.png')}}" class="menu-imagen" alt="" title="Ver/Modificar privilegios"/></button>
                                            </td>
                                        </tr>
                                            @endfor
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                           
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
                        <div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="H1">Eliminar Rol</h4>
                                        </div>
                                        <div class="modal-body">
                                            Esta seguro de eliminar el rol.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="btnEliminar">Eliminar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
     
                        
<div class="modal fade" id="agregarEditarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="inputTitle">Editar Privilegios</h4>
                                        </div>
                                        <div class="modal-body">
                                       <form role="form" id="formAgregar">
                                           <div class="form-group">
                                                <label>Nombre</label>
                                                <input class="form-control" id="inNombre" readonly="true"/>
                                                </div>
                                            <div class="form-group">
                                                <label>Descripci&oacute;n</label>
<!--                                                <input class="form-control" id="inDescripcion" required="true"/>-->
                                                <textarea class="form-control" id="inDescripcion" readonly="true" rows="4" style="width: 100%"></textarea>
                                                </div>
                                        </form>
                                                  
                                           <div class="row">
    <div class="col-lg-12">
        <div class="box">
            <header>
                <h5>Seleccionar Privilegios</h5>

                <div class="toolbar">
                    <ul class="nav pull-right">                       
                        <li>
                            <a class="accordion-toggle minimize-box" data-toggle="collapse" href="#div-3">
                                <i class="icon-chevron-up"></i>
                            </a>
                        </li>
                    </ul>
                </div>

            </header>
            <div id="div-3" class="accordion-body collapse in body">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group">
                            <div class="input-group">
                                <input id="box1Filter" type="text" placeholder="Filter" class="form-control" />
                                <span class="input-group-btn">
                                    <button id="box1Clear" class="btn btn-warning" type="button">x</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <select id="box1View" multiple="multiple" class="form-control" size="16">
                                <option value="501649">2008-2009 "Mini" Baja</option>
                                <option value="501497">AAPA - Asian American Psychological Association</option>
                                <option value="501053">Academy of Film Geeks</option>
                                <option value="500001">Accounting Association</option>
                                <option value="501227">ACLU</option>
                                <option value="501610">Active Minds</option>
                                <option value="501514">Activism with A Reel Edge (A.W.A.R.E.)</option>
                                <option value="501656">Adopt a Grandparent Program</option>
                                <option value="501050">Africa Awareness Student Organization</option>
                                <option value="501075">African Diasporic Cultural RC Interns</option>
                                <option value="501493">Agape</option>
                                <option value="501562">AGE-Alliance for Graduate Excellence</option>
                                <option value="500676">AICHE (American Inst of Chemical Engineers)</option>
                                <option value="501460">AIDS Sensitivity Awareness Project ASAP</option>
                                <option value="500004">Aikido Club</option>
                                <option value="500336">Akanke</option>
                            </select>
                            <hr>
                            <div class="alert alert-block">
                                <span id="box1Counter" class="countLabel"></span>
                                <select id="box1Storage" class="form-control"></select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="btn-group btn-group-vertical" style="white-space: normal;">
                            <button id="to2" type="button" class="btn btn-primary">
                                <i class="icon-chevron-right"></i>
                            </button>
                            <button id="allTo2" type="button" class="btn btn-primary">
                                <i class="icon-forward"></i>
                            </button>
                            <button id="allTo1" type="button" class="btn btn-danger">
                                <i class="icon-backward"></i>
                            </button>
                            <button id="to1" type="button" class="btn btn-danger">
                                <i class=" icon-chevron-left icon-white"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="form-group">
                            <div class="input-group">
                                <input id="box2Filter" type="text" placeholder="Filter" class="form-control" />
                                <span class="input-group-btn">
                                    <button id="box2Clear" class="btn btn-warning" type="button">x</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <select id="box2View" multiple="multiple" class="form-control" size="16"></select>
                        </div>
                        <hr />

                        <div class="alert alert-block">
                            <span id="box2Counter" class="countLabel"></span>
                            <select id="box2Storage" class="form-control"> </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
                                            <input type="hidden" id="ide_item" value="0"/>
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
        <meta name="_urlTarget" content="{{url('privilegios')}}" />
        <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.privilegios.js')}}"></script>  
        <script src="assets/js/jquery-ui.min.js"></script>
        <script src="assets/plugins/jquery.dualListbox-1.3/jquery.dualListBox-1.3.min.js"></script>
@endsection