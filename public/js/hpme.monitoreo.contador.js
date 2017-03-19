/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){    
    //Agregar nuevo usuario
    // Set a variable, we will fill later.
    var value = null;

    // On submit click, set the value
    $('input[type="submit"]').click(function(){
        value = $(this).val();
    });
   
    $( '#formAgregarDetalle' ).submit( function( e ) {
        //console.log(e);
        $('#loading').modal('show');
        var target='verificarEjecucion'
        if(value==='Subir'){
            target="aplicarEjecucion";
        }
        var url_target=(""+$('meta[name="_urlUpload"]').attr('content'))+"/"+target;
        var ideProyectoPlanificacion=(""+$('meta[name="_proyecto"]').attr('content'));
        var idePeriodoMonitoreo=(""+$('meta[name="_periodo"]').attr('content'));
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        }); 
        
        var data=new FormData(this);
        data.append('ide_proyecto_planificacion',ideProyectoPlanificacion);
        data.append('ide_periodo_monitoreo',idePeriodoMonitoreo);
        
        $.ajax( {
          url: url_target,
          type: 'POST',
          data: data,
          processData: false,
          contentType: false,
          uploadMultiple : true,
          success: function(data){
              if(value==='Subir'){
                $('#confirmarModal').modal('show');
                location.reload(true);              
              }else{
                $('#inFila').val(data.filas);              
                $('#inFilaEncontrada').val(data.total_encontrado);
                $('#inFilaNoEncontrada').val(data.total_noencontrado);
                $('#inMontoEncontrado').val(data.monto_encontrado);
                $('#inMontoNoEncontrado').val(data.monto_noencontrado);        
                $('#detalleArchivosModal').modal('show');
              }
              
              $('#loading').modal('hide');
          },
          error:function(data){            
                $('#loading').modal('hide');
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( var e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al subir el archivo al servidor.</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');   
          }
        } );
        e.preventDefault();
    } 
    );
    
});