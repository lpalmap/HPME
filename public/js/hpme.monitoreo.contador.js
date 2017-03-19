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
        $('#erroresModal').modal('show');
        var url_target=(""+$('meta[name="_urlUpload"]').attr('content'))+"/verificarEjecucion";
       // var form=$(this);
        //var val = $("input[type=submit][clicked=true]").val()
        //alert("submit s"+value);
        //$('#loading').modal('hide');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        }); 
        //var ideRegionProductoDetalle=$("#btnGuardarDetalle").val();
        //var idePeriodoRegion=(""+$('meta[name="_periodoRegion"]').attr('content'));
        var data=new FormData(this);
        //data.append('ide_region_producto_detalle',ideRegionProductoDetalle);
        //data.append('ide_periodo_region',idePeriodoRegion);
        $.ajax( {
          url: url_target,
          type: 'POST',
          data: data,
          processData: false,
          contentType: false,
          uploadMultiple : true,
          success: function(data){
              
              console.log(data);
              $('#inFila').val(data.filas);
              
              //$('#inFilaEncontrada').val(data.filas);
              //$('#inFilaNoEncontrada').val(data.filas);
              //$('#inMontoEncontrado').val(data.filas);
              //$('#inMontoNoEncontrado').val(data.filas);
              
              $('#detalleArchivosModal').modal('show');
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