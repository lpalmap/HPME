@extends('layouts.master')
@section('globalStyles')
    @parent
        <!-- PAGE LEVEL STYLES -->
    <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- END PAGE LEVEL  STYLES -->
@endsection
@section('content')
<!--PAGE CONTENT -->
        <div id="content">

            <div class="inner">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Proyectos de Planificaci&oacute;n Anual</h2>
                    </div>
                </div>

                <hr />


                <div class="row">
                <div class="col-lg-12">
                      <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Vivienda Nueva(Numero de Construcciones)
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-pills">
                                <li><a href="{{url('/planificacion_anual')}}">Proyecto</a>
                                </li>
                                <li><a href="{{url('/planificacion_metas')}}">Metas</a>
                                </li>
                                <li><a href="{{url('/planificacion_objetivos')}}">Objetivos</a>
                                </li>
                                <li><a href="{{url('/planificacion_areas')}}">&Aacute;rea de Atenci&oacute;n</a>
                                </li>
                                <li><a href="{{url('/planificacion_indicadores')}}">Indicadores</a>
                                </li>
                                <li class="active"><a href="#profile-pills" data-toggle="tab">Productos</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="profile-pills">
                                       <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">                                      
                                        <tr class="even gradeA">
                                            <td><a class="btn">Estufa</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a class="btn">Letrina</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a class="btn">Filtro</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a class="btn">Kit Saludable</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a class="btn">Cosechadores de agua de lluvia</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a class="btn">Mochilas de agua</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a class="btn">Tipo 1</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a class="btn">Tipo 2</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                        <tr class="even gradeA">
                                            <td><a class="btn">Otro</a></td>
                                            <td>
                                                <button class="btn btn-danger" value=""><i class="icon-remove icon-white"></i> Eliminar</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                           
                        </div>
                                </div>
                            </div>
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
                        <div class="modal fade" id="agregarEditarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="inputTitle"></h4>
                                        </div>
                                        <div class="modal-body">
                                       <form role="form" id="formAgregar">
                                           
                                           <div class="form-group">
                                                   <table class="table table-striped table-bordered table-hover" id="dataTableItems">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">Ene-Mar</th>
                                            <th style="text-align: center">Abr-Jun</th>
                                            <th style="text-align: center">Julio-Sep</th>
                                            <th style="text-align: center">Oct-Dic</th>
                                            <th style="text-align: center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">                                      
                                        <tr class="even gradeA">
                                            <td><input class="form-control" required="true"/></td>
                                            <td><input class="form-control" required="true"/></td>
                                            <td><input class="form-control" required="true"/></td>
                                            <td><input class="form-control" required="true"/></td>
                                            <td><input class="form-control" disabled="true"/></td>
                                        </tr>
                                    </tbody>
                                </table>
                                                
                                           </div>
                                                
                                           <div class="form-group">
                                                <label>Proyecto</label>
                                                    <select id="inGrupoLista" class="form-control">
                                                       <option value="0">Oportunidades y casas para Guatemala. GT11101</option>
                                                       <option value="1">Pequeños saltos, grandes cambios GT11506</option>
                                                       <option value="2">Colonia Amway GT10522</option>
                                                       <option value="3">Colonia Skipper GT12002</option>
                                                       <option value="4">Cosechadores de agua en la comunidad Macalajau GT1</option>
                                                       <option value="5">Colonia Constructores de Esperanza GT14002</option>                                                      
                                                    </select>                                              
                                                </div>
                                            <div class="form-group">
                                                <label>Breve Descripci&oacute;n</label>
<!--                                                <input class="form-control" id="inDescripcion" required="true"/>-->
                                                <textarea class="form-control" id="inDescripcion" rows="3" style="width: 100%"></textarea>
                                                </div>
                                                <div class="form-group">
                                                <label>Recursos</label>
<!--                                                <input class="form-control" id="inDescripcion" required="true"/>-->
                                                    <div class="checkbox anim-checkbox">
            <input type="checkbox" id="ch1" />
            <label for="ch1">Economicos</label>
        </div>
        <div class="checkbox anim-checkbox">
            <input type="checkbox" id="ch2" class="primary" />
            <label for="ch2">Finacieros</label>
        </div>
        <div class="checkbox anim-checkbox">
            <input type="checkbox" id="ch3" class="success" />
            <label for="ch3">Humanos</label>
        </div>
        <div class="checkbox anim-checkbox">
            <input type="checkbox" id="ch4" class="warning" />
            <label for="ch4">Equipo</label>
        </div>
                                                </div>
                                       
                                       </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
                                            <input type="hidden" id="ide_item" value="0"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
@endsection

@section('footer')
    @parent
        <meta name="_token" content="{!! csrf_token() !!}" />
        <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
        <script src="{{asset('js/hpme.lang.js')}}"></script>
        <script src="{{asset('js/hpme.planificacion.js')}}"></script>
@endsection