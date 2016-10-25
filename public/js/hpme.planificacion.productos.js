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
});