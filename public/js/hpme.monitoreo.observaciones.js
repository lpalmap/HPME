/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){    
    //Agregar nuevo usuario
    $('#btnAgregar').click(function(){
        $('#loading').modal('show');
        $('#formAgregar').trigger("reset");
        $('#agregarObservacion').modal('show');
        $('#btnGuardar').val($(this).val());
        $('#loading').modal('hide');
    });
    
    $('#btnCerrar').click(function(){
        $('#loading').modal('show');
        $('#cerrarObservacion').modal('show');
        $('#btnMarcar').val($(this).val());
        $('#loading').modal('hide');
    });
    
    $("#btnGuardar").click(function (e) {      
        $('#loading').modal('show');
        var formData = {
            ide_periodo_region: $(this).val(),
            asunto:$('#inAsunto').val(),
            para:$('#inPara').val(),
            mensaje:$('#inMensaje').val()
        };   
              
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        var url_target=(""+$('meta[name="_urlTarget"]').attr('content'))+'/add';
        var usuario=parseInt($('meta[name="_usuario"]').attr('content'));
        $.ajax({
            type: 'POST',
            url: url_target,
            data: formData,
            dataType: 'json',
            success: function (data) {
                var nitem='';
                var user=parseInt(data.ide_usuario);
                if(user===usuario || usuario<0){
                    nitem+='<li>';
                    nitem+='<div class="timeline-badge danger">';
                    $('meta[name="_usuario"]').attr('content',user);
                }else{
                    nitem+='<li class="timeline-inverted">';
                    nitem+='<div class="timeline-badge success">';
                }
                
                nitem+='<i class="icon-envelope-alt"></i>';
                nitem+='</div><div class="timeline-panel">';
                nitem+='<div class="timeline-heading">';
                nitem+='<h4 class="timeline-title">'+data.nombres+' '+data.apellidos+' ('+data.usuario+')</h4></div><div class="timeline-body">';
                nitem+='<p>'+formData.mensaje+'</p></div></div></li>';
                $('#listaMensajes').append(nitem);
                if(data.cambioEstado==='S'){
                    location.reload(true);
                }                
                $('#agregarObservacion').modal('hide');
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
                    errHTML+='<li>Error al guardar el mensaje.</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');                
            }
        });
    });
    
    $("#btnMarcar").click(function (e) {      
        $('#loading').modal('show');
        var formData = {
            ide_periodo_region: $(this).val()
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
    
});