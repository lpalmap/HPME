/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){    
    //Agregar nuevo usuario
    
    $( document ).on( 'click', '.btn-editar-valor', function() {
        $('#loading').modal('show');
        $('#ingresarDetalleModal').modal('show');
        $('#loading').modal('hide');
    });
    
    $('#btnAprobarPlan').click(function(){
        $('#loading').modal('show');
        $('#aprobarPlanificacion').modal('show');
        $('#btnAprobar').val($(this).val());
        $('#loading').modal('hide');
    });
    
    $("#btnAprobar").click(function (e) {      
        $('#loading').modal('show');
        var formData = {
            ide_proyecto_region: $(this).val()
        };   
              
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        var url_target=(""+$('meta[name="_urlTarget"]').attr('content'))+'/aprobar';
        $.ajax({
            type: 'POST',
            url: url_target,
            data: formData,
            dataType: 'json',
            success: function (data) {
                location.reload();
                $('#aprobarPlanificacion').modal('hide');
                $('#loading').modal('hide');
            },
            error: function (data) {
                $('#loading').modal('hide');
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al aprobar la planificaci&oacute;n.</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');                
            }
        });
    });
    
    $("#btnMarcar").click(function (e) {      
        $('#loading').modal('show');
        var formData = {
            ide_proyecto_region: $(this).val()
        };   
              
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });      
        var url_target=(""+$('meta[name="_urlTarget"]').attr('content'))+'/marcar';
        $.ajax({
            type: 'POST',
            url: url_target,
            data: formData,
            dataType: 'json',
            success: function (data) {    
                location.reload(true);
                $('#cerrarObservacion').modal('hide');
                $('#loading').modal('hide');
            },
            error: function (data) {
                $('#loading').modal('hide');
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al marcar las observaciones.</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');                
            }
        });
    });
    
    $("#cleanVacio").click(function (e) {      
        $('#loading').modal('show');
        $('.goodbye').remove();
        $('#loading').modal('hide');
        $('#confirmacionModal').modal('show');    
    });
    
    
});