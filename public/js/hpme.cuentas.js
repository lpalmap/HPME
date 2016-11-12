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
    
    //Clic sobre el botón eliminar en el popup de confirmación
    $('#btnEliminar').click(function(){
        $('#loading').modal('show');
        //Se obtiene el id del elemento a eliminar
        var item_id = $(this).val();
       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var url=(""+$('meta[name="_url"]').attr('content')).replace("#","");
        //Se hace el request con ajax a la url para eliminar el item
        $.ajax({
            type: "DELETE",
            url: url + '/' + item_id,
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
        
        //Se oculta el popup de confirmación.
        $('#eliminarModal').modal('hide');
    });
    
    //Agregar nuevo usuario
    $('#btnAgregar').click(function(){
        $('#inputTitle').html("Agregar Cuenta");
        $('#formAgregar').trigger("reset");
        $('#btnGuardar').val('add');
        $('#agregarEditarModal').modal('show');
    });
    
    $(document).on('click','.btn-editar',function(){
        $('#loading').modal('show');
        var ide_item=$(this).val();
        $('#inputTitle').html("Editar Cuenta");
        var url=(""+$('meta[name="_url"]').attr('content')).replace("#","");
        $.get(url + '/' + ide_item, function (data) {
            $('#inCuenta').val(data.cuenta);
            $('#inNombre').val(data.nombre);
            $('#inDescripcion').val(data.descripcion);
            if(data.ind_consolidar=='S'){
                $('#inConsolidar').prop('checked', true);
            }else{
                $('#inConsolidar').prop('checked', false);
            }
            if(data.estado=='ACTIVA'){
               $('#inActiva').prop('selected', true);
               $('#inInactiva').prop('selected', false);
            }else{
               $('#inActiva').prop('selected', false);
               $('#inInactiva').prop('selected', true);  
            }
            //$('#inDescripcion').val(data.descripcion);
            $('#btnGuardar').val('update');
            $('#agregarEditarModal').modal('show');
            $('#ide_item').val(data.ide_cuenta);
            $('#loading').modal('hide');
        }); 
    });    

    //create new task / update existing task
    $("#btnGuardar").click(function (e) {   
        $('#loading').modal('show');
        var consolida='N';
        if($('#inConsolidar').prop('checked')){
            consolida='S';
        }
        
        var cuenta_padre=(""+$('meta[name="_cuentaPadre"]').attr('content')).replace("#","");
        
        var formData = {
            cuenta: $('#inCuenta').val(),
            nombre: $('#inNombre').val(),
            descripcion: $('#inDescripcion').val(),
            ind_consolidar:consolida,
            estado: $('#inEstado').val(),
            ide_cuenta_padre: cuenta_padre
        };   
        
        $('#loading').modal('show');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        var url=(""+$('meta[name="_url"]').attr('content')).replace("#","");
        var urlTarget=(""+$('meta[name="_urlTarget"]').attr('content')).replace("#","");
        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#btnGuardar').val();

        var type = "POST"; //for creating new resource
        var ide_item = $('#ide_item').val();;

        if (state == "update"){
            type = "PUT"; //for updating existing resource
            url += '/' + ide_item;
        }
        $.ajax({
            type: type,
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data); 
                var item = '<tr class="even gradeA" id="item'+data.ide_cuenta+'">';
                    item+='<td>'+data.cuenta+'</td>';
                    item+='<td><a href="'+urlTarget+'/'+data.ide_cuenta+'">'+data.nombre+'</a></td>';
                    item+='<td>'+data.descripcion+'</td>';
                    if(data.ind_consolidar=='S'){
                        item+='<td>SI</td>';
                    }else{
                        item+='<td>NO</td>';
                    }
                    if(data.estado=='ACTIVA'){
                        item+='<td>Activa</td>';
                    }else{
                        item+='<td>Inactiva</td>';
                    }                   
                    item+='<td><button class="btn btn-primary btn-editar" value="'+data.ide_cuenta+'"><i class="icon-pencil icon-white" ></i> Editar</button>';
                    item+='<button class="btn btn-danger" value="'+data.ide_cuenta+'"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
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
    });
});