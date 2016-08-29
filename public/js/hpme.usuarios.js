/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
//    var dataTable=$('#dataTables-example').DataTable({
//	"sProcessing":     "Procesando...",
//	"sLengthMenu":     "Mostrar _MENU_ registros",
//	"sZeroRecords":    "No se encontraron resultados",
//	"sEmptyTable":     "NingÃºn dato disponible en esta tabla",
//	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
//	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
//	"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
//	"sInfoPostFix":    "",
//	"sSearch":         "Buscar:",
//	"sUrl":            "",
//	"sInfoThousands":  ",",
//	"sLoadingRecords": "Cargando...",
//	"oPaginate": {
//		"sFirst":    "Primero",
//		"sLast":     "Ãšltimo",
//		"sNext":     "Siguiente",
//		"sPrevious": "Anterior"
//	},
//	"oAria": {
//		"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
//		"sSortDescending": ": Activar para ordenar la columna de manera descendente"
//	}
//});

    var dataTable=$('#dataTables-example').DataTable(window.lang);
    
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
//    $('.btn-danger').on('click','.btn-danger',function(){
//        $('#btnEliminar').val($(this).val());
//        $('#buttonedModal').modal('show');
//        alert('tesrs33333333');
//        
////        $.ajaxSetup({
////            headers: {
////                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
////            }
////        })
////        
////        $.ajax({
////            type: "DELETE",
////            url: url + '/' + user_id,
////            success: function (data) {
////                console.log(data);
////                $("#usuario" + user_id).remove();
////            },
////            error: function (data) {
////                console.log('####Error:', data);
////                alert('Error borrado '+data);
////            }
////        });
//    });
    
    $( document ).on( 'click', '.btn-danger', function() {
        $('#btnEliminar').val($(this).val());
        $('#buttonedModal').modal('show');
    });
//    $( '.btn-danger' ).on( 'click', 'button', function() {
//        $('#btnEliminar').val($(this).val());
//        $('#buttonedModal').modal('show');
//    });
    
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
                //$("#usuario" + user_id).remove();
                //dataTable.DataTable().draw();
                dataTable.row( $('#usuario'+user_id)).remove().draw();
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
    
    $(document).on('click','.btn-editar',function(){
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
        console.log("Enviando url "+my_url);
        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data); 
                var item = '<tr class="even gradeA" id="usuario' + data.ide_usuario+ '"><td>' + data.usuario + '</td><td>' + data.nombres + '</td><td>' + data.apellidos+ '</td>';
                    item += '<td><button class="btn btn-primary btn-editar" value="' + data.ide_usuario + '"><i class="icon-pencil icon-white" ></i> Editar</button>';
                    item += '<button class="btn btn-danger" value="' + data.ide_usuario + '"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
 
                if (state == "add"){ //if user added a new record
                    
                    //$('#lista-items').append(item);
                    //dataTable.DataTable.add(item).draw();
                    dataTable.rows.add($(item)).draw();
                    //dataTable.DataTable().draw();
                    
                }else{ //if user updated an existing record
                    //var d=dataTable.DataTable().row($('#usuario'+ide_usuario)).data();
                    //$("#lista-items" + ide_usuario).replaceWith( item );
                     //dataTable.DataTable.row(item).data().draw();
                     //dataTable.DataTable().row($('#usuario'+ide_usuario)).data($(item)).draw();
                     dataTable.row( $('#usuario'+ide_usuario)).remove();
                     dataTable.rows.add($(item)).draw();
                }

                $('#formAgregar').trigger("reset");

                $('#formModal').modal('hide');
                //dataTable.DataTable.ajax.reload();
            },
            error: function (data) {
                console.log('Error:', data);
                alert(data.responseText);
            }
        });
    });
});

