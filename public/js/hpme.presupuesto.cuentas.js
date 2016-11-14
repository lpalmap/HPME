/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    var dataTable=$('#dataTableItems').DataTable(window.lang);
    
    $( document ).on( 'click', '.ck-action', function() {
        if(this.checked){
            var id=$(this).val();
            var monto=parseFloat($("#itemReplicar").val().replace(",","."));
            if(isNaN(monto) || monto<0 || monto.length==0){
                $('#itemVal'+id).val('');
                $("#itemReplicar").val('');
            }else{
                monto=monto.toFixed(2);
                $('#itemReplicar').val(monto);
                $('#itemVal'+id).val(monto);
                recalcTotal();
            }          
        }   
    });
    
    $( document ).on('keyup', '.input-action', function() {
        var monto=parseFloat($(this).val().replace(",","."));
        if(isNaN(monto) || monto<0 || monto.length==0){
            $(this).val('');
        }
        recalcTotal();     
    });
    
    function recalcTotal(){
        var montoTotal=0.00;
        for(var i=1;i<=12;i++){
           var monto=parseFloat($('#itemVal'+i).val().replace(",","."));
           if(isNaN(monto) || monto<0 || monto.length==0){
               $('#itemVal'+i).val('');
           }else{
               monto=monto.toFixed(2);
               //$('#itemVal'+i).val(monto);
               montoTotal=montoTotal+parseFloat(monto);
           }
        }
        $('#total').val(montoTotal.toFixed(2));         
    };
    
    $("#itemReplicar").keyup(function(){
        if($('#replicar').prop('checked')){
            var monto=parseFloat($("#itemReplicar").val().replace(",","."));
            if(isNaN(monto) || monto<0 || monto.length==0){
                $('#itemReplicar').val('');
                monto=0;
            }else{
                monto=monto.toFixed(2);
            }
            $("input[name='ckItem']").each(function() {
                if(this.checked){
                    $('#itemVal'+$(this).prop('value')).val(monto);
                }
            });
            recalcTotal();
        }
    });
    
    $('#replicar').click(function(){
        if(this.checked){
            var monto=parseFloat($("#itemReplicar").val().replace(",","."));
            if(isNaN(monto) || monto<0 || monto.length==0){
                $('#itemReplicar').val('');
                $('#total').val(0);
                for(var i=1;i<=12;i++){
                    $('#itemVal'+i).val('');
                }
                $("input[name='ckItem']").each(function() {
                    this.checked=true;
                });
            }else{
                monto=monto.toFixed(2);
                $('#itemReplicar').val(monto);
                var montoTotal=monto*12;
                for(var i=1;i<=12;i++){
                    $('#itemVal'+i).val(monto);
                }
                $('#total').val(montoTotal.toFixed(2));
                $("input[name='ckItem']").each(function() {
                    this.checked=true;
                });
            }
        }else{
            $("input[name='ckItem']").each(function() {
                    this.checked=false;
            });
        }     
    });
    
    $(document).on('click','.btn-cuenta',function(){
        $('#loading').modal('show');
        $('#inputTitle').html("Editar Cuenta");
        $('#agregarEditarModal').modal('show');
        $('#formAgregar').trigger("reset");
        $('#btnGuardar').val($(this).val());
        $('#loading').modal('hide');
    });    

    //create new task / update existing task
    $("#btnGuardar").click(function (e) {   
        $('#loading').modal('show');
        alert('guardar');
        var ideCuenta=$(this).val();
        
        alert(ideCuenta);
        var items='{"items":[';
        var seleccion=true;
        $(".input-action").each(function() {
            var monto=parseFloat($(this).val().replace(",","."));
            if(isNaN(monto) || monto<0 || monto.length==0){
                //se ignora el item
            }else{
                if(seleccion){
                    items+='{"item":"'+$(this).prop('id')+'","value":"'+monto.toFixed(2)+'"}';
                    seleccion=false;
                }else{
                    items+=',{"item":"'+$(this).prop('id')+'","value":"'+monto.toFixed(2)+'"}';                  
                }
                
                //alert('fin push');
            }
        });
        items+="]}";
        console.log(items);
        alert(JSON.parse(items));
        if(2>1)return;
        var cuenta_padre=(""+$('meta[name="_cuentaPadre"]').attr('content')).replace("#","");
        
        var formData = {
            cuenta: $('#inCuenta').val(),
            nombre: $('#inNombre').val(),
            descripcion: $('#inDescripcion').val(),
            ind_consolidar:1,
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