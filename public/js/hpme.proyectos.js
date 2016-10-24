/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTableItems').DataTable(window.lang);
    var url = window.location;
    url=(""+url).replace("#","");
    $.configureBoxes();
    
    //Clic sobre el botón eliminar para un item de la tabla
    $( document ).on( 'click', '.btnEliminarItem', function() {
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
                console.log('####Error:', data);
                alert('Error borrado '+data);
            }
        });
        
        //Se oculta el popup de confirmación.
        $('#eliminarModal').modal('hide');
    });
    
    //Agregar nuevo usuario
    $('#btnAgregar').click(function(){
        $('#loading').modal('show');
        $('#inputTitle').html("Agregar Proyecto");
        $('#formAgregar').trigger("reset");
        
        $('#box1View').html('');
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        $.ajax({
            type: "POST",
            url: url + '/regiones',
            success: function (data) {
                console.log(data);
                var htmlView=''; 
                for(var d in data){
                    htmlView+='<option value="'+data[d].ide_region+'" title="'+data[d].nombre+'">'+data[d].nombre+"</option>";
                }
                $('#box1View').html(htmlView);
                $('#loading').modal('hide');
            },
            error: function (data) {
                console.log('Error:', data);
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al obtener regiones para el proyecto.</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show'); 
                $('#loading').modal('hide');
            }
        });
        
        $.post(url + '/' + 'regiones', function (data) {
            //success data
            console.log(data);
            
        });       
        $('#box2View').html('');     
        $('#btnGuardar').val('add');
        $('#agregarEditarModal').modal('show');
    });
    
    $(document).on('click','.btn-editar',function(){
        $('#loading').modal('show');
        var ide_item=$(this).val();
        $('#inputTitle').html("Editar Proyecto");
        $.get(url + '/' + ide_item, function (data) {
            //success data
            console.log(data);
            $('#inNombre').val(data.item.nombre);
            $('#inDescripcion').val(data.item.descripcion);
            var box1HTML='';
            var box2HTML='';
            
            if(data.item.hasOwnProperty("regiones") && data.item.regiones.length>0){
                for(var d in data.item.regiones){
                    box2HTML+='<option value="'+data.item.regiones[d].ide_region+'" title="'+data.item.regiones[d].nombre+'">'+data.item.regiones[d].nombre+"</option>";
                }   
            }
            if(data.hasOwnProperty("regiones") && data.regiones.length>0){
                for(var d in data.regiones){
                    box1HTML+='<option value="'+data.regiones[d].ide_region+'" title="'+data.regiones[d].nombre+'">'+data.regiones[d].nombre+"</option>";
                }   
            }
            $('#box1View').html(box1HTML);
            $('#box2View').html(box2HTML);
            
            $('#btnGuardar').val('update');
            $('#agregarEditarModal').modal('show');
            $('#ide_item').val(data.item.ide_proyecto);
            $('#loading').modal('hide');
        }); 
    });    

    //create new task / update existing task
    $("#btnGuardar").click(function (e) {      
        $('#loading').modal('show'); 
        var itemsData=[];
        
        $("#box2View option").each(function(){
            itemsData.push($(this).val());
        });
        
        var formData = {
            nombre: $('#inNombre').val(),
            descripcion: $('#inDescripcion').val(),
            regiones: itemsData
        };   
       
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

        console.log(formData);
        console.log("Enviando url "+my_url);
        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data); 
                var item = '<tr class="even gradeA" id="item'+data.ide_proyecto+'">'
                    item+='<td>'+data.nombre+'</td>'
                    item+='<td>'+data.descripcion+'</td>';
                    item+='<td><button class="btn btn-primary btn-editar" value="'+data.ide_proyecto+'"><i class="icon-pencil icon-white" ></i> Editar</button>';
                    item+='<button class="btn btn-danger btnEliminarItem" value="'+data.ide_proyecto+'"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
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
                console.log('Error:', data);
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al guardar el proyecto.</li>';
                }
                console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');                
            }
        });
    });
});