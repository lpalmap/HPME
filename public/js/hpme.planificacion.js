/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTableItems').DataTable({
        "order": [[ 4, "asc" ]],
        "language": window.lang.language
    });
    
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
        var my_url=$('meta[name="_url"]').attr('content');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        //Se hace el request con ajax a la url para eliminar el item
        $.ajax({
            type: "DELETE",
            url: my_url + '/' + item_id,
            success: function (data) {
                console.log(data);
                //$("#usuario" + user_id).remove();
                //dataTable.DataTable().draw();
                dataTable.row( $('#item'+item_id)).remove().draw();
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
                    errHTML+='<li>Error al borrar la plantilla.</li>';
                }
                console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show'); 
            }
        });
        
        //Se oculta el popup de confirmación.
        $('#eliminarModal').modal('hide');
    });
    
    //Agregar nuevo usuario
    $('#btnAgregar').click(function(){
        $('#inputTitle').html("Nueva Plantilla");
        $('#formAgregar').trigger("reset");
        $('#inPeriodo').prop("disabled",false);
        $('#btnGuardar').val('add');
        $('#agregarEditarModal').modal('show');
    });
    
    $(document).on('click','.btn-editar',function(){
        $('#loading').modal('show');
        var ide_item=$(this).val();
        $('#inputTitle').html("Editar Plantilla");
        var my_url=$('meta[name="_url"]').attr('content');
        $.get(my_url + '/' + ide_item, function (data) {
            //success data
            console.log(data);
            $('#inDescripcion').val(data.descripcion);
            $('#inPeriodo').val(data.ide_lista_periodicidad);
            $('#inPeriodo').prop("disabled",true);
            $('#btnGuardar').val('update');
            $('#agregarEditarModal').modal('show');
            $('#ide_item').val(data.ide_proyecto);
            $('#loading').modal('hide');
        });
    });    

    //create new task / update existing task
    $("#btnGuardar").click(function (e) {      
        $('#loading').modal('show');
        var formData = {
            descripcion: $('#inDescripcion').val(),
            periodicidad:$('#inPeriodo').val()
        };   
              
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        var type = "PUT"; //for creating new resource
        var my_url = $('meta[name="_url"]').attr('content');
        var url_target=(""+$('meta[name="_urlTarget"]').attr('content'));
        var state=$(this).val();
        var ide_item = $('#ide_item').val();
                
        if(state=='add'){
            type='POST';          
        }else{
            my_url += '/' + ide_item;
        }
        
        console.log(formData);
        console.log("Enviando url "+my_url);
        
        //alert("type "+type+" url "+my_url);
        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data); 
                var item='<tr class="even gradeA" id="item'+data.ide_proyecto+'">';
                item+='<td>'+data.fecha_proyecto+'</td>';
                item+='<td>'+(data.fecha_cierre?data.fecha_cierre:'')+'</td>';
                item+='<td><a href="'+url_target+"/"+data.ide_proyecto+'">'+data.descripcion+'</a></td>';
                item+='<td>'+data.periodicidad.descripcion+'</td>';
                item+='<td>'+data.estado+'</td>';
                item+='<td>';
                item+='<button class="btn btn-primary btn-editar" value="'+data.ide_proyecto+'"><i class="icon-pencil icon-white" ></i> Editar</a>';
                item+='<button class="btn btn-danger" value="'+data.ide_proyecto+'"><i class="icon-remove icon-white"></i> Eliminar</button>';
                item+='</td>';
                item+='</tr>';
                
                if (state == "add"){ 
                    dataTable.rows.add($(item)).draw();                    
                }else{ 
                     dataTable.row( $('#item'+ide_item)).remove();
                     dataTable.rows.add($(item)).draw();
                }
//                $('#formAgregar').trigger("reset");
                $('#agregarEditarModal').modal('hide');
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
                    errHTML+='<li>Error al guardar la plantilla.</li>';
                }
                console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');                
            }
        });
    });
});


