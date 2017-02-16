/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTableItems').DataTable({
        "order": [[ 2, "asc" ]],
        "language": window.lang.language
    });
    var url = window.location;
    url=(""+url).replace("#","");
    
    //Clic sobre el botón eliminar para un item de la tabla
    $( document ).on( 'click', '.btn-danger', function() {
        $('#btnEliminar').val($(this).val());
        $('#eliminarModal').modal('show');
    });
    
    //Clic sobre el botón eliminar en el popup de confirmación
    $('#btnEliminar').click(function(){
        $('#loading').modal('show');
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
                dataTable.row( $('#item'+item_id)).remove().draw();
                $('#loading').modal('hide');
            },
            error: function (data) {
               $('#loading').modal('hide');
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( var er in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[er]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al borrar el objetivo.</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');
            }
        });
        $('#eliminarModal').modal('hide');
    });
    
    //Agregar nuevo usuario
    $('#btnAgregar').click(function(){
        $('#inputTitle').html("Agregar Objetivo");
        $('#formAgregar').trigger("reset");
        $('#btnGuardar').val('add');
        $('#agregarEditarModal').modal('show');
    });
    
    $(document).on('click','.btn-editar',function(){
        $('#loading').modal('show');
        var ide_item=$(this).val();
        $('#inputTitle').html("Editar Objetivo");
        $.get(url + '/' + ide_item, function (data) {
            $('#inNombre').val(data.nombre);
            $('#inDescripcion').val(data.descripcion);
            $('#inOrden').val(data.orden);
            $('#btnGuardar').val('update');
            $('#agregarEditarModal').modal('show');
            $('#ide_item').val(data.ide_objetivo);
            $('#loading').modal('hide');
        }) 
    });    

    //create new task / update existing task
    $("#btnGuardar").click(function (e) {      
        var formData = {
            nombre: $('#inNombre').val(),
            descripcion: $('#inDescripcion').val(),
            orden : $('#inOrden').val()
        };   
        $('#loading').modal('show');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#btnGuardar').val();

        var type = "POST"; //for creating new resource
        var ide_item = $('#ide_item').val();;
        var my_url = url;

        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + ide_item;
        }

        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                var item = '<tr class="even gradeA" id="item'+data.ide_objetivo+'">'
                    item+='<td>'+data.nombre+'</td>'
                    item+='<td>'+data.descripcion+'</td>';
                    item+='<td>'+data.orden+'</td>';
                    item+='<td><button class="btn btn-primary btn-editar" value="'+data.ide_objetivo+'"><i class="icon-pencil icon-white" ></i> Editar</button>';
                    item+='<button class="btn btn-danger" value="'+data.ide_objetivo+'"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
                if (state == "add"){ 
                    dataTable.rows.add($(item)).draw();                    
                }else{ 
                     dataTable.row( $('#item'+ide_item)).remove();
                     dataTable.rows.add($(item)).draw();
                }
                $('#formAgregar').trigger("reset");
                $('#agregarEditarModal').modal('hide');
                $('#loading').modal('hide');
            },
            error: function (data) {
                $('#loading').modal('hide');
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( var er in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[er]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al borrar el objetivo.</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');               
            }
        });
    });
});