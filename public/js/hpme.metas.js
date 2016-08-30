/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTables-example').DataTable(window.lang);
    var url = window.location;
    
    //Clic sobre el botón eliminar para un item de la tabla
    $( document ).on( 'click', '.btn-danger', function() {
        $('#btnEliminar').val($(this).val());
        $('#eliminarModal').modal('show');
    });
    
    //Clic sobre el botón eliminar en el popup de confirmación
    $('#btnEliminar').click(function(){
        //Se obtiene el id del elemento a eliminar
        var item_id = $(this).val();
       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        })
        //Se hace el request con ajax a la url para eliminar el item
        $.ajax({
            type: "DELETE",
            url: url + '/' + item_id,
            success: function (data) {
                console.log(data);
                //$("#usuario" + user_id).remove();
                //dataTable.DataTable().draw();
                dataTable.row( $('#item'+item_id)).remove().draw();
            },
            error: function (data) {
                console.log('####Error:', data);
                alert('Error borrado '+data);
            }
        });
        
        //Se oculta el popup de confirmación.
        $('#eliminarModal').modal('hide');
    });
    
    //Agregar nuevo usuario
    $('#btnAgregar').click(function(){
        $('#inputTitle').html("Agregar Meta");
        $('#formAgregar').trigger("reset");
        $('#btnGuardar').val('add');
        $('#agregarEditarModal').modal('show');
    });
    
    $(document).on('click','.btn-editar',function(){
        var ide_item=$(this).val();
        $('#inputTitle').html("Editar Meta");
        $.get(url + '/' + ide_item, function (data) {
            //success data
            console.log(data);
            $('#inNombre').val(data.nombre);
            $('#inDescripcion').val(data.descripcion);
            $('#btnGuardar').val('update');
            $('#agregarEditarModal').modal('show');
            $('#ide_item').val(data.ide_meta);
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
            nombre: $('#inNombre').val(),
            descripcion: $('#inDescripcion').val(),
        };

        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#btnGuardar').val();

        var type = "POST"; //for creating new resource
        var ide_item = $('#ide_item').val();;
        var my_url = url;

        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + ide_item;
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
                var item = '<tr class="even gradeA" id="item'+data.ide_meta+'">'
                    item+='<td>'+data.nombre+'</td>'
                    item+='<td>'+data.descripcion+'</td>';
                    item+='<td><button class="btn btn-primary btn-editar" value="'+data.ide_meta+'"><i class="icon-pencil icon-white" ></i> Editar</button>';
                    item+='<button class="btn btn-danger" value="'+data.ide_meta+'"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
                if (state == "add"){ 
                    dataTable.rows.add($(item)).draw();                    
                }else{ 
                     dataTable.row( $('#item'+ide_item)).remove();
                     dataTable.rows.add($(item)).draw();
                }
                $('#formAgregar').trigger("reset");
                $('#agregarEditarModal').modal('hide');
            },
            error: function (data) {
                console.log('Error:', data);
                alert(data.responseText);
            }
        });
    });
});

