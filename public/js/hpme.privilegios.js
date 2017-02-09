/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTableItems').DataTable(window.lang);    
    $.configureBoxes();   
    //Ventana para modificar los privilegios
    $(document).on('click','.btn-editar',function(){
        $('#loading').modal('show');
        var ide_item=$(this).val();
        var my_url=(""+$('meta[name="_urlTarget"]').attr('content')).replace("#","");
        
        $.get(my_url + '/' + ide_item, function (data) {
            //success data
            console.log(data);
            $('#inNombre').val(data.rol.nombre);
            $('#inDescripcion').val(data.rol.descripcion);
            var box1HTML='';
            var box2HTML='';
            if(data.rol.hasOwnProperty("privilegios") && data.rol.privilegios.length>0){
                for(var d in data.rol.privilegios){
                    box2HTML+='<option value="'+data.rol.privilegios[d].ide_privilegio+'" title="'+data.rol.privilegios[d].descripcion+'">'+data.rol.privilegios[d].descripcion+"</option>";
                }   
            }
            
            if(data.hasOwnProperty("pendientes") && data.pendientes.length>0){
                for(var d in data.pendientes){
                    box1HTML+='<option value="'+data.pendientes[d].ide_privilegio+'" title="'+data.pendientes[d].descripcion+'">'+data.pendientes[d].descripcion+"</option>";
                }   
            }
            $('#box1View').html(box1HTML);
            $('#box2View').html(box2HTML);           
            $('#agregarEditarModal').modal('show');
            $('#ide_item').val(data.rol.ide_rol);
            $('#loading').modal('hide');
        }) 
    });    

    //create new task / update existing task
    $("#btnGuardar").click(function (e) {      
        $('#loading').modal('show'); 
        var itemsData=[];
        
        $("#box2View option").each(function(){
            itemsData.push($(this).val());
        });
        
        var ide_item = $('#ide_item').val();
        
        var formData = {
            ide_rol: ide_item,
            privilegios: itemsData
        };   
       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var type = "POST"; //for creating new resource
        var my_url = $('meta[name="_urlTarget"]').attr('content');      
        $.ajax({
            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
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
                    errHTML+='<li>Error al guardar privilegios.</li>';
                }
                console.log('Error:', data);
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');                
            }
        });
    });
});