/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTableItems').DataTable({
        "order": [[ 1, "asc" ]],
        "language": window.lang.language
    });
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
        $('#inputTitle').html("Agregar Objetivo");
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
                    var item='';
                    for(var i in data){
                        item+='<div class="checkbox"><input type="checkbox" name="ckItem" value="'+data[i].ide_objetivo+'"/><label>'+data[i].nombre+'</label></div>'; 
                    }
                    if(item.length>0){
                       $('#formAgregar').html(item);
                    }else{
                       $ ('#formAgregar').html("<li>No hay objetivos pendientes de agregar.</li>");  
                    }
                    $('#agregarEditarModal').modal('show'); 
                },
                error: function (data) {
                    $('#loading').modal('hide');
                    var errHTML="";
                    if((typeof data.responseJSON != 'undefined')){
                        for( var e in data.responseJSON){
                            errHTML+="<li>"+data.responseJSON[e]+"</li>";
                        }
                    }else{
                        errHTML+='<li>Error al guardar el objetivo.</li>';
                    }
                    $("#erroresContent").html(errHTML); 
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
                dataTable.row( $('#item'+item_id)).remove().draw();
                $('#eliminarModal').modal('hide');
                $('#loading').modal('hide');
            },
            error: function (data) {
                $('#loading').modal('hide');
                $("#erroresContent").html("<li>Error al borrar el objetivo.</li>"); 
                $('#erroresModal').modal('show');
            }
        });
    });
     
    $("#btnGuardar").click(function (e) {  
        $('#loading').modal('show');
        var text = '{ "items" : [';
        var seleccion=false;
        $("input[name='ckItem']").each(function() {
            if(this.checked){
                if(seleccion){
                    text+=',{"ide_objetivo":"'+this.value+'"}';
                }else{
                    text+='{"ide_objetivo":"'+this.value+'"}';
                    seleccion=true;
                }           
            }
        });
        
        if(seleccion){
            var my_url=(""+$('meta[name="_url"]').attr('content'));
            var url_target=(""+$('meta[name="_urlTarget"]').attr('content'));
            var ide_proyecto_meta=$('meta[name="_proyectometa"]').attr('content');
            text+='],"ide_proyecto_meta":"'+ide_proyecto_meta+'"}';
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
                    var item;
                    for(var i in data){
                        item='<tr class="even gradeA" id="item'+data[i].ide_objetivo_meta+'">';
                        item+='<td><a href="'+url_target+'/'+data[i].ide_objetivo_meta+'">'+data[i].objetivo.nombre+'</a></td>';
                        item+='<td>'+data[i].objetivo.orden+'</td>';
                        item+='<td><button class="btn btn-danger" value="'+data[i].ide_objetivo_meta+'"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
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
                        errHTML+='<li>Error al guardar el objetivo</li>';
                    }
                    $("#erroresContent").html(errHTML); 
                    $('#erroresModal').modal('show');                  
                }
            });
            
        }else{
            $("#erroresContent").html("<li>Debe seleccionar un objetivo</li>"); 
            $('#erroresModal').modal('show');
            $('#loading').modal('hide');
        }
    });
});