/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    $('#btnActualizar').click(function(){
        $('#loading').modal('show');
        $('#confirmarModal').modal('show');
        $('#loading').modal('hide');
    });
     $("#btnGuardar").click(function (e) {
        $('#loading').modal('show');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var formData = {
            password: $('#inPassword').val(),
            nombres: $('#inNombres').val(),
            apellidos: $('#inApellidos').val(),
            email: $('#inEmail').val()
        };   
        var url_target=$('meta[name="_urlTarget"]').attr('content');
        console.log(formData);
        console.log("Enviando url "+url_target);
        $.ajax({
            type: 'POST',
            url: url_target,
            data: formData,
            dataType: 'json',
            success: function (data) {
                $('#confirmarModal').modal('hide');
                $('#loading').modal('hide');
            },
            error: function (data) {
                $('#confirmarModal').modal('hide');
                $('#loading').modal('hide');
                console.log('Error:', data);
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al guardar el usuario.</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');  
            }
        });
        
    });
});