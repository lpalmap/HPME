<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

 <!-- BEGIN HEAD -->
<head>
     <meta charset="UTF-8" />
    <title>BCORE Admin Dashboard Template | Data Tables</title>
     <meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
     <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <!-- GLOBAL STYLES -->
    <!-- GLOBAL STYLES -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/theme.css" />
    <link rel="stylesheet" href="assets/css/MoneAdmin.css" />
    <link rel="stylesheet" href="assets/plugins/Font-Awesome/css/font-awesome.css" />
    <!--END GLOBAL STYLES -->

    <!-- PAGE LEVEL STYLES -->
    <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <!-- END PAGE LEVEL  STYLES -->
       <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
     <!-- END HEAD -->
     
    <!-- BEGIN BODY-->
<body class="padTop53 " >

     <!-- MAIN WRAPPER -->
    <div id="wrap">


         <!-- HEADER SECTION -->
        <div id="top">

            <nav class="navbar navbar-inverse navbar-fixed-top " style="padding-top: 10px;">
                <a data-original-title="Show/Hide Menu" data-placement="bottom" data-tooltip="tooltip" class="accordion-toggle btn btn-primary btn-sm visible-xs" data-toggle="collapse" href="#menu" id="menu-toggle">
                    <i class="icon-align-justify"></i>
                </a>
                <!-- LOGO SECTION -->
                <header class="navbar-header">

                    <a href="{{asset('home')}}" class="navbar-brand">
                    <img src="{{asset('images/loghab.png')}}" alt="" /></a>
                    <img src="{{asset('images/banner.png')}}" alt="" /></a>
                </header>
                <!-- END LOGO SECTION -->
                <ul class="nav navbar-top-links navbar-right">

                    <!-- MESSAGES SECTION -->
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <span class="label label-success">2</span>    <i class="icon-envelope-alt"></i>&nbsp; <i class="icon-chevron-down"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-messages">
                            <li>
                                <a href="#">
                                    <div>
                                       <strong>John Smith</strong>
                                        <span class="pull-right text-muted">
                                            <em>Today</em>
                                        </span>
                                    </div>
                                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing.
                                        <br />
                                        <span class="label label-primary">Important</span> 

                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <strong>Raphel Jonson</strong>
                                        <span class="pull-right text-muted">
                                            <em>Yesterday</em>
                                        </span>
                                    </div>
                                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing.
                                         <br />
                                        <span class="label label-success"> Moderate </span> 
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <strong>Chi Ley Suk</strong>
                                        <span class="pull-right text-muted">
                                            <em>26 Jan 2014</em>
                                        </span>
                                    </div>
                                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing.
                                         <br />
                                        <span class="label label-danger"> Low </span> 
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a class="text-center" href="#">
                                    <strong>Read All Messages</strong>
                                    <i class="icon-angle-right"></i>
                                </a>
                            </li>
                        </ul>

                    </li>
                    <!--END MESSAGES SECTION -->

                    <!--TASK SECTION -->
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <span class="label label-danger">5</span>   <i class="icon-tasks"></i>&nbsp; <i class="icon-chevron-down"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-tasks">
                            <li>
                                <a href="#">
                                    <div>
                                        <p>
                                            <strong> Profile </strong>
                                            <span class="pull-right text-muted">40% Complete</span>
                                        </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                                <span class="sr-only">40% Complete (success)</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <p>
                                            <strong> Pending Tasks </strong>
                                            <span class="pull-right text-muted">20% Complete</span>
                                        </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <p>
                                            <strong> Work Completed </strong>
                                            <span class="pull-right text-muted">60% Complete</span>
                                        </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                                <span class="sr-only">60% Complete (warning)</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <p>
                                            <strong> Summary </strong>
                                            <span class="pull-right text-muted">80% Complete</span>
                                        </p>
                                        <div class="progress progress-striped active">
                                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                                <span class="sr-only">80% Complete (danger)</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a class="text-center" href="#">
                                    <strong>See All Tasks</strong>
                                    <i class="icon-angle-right"></i>
                                </a>
                            </li>
                        </ul>

                    </li>
                    <!--END TASK SECTION -->

                    <!--ALERTS SECTION -->
                    <li class="chat-panel dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <span class="label label-info">8</span>   <i class="icon-comments"></i>&nbsp; <i class="icon-chevron-down"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-alerts">

                            <li>
                                <a href="#">
                                    <div>
                                        <i class="icon-comment" ></i> New Comment
                                    <span class="pull-right text-muted small"> 4 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <i class="icon-twitter info"></i> 3 New Follower
                                    <span class="pull-right text-muted small"> 9 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <i class="icon-envelope"></i> Message Sent
                                    <span class="pull-right text-muted small" > 20 minutes ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <i class="icon-tasks"></i> New Task
                                    <span class="pull-right text-muted small"> 1 Hour ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">
                                    <div>
                                        <i class="icon-upload"></i> Server Rebooted
                                    <span class="pull-right text-muted small"> 2 Hour ago</span>
                                    </div>
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a class="text-center" href="#">
                                    <strong>See All Alerts</strong>
                                    <i class="icon-angle-right"></i>
                                </a>
                            </li>
                        </ul>

                    </li>
                    <!-- END ALERTS SECTION -->

                    <!--ADMIN SETTINGS SECTIONS -->

                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="icon-user "></i>&nbsp; <i class="icon-chevron-down "></i>
                        </a>

                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#"><i class="icon-user"></i> User Profile </a>
                            </li>
                            <li><a href="#"><i class="icon-gear"></i> Settings </a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="login.html"><i class="icon-signout"></i> Logout </a>
                            </li>
                        </ul>

                    </li>
                    <!--END ADMIN SETTINGS -->
                </ul>

            </nav>

        </div>
        <!-- END HEADER SECTION -->



        <!-- MENU SECTION -->
       <div id="left">
            <div class="media user-media well-small">
                <a class="user-link" href="#">
                    <img class="media-object img-thumbnail user-img" alt="User Picture" src="{{asset('images/user_default.png')}}" />
                </a>
                <br />
                <div class="media-body">
                    <h5 class="media-heading">{{Auth::user()->usuario}}</h5>
                    <ul class="list-unstyled user-info">
                        
                        <li>
                             <a class="btn btn-success btn-xs btn-circle" style="width: 10px;height: 12px;"></a> Online
                           
                        </li>
                       
                    </ul>
                </div>
                <br />
            </div>

            <ul id="menu" class="collapse">
                 
                <!-- MENU PLANIFICACION -->
                 
                <li class="panel ">
                    <a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#component-nav">
<!--                        <i class="icon-tasks"> </i>     -->
                        <img class="menu-imagen"src="{{asset('images/mod_planificacion.png')}}"/>Planificaci&#243;n 
                        <span class="pull-right">
                          <i class="icon-angle-left"></i>
                        </span>
                       &nbsp; <span class="label label-default">0</span>&nbsp;
                    </a>
                    <ul class="collapse" id="component-nav">                   
                        <li class=""><a href="button.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/plan_anual.png')}}"/> Planificaci&#243;n Anual</a></li>
                         <li class=""><a href="icon.html"><i class="icon-angle-right"></i></i><img class="menu-imagen"src="{{asset('images/plan_afiliado.png')}}"/> Planificaci&#243;n Afiliado</a></li>
                        <li class=""><a href="progress.html"><i class="icon-angle-right"></i></i><img class="menu-imagen"src="{{asset('images/plan_aut.png')}}"/> Autorizaci&#243;n Planificaci&#243;n</a></li>
                        <li class=""><a href="tabs_panels.html"><i class="icon-angle-right"></i></i><img class="menu-imagen"src="{{asset('images/plan_cerrar.png')}}"/> Cerrar Planificaci&#243;n</a></li>
                        <li class=""><a href="notifications.html"><i class="icon-angle-right"></i></i><img class="menu-imagen"src="{{asset('images/plan_formato.png')}}"/> Generar Formato Internacional</a></li>
                         <li class=""><a href="more_notifications.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/calc.png')}}"/></i> Backup Planificaci&#243;n Anual</a></li>
                    </ul>
                </li>
                
                 <!-- MENU MONITOREO -->
                 
                <li class="panel ">
                    <a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle collapsed" data-target="#form-nav">
                        <img class="menu-imagen"src="{{asset('images/mod_monitoreo.png')}}"/>  Monitoreo
	   
                        <span class="pull-right">
                            <i class="icon-angle-left"></i>
                        </span>
                          &nbsp; <span class="label label-success">0</span>&nbsp;
                    </a>
                    <ul class="collapse" id="form-nav">
                        <li class=""><a href="forms_general.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/mon_admin.png')}}"/>  Administraci&#243;n Monitoreo Trimestral</a></li>
                        <li class=""><a href="forms_advance.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/mon_formato.png')}}"/>  Formato Trimestral </a></li>
                        <li class=""><a href="forms_validation.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/mon_avances.png')}}"/>  Monitoreo de Avances </a></li>
                        <li class=""><a href="forms_fileupload.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/mon_historial.png')}}"/>  Historial de Formatos </a></li>
                        <li class=""><a href="forms_editors.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/calc.png')}}"/>  Backup Formato Trimestral </a></li>
                    </ul>
                </li>
                
                <!-- MENU MONITOREO -->

                <li class="panel">
                    <a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#pagesr-nav">
                        <img class="menu-imagen"src="{{asset('images/mod_reportes.png')}}"/> Reportes
	   
                        <span class="pull-right">
                            <i class="icon-angle-left"></i>
                        </span>
                          &nbsp; <span class="label label-info">0</span>&nbsp;
                    </a>
                    <ul class="collapse" id="pagesr-nav">
                        <li><a href="pages_calendar.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/rep_plan_anual.png')}}"/>  Planificaci&#243;n Consolidada Anual </a></li>
                        <li><a href="pages_timeline.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/rep_anual_trimestral.png')}}"/>  Planificaci&#243;n Consolidada Trimestral </a></li>
                        <li><a href="pages_social.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/grafico.png')}}"/>  Generar Gr&aacute;fico </a></li>
                        <li><a href="pages_pricing.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/rep_historial.png')}}"/>  Historial de Reportes </a></li>
                    </ul>
                </li>
                
                
                <!-- MENU CONFIGURACION -->
                <li class="panel">
                    <a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#DDL-nav">
                        <img class="menu-imagen"src="{{asset('images/mod_conf.png')}}"/> Configuraci&#243;n
	   
                        <span class="pull-right">
                            <i class="icon-angle-left"></i>
                        </span>
                    </a>
                    <ul class="collapse" id="DDL-nav">
                        <li><a href="#"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/conf_parametro.png')}}"/> Par&aacute;metros </a></li>
                        <li><a href="#"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/conf_lista.png')}}"/> Lista de Valores </a></li>
                        <li>
                            <a href="#" data-parent="#DDL-nav" data-toggle="collapse" class="accordion-toggle" data-target="#DDL1-nav">
                                <img class="menu-imagen"src="{{asset('images/conf_catalogo.png')}}"/> Cat&aacute;logos
	   
                        <span class="pull-right" style="margin-right: 20px;">
                            <i class="icon-angle-left"></i>
                        </span>


                            </a>
                            <ul class="collapse" id="DDL1-nav">
                                <li>
                                    <a href="#"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/conf_afiliado.png')}}"/> Afiliados</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/conf_proyecto.png')}}"/> Proyectos</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/conf_meta.png')}}"/> Metas</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/conf_indicador.png')}}"/> Indicadores</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/conf_objetivo.png')}}"/> Objetivos</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/conf_producto.png')}}"/> Productos</a>
                                </li>
                                <li>
                                    <a href="#"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/conf_recurso.png')}}"/> Recursos</a>
                                </li>
                            </ul>

                        </li>
                        
                    </ul>
                </li>
                
                <!-- MENU SEGURIDAD -->
                <li class="panel">
                    <a href="#" data-parent="#menu" data-toggle="collapse" class="accordion-toggle" data-target="#chart-nav">
                        <img class="menu-imagen"src="{{asset('images/seg_lock.png')}}"/> Seguridad
	   
                        <span class="pull-right">
                            <i class="icon-angle-left"></i>
                        </span>
                          &nbsp; <span class="label label-danger">0</span>&nbsp;
                    </a>
                    <ul class="collapse" id="chart-nav">
                        <li><a href="{{url('usuarios')}}"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/user.jpg')}}"/> Usuarios </a></li>
                        <li><a href="charts_bar.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/seg_roles.png')}}"/> Roles </a></li>
                        <li><a href="charts_pie.html"><i class="icon-angle-right"></i><img class="menu-imagen"src="{{asset('images/seg_privilegio.png')}}"/> Privelgios </a></li>
                    </ul>
                </li>
                
            </ul>

        </div>
        <!--END MENU SECTION -->


        <!--PAGE CONTENT -->
         <!--PAGE CONTENT -->
        <div id="content">

            <div class="inner">
                <div class="row">
                    <div class="col-lg-12">


                        <h2> Usuarios </h2>



                    </div>
                </div>

                <hr />


                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <button class="btn btn-success" id="btnAgregar"><i class="icon-user icon-white" ></i> Agregar Usuario</button>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-items" name="lista-items">
                                        @if (isset($usuarios))
                                            @for ($i=0;$i<count($usuarios);$i++)
                                        <tr class="even gradeA" id="usuario{{$usuarios[$i]->ide_usuario}}">
                                            <td>{{$usuarios[$i]->usuario}}</td>
                                            <td>{{$usuarios[$i]->nombres}}</td>
                                            <td>{{$usuarios[$i]->apellidos}}</td>
                                            <td>
                                                <button class="btn btn-primary btn-editar" value="{{$usuarios[$i]->ide_usuario}}"><i class="icon-pencil icon-white" ></i> Editar</button>
                                                <button class="btn btn-danger" value="{{$usuarios[$i]->ide_usuario}}"><i class="icon-remove icon-white"></i> Eliminar</button>
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
       <!--END PAGE CONTENT -->


    </div>

     <!--END MAIN WRAPPER -->
     
     <div class="col-lg-12">
                        <div class="modal fade" id="buttonedModal" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="H1">Eliminar Usuario</h4>
                                        </div>
                                        <div class="modal-body">
                                            Esta seguro de eliminar el usuario.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="btnEliminar">Eliminar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
     
                        
                <div class="col-lg-12">
                        <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="inputTitle"></h4>
                                        </div>
                                        <div class="modal-body">
                                            <form role="form" id="formAgregar">
                                            <div class="form-group">
                                                <label>Usuario</label>
                                                <input class="form-control" id="inUsuario" required="true"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Contrase&ntilde;a</label>
                                                <input class="form-control" id="inPassword" required="true" type="password"/>
                                            </div>
                                            <div class="form-group">
                                                <label>Nombres</label>
                                                <input class="form-control" id="inNombres" required="true"/>
                                            </div>                                          
                                               <div class="form-group">
                                                <label>Apellidos</label>
                                                <input class="form-control" id="inApellidos" required="true"/>
                                          </div>
                                       
                                    </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
                                            <input type="hidden" id="ide_usuario" value="0"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

   <!-- FOOTER -->
    <div id="footer">
        <p>&copy;  Habitat para la Humanidad Guatemala &nbsp;2016 &nbsp;</p>
    </div>
    <!--END FOOTER -->
    <meta name="_token" content="{!! csrf_token() !!}" />
     <!-- GLOBAL SCRIPTS -->
    <script src="assets/plugins/jquery-2.0.3.min.js"></script>
     <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    <!-- END GLOBAL SCRIPTS -->
        <!-- PAGE LEVEL SCRIPTS -->
    <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="assets/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="{{asset('js/hpme.usuarios.js')}}"></script>
<!--     <script>
         $(document).ready(function () {
             $('#dataTables-example').dataTable();
         });
    </script>-->
     <!-- END PAGE LEVEL SCRIPTS -->
</body>
    <!-- END BODY-->
    
</html>