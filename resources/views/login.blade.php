<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Herramienta de Planificaci&#243;n</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.css')}}" />
<!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
  <script src="{{asset('assets/plugins/jquery.min.js')}}"></script>
  <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script> 
</head>
    <body>
         <div class="container">
            <div class="row">
		<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
                    <div style="text-align: center">
                        <img src="{{asset('images/loghab_login.png')}}"/>
                    </div>
			  	<div class="panel-heading">
                                    <h3 class="panel-title" style="text-align: center;font-weight: bold">Iniciar Sesi&#243;n</h3>
			 	</div>
                    @if (isset($error))
                    <div class="alert alert-danger fade in">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <strong>Error!</strong> Usuario o contrase&ntilde;a incorrectos.
                    </div>
                    @endif
			  	<div class="panel-body">
                                    <form accept-charset="UTF-8" role="form" method="POST" action="{{url('login')}}"
                                          {{csrf_field()}}
                                     <fieldset>
                                         <input type="hidden" name="_token" value="{{ csrf_token() }}">
			    	  	<div class="form-group">
                                            <input class="form-control" placeholder="Usuario" name="usuario" type="text" id="usuario">
			    		</div>
			    		<div class="form-group">
                                            <input class="form-control" placeholder="Contrase&ntilde;a" name="password" type="password" value="" id="password">
			    		</div>
			    		<div class="checkbox">
			    	    	<label>
			    	    		<input name="remember" type="checkbox" value="Recordarme"> Recordarme
			    	    	</label>
			    	    </div>
			    		<input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
			    	</fieldset>
			      	</form>
			    </div>
			</div>
		</div>
	</div>
        </div>   
    </body>
</html>