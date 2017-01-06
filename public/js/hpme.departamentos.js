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
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al borrar el departamento.</li>';
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
        $('#loading').modal('show');
        $('#inputTitle').html("Agregar Departamento");
        $('#formAgregar').trigger("reset");
        $('#inAdmin').val(0);
        
        var selectHTML='<option value="0"></option>';
        var selectHTMLCont='<option value="0"></option>';
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var data={
            operacion:"retrive"            
        };
        
        $.ajax({
            type: "POST",
            url: url+'/admin',
            data: data,
            dataType: 'json',
            success: function (data) {
                //console.log(data); 
                for(var u in data.admins){
                    selectHTML+='<option value="'+data.admins[u].ide_usuario+'">'+data.admins[u].usuario+'&nbsp;('+data.admins[u].nombres+'&nbsp;'+data.admins[u].apellidos+')</option>';
                }
                for(var u in data.contadores){
                    selectHTMLCont+='<option value="'+data.contadores[u].ide_usuario+'">'+data.contadores[u].usuario+'&nbsp;('+data.contadores[u].nombres+'&nbsp;'+data.contadores[u].apellidos+')</option>';
                }
                //console.log(selectHTML);
                $('#inAdmin').html(selectHTML); 
                $('#inContador').html(selectHTMLCont); 
                $('#loading').modal('hide');
            },
            error: function (data) {
                //console.log('Error:', data); 
                $('#inAdmin').html(selectHTML); 
                $('#inContador').html(selectHTMLCont); 
                $('#loading').modal('hide');
            }
        });
          
        $('#btnGuardar').val('add');
        $('#agregarEditarModal').modal('show');
    });
    
    $(document).on('click','.btn-editar',function(){
        $('#loading').modal('show');
        var ide_item=$(this).val();
        $('#inputTitle').html("Editar Departamento");
        $.get(url + '/' + ide_item, function (data) {
            //success data
            //console.log(data);
            $('#inNombre').val(data.item.nombre);
            $('#inDescripcion').val(data.item.descripcion);
            $('#inCodigo').val(data.item.codigo_interno);
            var selectHTML='<option value="0"></option>';
            var selectHTMLCont='<option value="0"></option>';
            if(data.item.hasOwnProperty("director")){
                $('#inAdmin').val(data.item.director.ide_usuario);
                selectHTML+='<option value="'+data.item.director.ide_usuario+'" selected>'+data.item.director.usuario+'&nbsp;('+data.item.director.nombres+'&nbsp;'+data.item.director.apellidos+')</option>';
                //console.log('admin '+data.item.director.ide_usuario);
            }else{
                $('#inAdmin').val(0);
                //console.log('no admin');
            }
            if(data.item.hasOwnProperty("contador") && data.item.contador){
                $('#inContador').val(data.item.contador.ide_usuario);
                selectHTMLCont+='<option value="'+data.item.contador.ide_usuario+'" selected>'+data.item.contador.usuario+'&nbsp;('+data.item.contador.nombres+'&nbsp;'+data.item.contador.apellidos+')</option>';
                //console.log('admin '+data.item.director.ide_usuario);
            }else{
                $('#inContador').val(0);
                //console.log('no admin');
            }
            
            for(var u in data.users){
                selectHTML+='<option value="'+data.users[u].ide_usuario+'">'+data.users[u].usuario+'&nbsp;('+data.users[u].nombres+'&nbsp;'+data.users[u].apellidos+')</option>';
            }
            
            for(var u in data.contadores){
                selectHTMLCont+='<option value="'+data.contadores[u].ide_usuario+'">'+data.contadores[u].usuario+'&nbsp;('+data.contadores[u].nombres+'&nbsp;'+data.contadores[u].apellidos+')</option>';
            }
            //$('#inAdmin').val(itemVal);
            $('#inAdmin').html(selectHTML);
            $('#inContador').html(selectHTMLCont);
            $('#btnGuardar').val('update');
            $('#agregarEditarModal').modal('show');
            $('#ide_item').val(data.item.ide_departamento);
            $('#loading').modal('hide');
        }); 
    });    

    //create new task / update existing task
    $("#btnGuardar").click(function (e) {
        var formData = {
            nombre: $('#inNombre').val(),
            descripcion: $('#inDescripcion').val(),
            ide_usuario_director: $('#inAdmin').val(),
            ide_usuario_contador: $('#inContador').val(),
            codigo_interno: $('#inCodigo').val()         
        };   
        $('#loading').modal('show');
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

        //console.log(formData);
        //console.log("Enviando url "+my_url);
        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                //console.log(data); 
                var item = '<tr class="even gradeA" id="item'+data.ide_departamento+'">';
                    item+='<td>'+data.nombre+'</td>';
                    item+='<td>'+data.descripcion+'</td>';
                    item+='<td>'+data.codigo_interno+'</td>';
                    if(data.hasOwnProperty("director")){
                        item+='<td>'+data.director.usuario+'</td>';                          
                    }else{
                        item+='<td></td>';
                    }
                    if(data.hasOwnProperty("contador") && data.contador){
                        item+='<td>'+data.contador.usuario+'</td>';                          
                    }else{
                        item+='<td></td>';
                    }
                    item+='<td><button class="btn btn-primary btn-editar" value="'+data.ide_departamento+'"><i class="icon-pencil icon-white" ></i> Editar</button>';
                    item+='<button class="btn btn-danger" value="'+data.ide_departamento+'"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
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
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al guardar el departamento.</li>';
                }
                //console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');               
            }
        });
    });
});