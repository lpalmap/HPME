/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTableItems').DataTable(window.lang);
    var url = window.location;
    url=(""+url).replace("#","");
    
    //Clic sobre el botón eliminar para un item de la tabla
    $( document ).on( 'click', '.btn-danger', function() {
        $('#btnEliminar').val($(this).val());
        $('#eliminarModal').modal('show');
    });
    
    //Agregar nuevo
    $('#btnAgregar').click(function(){
        $('#loading').modal('show');
        $('#inputTitle').html("Agregar Meta");
        //$('#formAgregar').trigger("reset");
        $('#formAgregar').empty();
        
        var my_url=(""+$('meta[name="_url"]').attr('content')).replace("#","")+"/all";
        var ideProyecto=$('meta[name="_proyecto"]').attr('content');
        var formData = {
            ide_proyecto:ideProyecto
        };   
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        $.ajax({
                type: 'POST',
                url: my_url,
                data: formData,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    var item='';
                    for(var i in data){
                        item+='<div class="checkbox"><input type="checkbox" name="ckMeta" value="'+data[i].ide_meta+'"/><label>'+data[i].nombre+'</label></div>'; 
                    }
                    if(item.length>0){
                       $('#formAgregar').html(item);
                    }else{
                       $ ('#formAgregar').html("<li>No hay metas pendientes de agregar.</li>");  
                    }
                    $('#agregarEditarModal').modal('show'); 
                },
                error: function (data) {
                    $('#loading').modal('hide');
                    console.log('Error:', data);
                    $('#agregarEditarModal').modal('hide'); 
                    $("#erroresContent").html("<li>Error al agregar metas</li>"); 
                    $('#erroresModal').modal('show');              
                }
            });   
        $('#loading').modal('hide');
    });
    
    
    
    
    $( document ).on( 'click', '.uniform', function() {
        $('#loading').modal('show');
        url=(""+$('meta[name="_url"]').attr('content')).replace("#","");
        
        var formData = {
            ind_obligatorio: this.checked?'S':'N'
        };         
    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        //[update=PUT]

        var type = "PUT"; //for creating new resource
        var my_url = url+'/'+$(this).val();
        console.log(formData);
        console.log("Enviando url "+my_url);
        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data); 
                $('#loading').modal('hide');
            },
            error: function (data) {
                $('#loading').modal('hide');
                var errHTML="<li>Error al actualizar la meta</li>";
                console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');               
            }
        });
        
    });
    //Clic sobre el botón eliminar en el popup de confirmación
    $('#btnEliminar').click(function(){
        $('#loading').modal('show');
        //Se obtiene el id del elemento a eliminar
        var item_id = $(this).val();
        url=(""+$('meta[name="_url"]').attr('content')).replace("#","");
       
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
                dataTable.row( $('#item'+item_id)).remove().draw();
                $('#eliminarModal').modal('hide');
                $('#loading').modal('hide');
            },
            error: function (data) {
                $('#loading').modal('hide');
                console.log('Error borrando meta:', data);
                $("#erroresContent").html("<li>Error al borrar la meta.</li>"); 
                $('#erroresModal').modal('show');
            }
        });
    });
    
    $(document).on('click','.btn-editar',function(){
        $('#loading').modal('show');
        var ide_item=$(this).val();
        $('#inputTitle').html("Editar Plantilla");
        $.get(url + '/' + ide_item, function (data) {
            //success data
            console.log(data);
            $('#inNombre').val(data.nombre);
            $('#inDescripcion').val(data.descripcion);
            $('#btnGuardar').val('update');
            $('#agregarEditarModal').modal('show');
            $('#ide_item').val(data.ide_meta);
            $('#loading').modal('hide');
        }) 
    });    

    //create new task / update existing task
    $("#btnGuardar").click(function (e) {  
        $('#loading').modal('show');
        var text = '{ "metas" : [';
        var seleccion=false;
        $("input[name='ckMeta']").each(function() {
            if(this.checked){
                if(seleccion){
                    text+=',{"ide_meta":"'+this.value+'"}';
                }else{
                    text+='{"ide_meta":"'+this.value+'"}';
                    seleccion=true;
                }           
            }
            //console.log( this.value + ":" + this.checked );
        });
        
        
        if(seleccion){
            //alert(text);
            var my_url=(""+$('meta[name="_url"]').attr('content'));
            var ide_proyecto=$('meta[name="_proyecto"]').attr('content');
            var url_target=(""+$('meta[name="_urlTarget"]').attr('content'));
            text+='],"ide_proyecto":"'+ide_proyecto+'"}';
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
            });
            
            $.ajax({
                type: 'POST',
                url: my_url,
                data: JSON.parse(text),
                dataType: 'json',
                success: function (data) {
                    console.log(data);
//                    var item = '<tr class="even gradeA" id="item'+data.ide_meta+'">'
//                    item+='<td>'+data.nombre+'</td>'
//                    item+='<td>'+data.descripcion+'</td>';
//                    item+='<td><button class="btn btn-primary btn-editar" value="'+data.ide_meta+'"><i class="icon-pencil icon-white" ></i> Editar</button>';
//                    item+='<button class="btn btn-danger" value="'+data.ide_meta+'"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
//                    
//                  
                    var item;
                    for(var i in data){
                        console.log(data[i].ide_proyecto_meta);
                        console.log(data[i].meta.descripcion);
                        item='<tr class="even gradeA" id="item'+data[i].ide_proyecto_meta+'">';
                        item+='<td><a href="'+url_target+'/'+data[i].ide_proyecto_meta+'">'+data[i].meta.nombre+'</a></td>';
                        item+='<td style="text-align: center"><div class="checkbox">';
                        item+='<input class="uniform" type="checkbox" value="'+data[i].ide_proyecto_meta+'" checked/></div><td>'
                        item+='<button class="btn btn-danger" value="'+data[i].ide_proyecto_meta+'"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
                        dataTable.rows.add($(item)).draw(); 
                    }
                    $('#formAgregar').trigger("reset");
                    $('#agregarEditarModal').modal('hide');
                    $('#loading').modal('hide');
                },
                error: function (data) {
                    $('#loading').modal('hide');
                    console.log('Error:', data);
                    $("#erroresContent").html("<li>Error al agregar metas</li>"); 
                    $('#erroresModal').modal('show');
                    //alert(data.responseJSON.nombre);                
                }
            });
            
        }else{
            $("#erroresContent").html("<li>Debe seleccionar una meta</li>"); 
            $('#erroresModal').modal('show');
            $('#loading').modal('hide');
        }
    });
});


