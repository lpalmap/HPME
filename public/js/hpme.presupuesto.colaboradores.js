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
         
    $('#btnGuardar').click(function(){
        $('#loading').modal('show');
        //$('#formAgregar').trigger("reset");
        var ideColaborador=$('#inColaborador').val();
        if(ideColaborador){
            var my_url=(""+$('meta[name="_url"]').attr('content')).replace("#","")+"/colaborador";
            var idePresupuestoDepartamento=$('meta[name="_departamento"]').attr('content');
            var imgConsolidado=$('meta[name="_imgConsolidado"]').attr('content');
            var formData = {
                ide_presupuesto_departamento:idePresupuestoDepartamento,
                ide_colaborador:ideColaborador
            };   

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                    type: 'PUT',
                    url: my_url,
                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        console.log(data); 
                        var item = '<tr class="even gradeA" id="item'+data.ide_presupuesto_colaborador+'">';
                            item+= '<td style="text-align: center">'+data.fecha_ingreso+'</td>';
                            item+= '<td style="text-align: center"><a href="">'+data.nombres+' '+data.apellidos+'</a></td>';
                            item+= '<td style="text-align: center">';
                            item+= '<button class="btn btn-danger btnEliminarItem" value="'+data.presupuesto_colaborador+'"><i class="icon-remove icon-white"></i></button>&nbsp;&nbsp;&nbsp;';
                            item+='<a href="" ><img src="'+imgConsolidado+'" class="menu-imagen" alt="" title="Ver resumen consolidado"/></a>';                                           
                            item+='</td></tr>';                      
                        dataTable.rows.add($(item)).draw();                    
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
                            errHTML+='<li>Error al agregar colaborador.</li>';
                        }
                        console.log('Error:', data);
                        $("#erroresContent").html(errHTML); 
                        $('#erroresModal').modal('show'); 

                    }
            }); 
        }else{
            $('#loading').modal('hide');
            $("#erroresContent").html('<li>No hay colaboradores pendientes para agregar.</li>'); 
            $('#erroresModal').modal('show'); 
        }
          
        
    });
});