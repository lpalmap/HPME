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
    
    //Agregar nuevo usuario
    $(document).on('click','.btn2',function(){
        $('#inputTitle2').html("Planificaci&oacute;n");
        $('#formAgregar2').trigger("reset");
        $('#btnGuardar2').val('add');
        $('#agregarEditarModal2').modal('show');
        //alert('click');
    });
    
//    function calc(val){
//        $('#totalInput').html()
//    }
    
    //Agregar nuevo
    $('#btnAgregar').click(function(){
        $('#loading').modal('show');
        $('#inputTitle').html("Agregar Producto");
        //$('#formAgregar').trigger("reset");
        $('#formAgregar').empty();
        
        var my_url=(""+$('meta[name="_url"]').attr('content'))+"/all";
        var ideIndicadorArea=$('meta[name="_proyectoindicador"]').attr('content');
        var formData = {
            ide_indicador_area:ideIndicadorArea
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
                        item+='<div class="checkbox"><input type="checkbox" name="ckItem" value="'+data[i].ide_producto+'"/><label>'+data[i].nombre+'</label></div>'; 
                    }
                    if(item.length>0){
                       $('#formAgregar').html(item);
                    }else{
                       $ ('#formAgregar').html("<li>No hay Productos pendientes de agregar.</li>");  
                    }
                    $('#agregarEditarModal').modal('show'); 
                },
                error: function (data) {
                    $('#loading').modal('hide');
                    console.log('Error:', data);
                    $('#agregarEditarModal').modal('hide'); 
                    $("#erroresContent").html("<li>Error al agregar Producto</li>"); 
                    $('#erroresModal').modal('show');              
                }
            });   
        $('#loading').modal('hide');
    });
    
    //Clic sobre el botón eliminar en el popup de confirmación
    $('#btnEliminar').click(function(){
        $('#loading').modal('show');
        //Se obtiene el id del elemento a eliminar
        var item_id = $(this).val();
        url=(""+$('meta[name="_url"]').attr('content'));
       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
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
                console.log('Error borrando objetivo:', data);
                $("#erroresContent").html("<li>Error al borrar el Producto</li>"); 
                $('#erroresModal').modal('show');
            }
        });
    });
     
    //create new task / update existing task
    $("#btnGuardar").click(function (e) {  
        $('#loading').modal('show');
        var text = '{ "items" : [';
        var seleccion=false;
        $("input[name='ckItem']").each(function() {
            if(this.checked){
                if(seleccion){
                    text+=',{"ide_producto":"'+this.value+'"}';
                }else{
                    text+='{"ide_producto":"'+this.value+'"}';
                    seleccion=true;
                }           
            }
            //console.log( this.value + ":" + this.checked );
        });
        
        
        if(seleccion){
            //alert(text);
            var my_url=(""+$('meta[name="_url"]').attr('content'));
            var url_target=(""+$('meta[name="_urlTarget"]').attr('content'));
            var ide_indicador_area=$('meta[name="_proyectoindicador"]').attr('content');
            text+='],"ide_indicador_area":"'+ide_indicador_area+'"}';
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
                        item='<tr class="even gradeA" id="item'+data[i].ide_producto_indicador+'">';
                        item+='<td><label>'+data[i].producto.nombre+'</label></td>';
                        item+='<td><button class="btn btn-danger" value="'+data[i].ide_producto_indicador+'"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
                        dataTable.rows.add($(item)).draw(); 
                    }
                    $('#formAgregar').trigger("reset");
                    $('#agregarEditarModal').modal('hide');
                    $('#loading').modal('hide');
                },
                error: function (data) {
                    $('#loading').modal('hide');
                    console.log('Error:', data);
                    $("#erroresContent").html("<li>Error al agregar el Producto</li>"); 
                    $('#erroresModal').modal('show');
                    //alert(data.responseJSON.nombre);                
                }
            });
            
        }else{
            $("#erroresContent").html("<li>Debe seleccionar un Producto</li>"); 
            $('#erroresModal').modal('show');
            $('#loading').modal('hide');
        }
    });
});