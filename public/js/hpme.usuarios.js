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
    
    var url = window.location;
    url=(""+url).replace("#","");
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
        $('#loading').modal('show');      
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "DELETE",
            url: url + '/' + user_id,
            success: function (data) {
                console.log(data);
                dataTable.row( $('#usuario'+user_id)).remove().draw();
                $('#loading').modal('hide');
            },
            error: function (data) {
                $('#loading').modal('hide');
                console.log('Error:', data);
                $('#loading').modal('hide');
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al borrar el usuario.</li>';
                }
                console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');  
            }
        });
        $('#buttonedModal').modal('hide');
    });
    
    //Agregar nuevo usuario
    $('#btnAgregar').click(function(){
        $('#inputTitle').html("Agregar Usuario");
        $('#lbPassword').html("Contrase&ntilde;a");
        $('#formAgregar').trigger("reset");
        $('#inRol').val(0);
        $('#btnGuardar').val('add');
        $('#formModal').modal('show');
    });
    
    $(document).on('click','.btn-editar',function(){
        $('#loading').modal('show');
        var ide_usuario=$(this).val();
        $('#inputTitle').html("Editar Usuario");
        $.get(url + '/' + ide_usuario, function (data) {
            //success data
            console.log(data);
            $('#inUsuario').val(data.usuario);
            $('#inPassword').val('');
            $('#inNombres').val(data.nombres);
            $('#inApellidos').val(data.apellidos);
            $('#inEmail').val(data.email);
            $('#ide_usuario').val(data.ide_usuario);
            if(data.hasOwnProperty("roles") && data.roles.length>0){
                $('#inRol').val(data.roles[0].ide_rol);    
            }else{
                $('#inRol').val(0);
            }           
            $('#lbPassword').html("Contrase&ntilde;a (*Dejar en blanco si no desea modificar)");
            $('#btnGuardar').val('update');
            $('#formModal').modal('show');
            $('#loading').modal('hide');
        });      
    });    

    //create new task / update existing task
    $("#btnGuardar").click(function (e) {
       $('#loading').modal('show');
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
            email: $('#inEmail').val(),       
            ide_rol: $('#inRol').val()
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
                    if(data.hasOwnProperty("roles") && data.roles.length>0){
                        item+='<td>'+data.roles[0].nombre+'</td>';                          
                    }else{
                        item+='<td></td>';
                    }
                    item += '<td><button class="btn btn-primary btn-editar" value="' + data.ide_usuario + '"><i class="icon-pencil icon-white" ></i> Editar</button>';
                    item += '<button class="btn btn-danger" value="' + data.ide_usuario + '"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
                if (state == "add"){ 
                    dataTable.rows.add($(item)).draw();                    
                }else{
                     dataTable.row( $('#usuario'+ide_usuario)).remove();
                     dataTable.rows.add($(item)).draw();
                }
                $('#formAgregar').trigger("reset");
                $('#formModal').modal('hide');
                $('#loading').modal('hide');
            },
            error: function (data) {
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
                console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');  
            }
        });
        
    });
});

