/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTableItems').DataTable(window.lang);
    var url = window.location;
    url=(""+url).replace("#","");
    var inVal;
    var inVal2;
    var inVal3;
    var inVal4;
     
    function recalcTotal(){
        inVal=parseInt($('#primerTrim').val());
        if(isNaN(inVal) || inVal<0){
            inVal=0;
            $('#primerTrim').val('');
        }
        
        inVal2=parseInt($('#segundoTrim').val());
        if(isNaN(inVal2) || inVal2<0){
            inVal2=0;
            $('#segundoTrim').val('');
        }
        
        inVal3=parseInt($('#tercerTrim').val());
        if(isNaN(inVal3) || inVal3<0){
            inVal3=0;
            $('#tercerTrim').val('');
        }
            
        inVal4=parseInt($('#cuartoTrim').val());
        if(isNaN(inVal4) || inVal4<0){
            inVal4=0;
            $('#cuartoTrim').val('');
        }
        
        $("#totalInput").val(inVal+inVal2+inVal3+inVal4);  
        
    };
   
    $("#primerTrim").keyup(function(){
        recalcTotal();
    });
    
    $("#segundoTrim").keyup(function(){   
        recalcTotal();
    });
    
    $("#tercerTrim").keyup(function(){   
        recalcTotal();
    });
    
    $("#cuartoTrim").keyup(function(){   
        recalcTotal();
    });
    //Clic sobre el botón eliminar para un item de la tabla
    $( document ).on( 'click', '.btn-danger', function() {
        $('#btnEliminar').val($(this).val());
        $('#eliminarModal').modal('show');
    });
    
    //Agregar nuevo usuario
    $(document).on('click','.btn2',function(){
        $('#loading').modal('show');
        var ideProyecto=$('meta[name="_proyecto"]').attr('content');
        var ideProductoIndicador=$(this).val();
        
        var formData = {
            ide_proyecto:ideProyecto,
            ide_producto_indicador:ideProductoIndicador
        }; 
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        var url=(""+$('meta[name="_url"]').attr('content'))+'/retriveDetalle';
        
        $('#formAgregarDetalle').trigger("reset");
        $('#btnGuardarDetalle').val(ideProductoIndicador);
        $('#ingresarDetalleModal').modal('show');
        $('#inProyecto').val(0);
        var HTMLProyectos='<option value="0"></option>';
        $('#inProyecto').html(HTMLProyectos);
        
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                var proyectoSelected=0;
                if(data.item){
                    $('#inDescripcion').val(data.item.descripcion);
                    for(var i in data.item.detalle){
                        var value=parseInt(data.item.detalle[i].valor);
                        var numItem=parseInt(data.item.detalle[i].num_detalle);
                        if(numItem===1){
                            $('#primerTrim').val(value);
                        }else{
                            if(numItem===2){
                                $('#segundoTrim').val(value);
                            }else{
                                if(numItem===3){
                                    $('#tercerTrim').val(value);
                                }else{
                                    $('#cuartoTrim').val(value); 
                                }
                            }
                        }
                    }
                    if(data.item.ide_proyecto){
                        proyectoSelected=data.item.ide_proyecto; 
                    }else{
                        $('#inProyecto').val(0);   
                    }              
               }
               if(data.proyectos){
                   for(var i in data.proyectos){
                       var selected='';
                       if(data.proyectos[i].ide_proyecto===proyectoSelected){
                           selected='selected';
                       }
                       HTMLProyectos+='<option title="'+data.proyectos[i].descripcion+'" value="'+data.proyectos[i].ide_proyecto+'" '+selected+'>'+data.proyectos[i].nombre+'</option>';
                   }
                   console.log(HTMLProyectos);
                   $('#inProyecto').html(HTMLProyectos);
               }
               recalcTotal();
               $('#loading').modal('hide');
           },   
            error: function (data) {
                console.log(data);
                $('#ingresarDetalleModal').modal('hide');
                $('#loading').modal('hide');
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( var e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al obtener detalle del producto</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');  
            }
        });
        
    });
    
    //Agregar nuevo
    $('#btnAgregar').click(function(){
        $('#loading').modal('show');
        $('#inputTitle').html("Agregar Producto");
        //$('#formAgregar').trigger("reset");
        $('#formAgregar').empty();
        
        var my_url=(""+$('meta[name="_url"]').attr('content'))+"/all";
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
                        item+='<div class="checkbox"><input type="checkbox" name="ckItem" value="'+data[i].ide_producto+'"/><label>'+data[i].nombre+'</label></div>'; 
                    }
                    if(item.length>0){
                       $('#formAgregar').html(item);
                    }else{
                       $ ('#formAgregar').html("<li>No hay productos pendientes de agregar.</li>");  
                    }
                    $('#agregarEditarModal').modal('show'); 
                },
                error: function (data) {
                    $('#loading').modal('hide');
                    console.log('Error:', data);
                    $('#agregarEditarModal').modal('hide'); 
                    $("#erroresContent").html("<li>Error al agregar producto</li>"); 
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
                $("#erroresContent").html("<li>Error al borrar el producto</li>"); 
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
                    var errHTML="";
                    if((typeof data.responseJSON != 'undefined')){
                        for( var e in data.responseJSON){
                            errHTML+="<li>"+data.responseJSON[e]+"</li>";
                        }
                    }else{
                        errHTML+='<li>Error al guardar el producto</li>';
                    }
                    $("#erroresContent").html(errHTML); 
                    $('#erroresModal').modal('show');               
                }
            });
            
        }else{
            $("#erroresContent").html("<li>Debe seleccionar un producto</li>"); 
            $('#erroresModal').modal('show');
            $('#loading').modal('hide');
        }
    });
    
    $("#btnGuardarDetalle").click(function (e) { 
        $('#loading').modal('show');
        
        
        var ideProyecto=$('meta[name="_proyecto"]').attr('content');
        var ideProductoIndicador=$("#btnGuardarDetalle").val();
        var descripcion=$("#inDescripcion").val();
        var proyecto=$('#inProyecto').val();
        var item1=parseInt($('#primerTrim').val());
        if(isNaN(item1) || item1<0){
            item1=0;
        }
        
        var item2=parseInt($('#segundoTrim').val());
        if(isNaN(item2) || item2<0){
            item2=0;
        }
        
        var item3=parseInt($('#tercerTrim').val());
        if(isNaN(item3) || item3<0){
            item3=0;
        }
            
        var item4=parseInt($('#cuartoTrim').val());
        if(isNaN(item4) || item4<0){
            item4=0;
        }
        
        var items={
            item1:item1,
            item2:item2,
            item3:item3,
            item4:item4
        };
        
        var formData = {
            ide_proyecto:ideProyecto,
            ide_producto_indicador:ideProductoIndicador,
            descripcion:descripcion,
            items:items,
            proyecto:proyecto
        }; 
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        var url=(""+$('meta[name="_url"]').attr('content'))+'/addDetalle';
        
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $('#ingresarDetalleModal').modal('hide');
                //console.log("test "+data.region);
                $('#loading').modal('hide');
            },
            error: function (data) {
                console.log(data);
                $('#ingresarDetalleModal').modal('hide');
                $('#loading').modal('hide');
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( var e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al guardar el producto</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');  
            }
        });
    });
    
});