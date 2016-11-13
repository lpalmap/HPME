/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTableItems').DataTable(window.lang);
    
    //Clic sobre el botón eliminar para un item de la tabla
    $( document ).on( 'click', '.btn-danger', function() {
        $('#btnEliminar').val($(this).val());
        $('#eliminarModal').modal('show');
    });
    
    //Agregar nuevo
    $('#btnAgregar').click(function(){
        $('#loading').modal('show');
        $('#inputTitle').html("Agregar Presupuesto Colaborador");
        //$('#formAgregar').trigger("reset");
        $('#inColaborador').html('');
        
        var my_url=(""+$('meta[name="_url"]').attr('content')).replace("#","")+"/all";
        var idePresupuestoDepartamento=$('meta[name="_departamento"]').attr('content');
        var formData = {
            ide_presupuesto_departamento:idePresupuestoDepartamento
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
                        item+='<option value="'+data[i].ide_colaborador+'">'+data[i].nombres+" "+data[i].apellidos+'</option>'; 
                    }
                    if(item.length>0){
                       $('#inColaborador').html(item);
                    }else{
                       $ ('#inColaborador').html('');  
                    }
                    $('#agregarEditarModal').modal('show'); 
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
                        errHTML+='<li>Error al obtener colaboradores.</li>';
                    }
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
                console.log('Error borrando meta:', data);
                $("#erroresContent").html("<li>Error al borrar la meta.</li>"); 
                $('#erroresModal').modal('show');
            }
        });
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
                        item+='<input class="uniform" type="checkbox" value="'+data[i].ide_proyecto_meta+'" checked/></div><td>';
                        item+='<button class="btn btn-danger" value="'+data[i].ide_proyecto_meta+'"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
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
                        errHTML+='<li>Error al guardar la meta.</li>';
                    }
                    $("#erroresContent").html(errHTML); 
                    $('#erroresModal').modal('show');                  
                }
            });
            
        }else{
            $("#erroresContent").html("<li>Debe seleccionar una meta</li>"); 
            $('#erroresModal').modal('show');
            $('#loading').modal('hide');
        }
    });
});