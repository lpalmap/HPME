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
        var ideRegionProductoDetalle=$(this).val();
        $('#btnGuardarDetalle').val(ideRegionProductoDetalle);
        var targetURL=$('meta[name="_urlTarget"]').attr('content');
        var requiereComprobante=$("#comprobante"+ideRegionProductoDetalle).val();
        //var periodo=$('meta[name="_periodo"]').attr('content');
        $.get(targetURL + '/detalleproducto/'+ideRegionProductoDetalle, function (data) {
            //success data
            $('#planificado').val(data.valor);
            $('#ejecutado').val(data.ejecutado); 
            $('#tabla_archivos tbody').html('');
            if(data.hasOwnProperty("archivos") && data.archivos){
                var tabla=$('#tabla_archivos tbody');
                var url_download=(""+$('meta[name="_urlDownload"]').attr('content'));
                var download_image=(""+$('meta[name="_download"]').attr('content'));
                var url_delete=(""+$('meta[name="_urlDelete"]').attr('content'));
                var delete_image=(""+$('meta[name="_delete"]').attr('content'));
                var download='<img src="'+download_image+'" class="menu-imagen" alt="" title="Descargar archivo"'
                var deletefile='<img src="'+delete_image+'" class="menu-imagen" alt="" title="Eliminar archivo"'
                for(var e in data.archivos){
                  tabla.append("<tr><td>" + data.archivos[e].nombre + "</td><td>" + data.archivos[e].fecha + "</td><td><a href="+url_download+'/'+data.archivos[e].ide_archivo_producto+">"+download+"</a><a href="+url_delete+'/'+data.archivos[e].ide_archivo_producto+">"+deletefile+"</a></td>");
                }
                if(requiereComprobante==='S'){
                    $("#fileUpload").attr('disabled', false);
                    $("#subirArchivo").attr('disabled', false);
                    $("#archivoLabel").html('** Este producto requiere cargar archivos para comprobar la ejecuci&oacute;n.');
                }else{
                    $("#fileUpload").attr('disabled', true);
                    $("#subirArchivo").attr('disabled', true);
                    $("#subirArchivo").removeAttr('disabled');
                    $("#archivoLabel").html('');
                }     
            }
            $('#loading').modal('hide');
        });
    });
    
    $( document ).on( 'click', '.btn-detalle-archivos', function() {
        $('#loading').modal('show');
        $('#detalleArchivosModal').modal('show');
        var ideRegionProductoDetalle=$(this).val();
        var targetURL=$('meta[name="_urlTarget"]').attr('content');
        //var periodo=$('meta[name="_periodo"]').attr('content');
        $.get(targetURL + '/detalleproducto/'+ideRegionProductoDetalle, function (data) {
            //success data
            console.log(data);
            $('#tabla_detalle_archivos tbody').html('');
            if(data.hasOwnProperty("archivos") && data.archivos){
                var tabla=$('#tabla_detalle_archivos tbody');
                var url_download=(""+$('meta[name="_urlDownload"]').attr('content'));
                var download_image=(""+$('meta[name="_download"]').attr('content'));
                var download='<img src="'+download_image+'" class="menu-imagen" alt="" title="Descargar archivo"'
                for(var e in data.archivos){
                  tabla.append("<tr><td>" + data.archivos[e].nombre + "</td><td>" + data.archivos[e].fecha + "</td><td><a href="+url_download+'/'+data.archivos[e].ide_archivo_producto+">"+download+"</a></td>");
                }
            }
            $('#loading').modal('hide');
        });
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
    
    $("#btnGuardarDetalle").click(function (e) {      
        $('#loading').modal('show');
        var ejecutado_val=$("#ejecutado").val()
        var ideRegionProductoDetalle=$(this).val();
        var requiereComprobante=$("#comprobante"+ideRegionProductoDetalle).val();
        var formData = {
            ide_region_producto_detalle: ideRegionProductoDetalle,
            ejecutado:ejecutado_val,
            requiere_archivo:requiereComprobante
        };     
              
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });      
        var url_target=(""+$('meta[name="_urlTarget"]').attr('content'))+'/guardar';
        $.ajax({
            type: 'POST',
            url: url_target,
            data: formData,
            dataType: 'json',
            success: function (data) {    
                $('#ejecutado'+ideRegionProductoDetalle).html(ejecutado_val);
                $('#ingresarDetalleModal').modal('hide');
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
                    errHTML+='<li>Error al guardar ejecuci&oacute;n</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');                
            }
        });
    });
    
    $( '#formAgregarDetalle' ).submit( function( e ) {
        $('#loading').modal('show');
        var url_target=(""+$('meta[name="_urlUpload"]').attr('content'));
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        }); 
        var ideRegionProductoDetalle=$("#btnGuardarDetalle").val();

        var data=new FormData(this);
        data.append('ide_region_producto_detalle',ideRegionProductoDetalle);
        
        $.ajax( {
          url: url_target,
          type: 'POST',
          data: data,
          processData: false,
          contentType: false,
          uploadMultiple : true,
          success: function(data){
              var tabla=$('#tabla_archivos tbody');
              var url_download=(""+$('meta[name="_urlDownload"]').attr('content'));
              var download_image=(""+$('meta[name="_download"]').attr('content'));
              var download='<img src="'+download_image+'" class="menu-imagen" alt="" title="Descargar archivo"'
              for(var e in data.archivos){
                  tabla.append("<tr><td>" + data.archivos[e].nombre + "</td><td>" + data.archivos[e].fecha + "</td><td><a href="+url_download+'/'+data.archivos[e].ide_archivo_producto+">"+download+"</a></td>");
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