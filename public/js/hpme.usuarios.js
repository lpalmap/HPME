/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    $('#dataTables-example').dataTable();
    
    var url = "/hpme/public/usuarios";

    //display modal form for task editing
//    $('.open-modal').click(function(){
//        var task_id = $(this).val();
//
//        $.get(url + '/' + task_id, function (data) {
//            //success data
//            console.log(data);
//            $('#task_id').val(data.id);
//            $('#task').val(data.task);
//            $('#description').val(data.description);
//            $('#btn-save').val("update");
//
//            $('#myModal').modal('show');
//        }) 
//    });

    //display modal form for creating new task
//    $('#btn-add').click(function(){
//        $('#btn-save').val("add");
//        $('#frmTasks').trigger("reset");
//        $('#myModal').modal('show');
//    });

    //delete task and remove it from list
    $('.btn-danger').click(function(){
        $('#btnEliminar').val($(this).val());
        $('#buttonedModal').modal('show');
        
//        $.ajaxSetup({
//            headers: {
//                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
//            }
//        })
//        
//        $.ajax({
//            type: "DELETE",
//            url: url + '/' + user_id,
//            success: function (data) {
//                console.log(data);
//                $("#usuario" + user_id).remove();
//            },
//            error: function (data) {
//                console.log('####Error:', data);
//                alert('Error borrado '+data);
//            }
//        });
    });
    
    $('#btnEliminar').click(function(){
        var user_id = $(this).val();
        $('#buttonedModal').modal('show');
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        $.ajax({
            type: "DELETE",
            url: url + '/' + user_id,
            success: function (data) {
                console.log(data);
                $("#usuario" + user_id).remove();
            },
            error: function (data) {
                console.log('####Error:', data);
                alert('Error borrado '+data);
            }
        });
        $('#buttonedModal').modal('hide');
    });
    
    //Agregar nuevo usuario
    $('#btnAgregar').click(function(){
        $('#inputTitle').html("Agregar Usuario");
        $('#formAgregar').trigger("reset");
        $('#btnGuardar').val('add');
        $('#formModal').modal('show');
    });
    
    $('.btn-editar').click(function(){
        var ide_usuario=$(this).val();
        $('#inputTitle').html("Editar Usuario");
        $.get(url + '/' + ide_usuario, function (data) {
            //success data
            console.log(data);
            $('#inUsuario').val(data.usuario);
            $('#inPassword').val(data.password);
            $('#inNombres').val(data.nombres);
            $('#inApellidos').val(data.apellidos);
            $('#ide_usuario').val(data.ide_usuario);
            $('#btnGuardar').val('update');
            $('#formModal').modal('show');
        }) 
    });    

    //create new task / update existing task
    $("#btnGuardar").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var formData = {
            usuario: $('#inUsuario').val(),
            password: $('#inPassword').val(),
            nombres: $('#inNombres').val(),
            apellidos: $('#inApellidos').val(),
        };

        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#btnGuardar').val();

        var type = "POST"; //for creating new resource
        var ide_usuario = $('#ide_usuario').val();;
        var my_url = url;

        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + ide_usuario;
        }

        console.log(formData);

        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);

                var item = '<tr class="even gradeA" id="usuario' + data.ide_usuario+ '"><td>' + data.usuario + '</td><td>' + data.nombres + '</td><td>' + data.apellidos+ '</td>';
                item += '<td><button class="btn btn-primary btn-editar" value="' + data.ide_suario + '"><i class="icon-pencil icon-white" ></i> Editar</button>';
                item += '<button class="btn btn-danger" value="' + data.ide_usuario + '"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';

                if (state == "add"){ //if user added a new record
                    $('#lista-items').append(item);
                }else{ //if user updated an existing record

                    $("#lista-items" + ide_usuario).replaceWith( item );
                }

                $('#formAgregar').trigger("reset");

                $('#formModal').modal('hide')
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
});

