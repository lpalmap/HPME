/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTableItems').DataTable(window.lang);
    
    $(document).on('click','.btn-enviar',function(){
        $('#loading').modal('show');
        $('#btnEnviar').val($(this).val());
        $('#enviarModal').modal('show');
        $('#loading').modal('hide');
    }); 
    
    $("#btnEnviar").click(function (e) { 
        $('#loading').modal('show');
        var ideProyecto=$(this).val();
        var formData = {
            ide_presupuesto_departamento: ideProyecto
        };   
              
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        var url_target=(""+$('meta[name="_urlTarget"]').attr('content'));
        url_target+='/enviar';
        $.ajax({
            type: 'POST',
            url: url_target,
            data: formData,
            dataType: 'json',
            success: function (data) {
                $('#loading').modal('hide');
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
                    errHTML+='<li>Error al enviar el presupuesto.</li>';
                }
                //console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');                
            }
        });
    });
    
});