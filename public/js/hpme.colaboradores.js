/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTables-example').DataTable(window.lang);   
    var url = window.location;
    url=(""+url).replace("#","");
    
    $( document ).on( 'click', '.btn-danger', function() {
        $('#btnEliminar').val($(this).val());
        $('#buttonedModal').modal('show');
    });
//    $( '.btn-danger' ).on( 'click', 'button', function() {
//        $('#btnEliminar').val($(this).val());
//        $('#buttonedModal').modal('show');
//    });
    
    $('#btnEliminar').click(function(){
        var user_id = $(this).val();
        $('#loading').modal('show');      
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.ajax({
            type: "DELETE",
            url: url + '/' + user_id,
            success: function (data) {
                console.log(data);
                dataTable.row( $('#usuario'+user_id)).remove().draw();
                $('#loading').modal('hide');
            },
            error: function (data) {
                $('#loading').modal('hide');
                console.log('Error:', data);
                $('#loading').modal('hide');
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al borrar el colaborador/proyecto.</li>';
                }
                console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');  
            }
        });
        $('#buttonedModal').modal('hide');
    });
    
    //Agregar nuevo usuario
    $('#btnAgregar').click(function(){
        $('#inputTitle').html("Agregar Colaborador");
        $('#formAgregar').trigger("reset");
        $('#inRol').val(0);
        $('#inPuesto').val(0);
        $('#btnGuardar').val('add');
        $('#formModal').modal('show');
    });
    
    $('#btnAgregarProyecto').click(function(){
        $('#inputTitleProyecto').html("Agregar Proyecto");
        $('#formAgregarProyecto').trigger("reset");
        $('#inDepartamentoProyecto').val(0);
        $('#inPuesto').val(0);
        $('#btnGuardarProyecto').val('add');
        $('#agregarProyecto').modal('show');
    });
    
    $(document).on('click','.btn-editar-colaborador',function(){
        $('#loading').modal('show');
        var ide_usuario=$(this).val();
        $('#inputTitle').html("Editar Colaborador");
        $.get(url + '/' + ide_usuario, function (data) {
            //success data
            console.log(data);
            $('#inNombres').val(data.nombres);
            $('#inApellidos').val(data.apellidos);
            $('#ide_usuario').val(data.ide_colaborador);
            if(data.hasOwnProperty("departamento")){
                $('#inRol').val(data.departamento.ide_departamento);    
            }else{
                $('#inRol').val(0);
            }
            if(data.hasOwnProperty("puesto")){
                $('#inPuesto').val(data.puesto.ide_puesto);    
            }else{
                $('#inPuesto').val(0);
            }
            $('#btnGuardar').val('update');
            $('#formModal').modal('show');
            $('#loading').modal('hide');
        });      
    });    

    $(document).on('click','.btn-editar-proyecto',function(){
        $('#loading').modal('show');
        var ide_usuario=$(this).val();
        $('#inputTitleProyecto').html("Editar Proyecto");
        $.get(url + '/' + ide_usuario, function (data) {
            //success data
            //console.log(data);
            $('#inNombreProyecto').val(data.nombres);
            $('#ide_col_proyecto').val(data.ide_colaborador);
            if(data.hasOwnProperty("departamento")){
                $('#inDepartamentoProyecto').val(data.departamento.ide_departamento);    
            }else{
                $('#inDepartamentoProyecto').val(0);
            }
            $('#btnGuardarProyecto').val('update');
            $('#agregarProyecto').modal('show');
            $('#loading').modal('hide');
        });      
    });  
    
    //create new task / update existing task
    $("#btnGuardar").click(function (e) {
       $('#loading').modal('show');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var formData = {
            tipo:'Colaborador',
            nombres: $('#inNombres').val(),
            apellidos: $('#inApellidos').val(),
            ide_departamento: $('#inRol').val(),
            ide_puesto: $('#inPuesto').val()
        };

        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#btnGuardar').val();

        var type = "POST"; //for creating new resource
        var ide_usuario = $('#ide_usuario').val();;
        var my_url = url;

        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + ide_usuario;
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
                var item = '<tr class="even gradeA" id="usuario' + data.ide_colaborador+ '">';
                    item+='<td>'+data.tipo+'</td>'+'<td>' + data.nombres + '</td><td>' + data.apellidos+ '</td>';
                    if(data.hasOwnProperty("puesto")){
                        item+='<td>'+data.puesto.nombre+'</td>';                          
                    }else{
                        item+='<td></td>';
                    }
                    if(data.hasOwnProperty("departamento")){
                        item+='<td>'+data.departamento.nombre+'</td>';                          
                    }else{
                        item+='<td></td>';
                    }
                    item += '<td><button class="btn btn-primary btn-editar btn-editar-colaborador" value="' + data.ide_colaborador + '"><i class="icon-pencil icon-white" ></i> Editar</button>';
                    item += '<button class="btn btn-danger" value="' + data.ide_colaborador + '"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
                if (state == "add"){ 
                    dataTable.rows.add($(item)).draw();                    
                }else{
                     dataTable.row( $('#usuario'+ide_usuario)).remove();
                     dataTable.rows.add($(item)).draw();
                }
                $('#formAgregar').trigger("reset");
                $('#formModal').modal('hide');
                $('#loading').modal('hide');
            },
            error: function (data) {
                $('#loading').modal('hide');
                //console.log('Error:', data);
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al guardar el colaborador.</li>';
                }
                //console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');  
            }
        });
        
    });
    
    $("#btnGuardarProyecto").click(function (e) {
       $('#loading').modal('show');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var formData = {
            tipo:'Proyecto',
            nombres: $('#inNombreProyecto').val(),
            apellidos: '',
            ide_departamento: $('#inDepartamentoProyecto').val()
        };

        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#btnGuardarProyecto').val();

        var type = "POST"; //for creating new resource
        var ide_usuario = $('#ide_col_proyecto').val();;
        var my_url = url;

        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + ide_usuario;
        }
        
        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                //console.log(data); 
                var item = '<tr class="even gradeA" id="usuario' + data.ide_colaborador+ '">';
                    item+='<td>'+data.tipo+'</td>'+'<td>' + data.nombres + '</td><td>' + data.apellidos+ '</td>';
                    item+='<td></td>';
                    if(data.hasOwnProperty("departamento")){
                        item+='<td>'+data.departamento.nombre+'</td>';                          
                    }else{
                        item+='<td></td>';
                    }
                    item += '<td><button class="btn btn-primary btn-editar btn-editar-proyecto" value="' + data.ide_colaborador + '"><i class="icon-pencil icon-white" ></i> Editar</button>';
                    item += '<button class="btn btn-danger" value="' + data.ide_colaborador + '"><i class="icon-remove icon-white"></i> Eliminar</button></td></tr>';
                if (state == "add"){ 
                    dataTable.rows.add($(item)).draw();                    
                }else{
                     dataTable.row( $('#usuario'+ide_usuario)).remove();
                     dataTable.rows.add($(item)).draw();
                }
                $('#formAgregarProyecto').trigger("reset");
                $('#agregarProyecto').modal('hide');
                $('#loading').modal('hide');
            },
            error: function (data) {
                $('#loading').modal('hide');
                //console.log('Error:', data);
                var errHTML="";
                if((typeof data.responseJSON != 'undefined')){
                    for( e in data.responseJSON){
                        errHTML+="<li>"+data.responseJSON[e]+"</li>";
                    }
                }else{
                    errHTML+='<li>Error al guardar el colaborador.</li>';
                }
                //console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');  
            }
        });
        
    });
    
});

