/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTableItems').DataTable({
        "language": window.lang.language
    });
    
    $("#btnIniciar").click(function (e) { 
        $('#loading').modal('show');
        var ideProyecto=$(this).val();
        var formData = {
            ide_proyecto: ideProyecto
        };   
              
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        var url_target=(""+$('meta[name="_urlTarget"]').attr('content'));
        url_target+='/iniciar';
        
        $.ajax({
            type: 'POST',
            url: url_target,
            data: formData,
            dataType: 'json',
            success: function (data) {
                //$('#loading').modal('hide');
                location.reload(true);
            },
            error: function (data) {
                $('#loading').modal('hide');
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al iniciar el proceso de monitoreo.</li>';
                }
                //console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');                
            }
        });
    });
    
    $("#btnHabilitar").click(function (e) { 
        $('#loading').modal('show');
        var idePeriodo=$(this).val();
        var formData = {
            ide_periodo_monitoreo: idePeriodo
        };   
              
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        var url_target=(""+$('meta[name="_urlTarget"]').attr('content'));
        url_target+='/habilitar';
        
        $.ajax({
            type: 'POST',
            url: url_target,
            data: formData,
            dataType: 'json',
            success: function (data) {
                //$('#loading').modal('hide');
                location.reload(true);
            },
            error: function (data) {
                $('#loading').modal('hide');
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al iniciar el proceso de monitoreo.</li>';
                }
                //console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');                
            }
        });
    });
    
    $( document ).on( 'click', '.btn-habilitar', function() { 
        $('#loading').modal('show');
        $('#btnHabilitar').val($(this).val());
        $('#habilitarModal').modal('show');
        $('#loading').modal('hide');
    });
     
});
