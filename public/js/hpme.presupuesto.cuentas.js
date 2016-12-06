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
        
        var cuenta=$(this).val();
        var idePresupuestoColaborador=(""+$('meta[name="_presupuestoColaborador"]').attr('content'));
        
        var formData = {
            ide_cuenta: cuenta,
            ide_presupuesto_colaborador: idePresupuestoColaborador
        }; 
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var url=(""+$('meta[name="_url"]').attr('content')).replace("#","");
        url+="/getDetalle";

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                var recalc=false;
                for(var e in data){
                    $('#itemVal'+data[e].num_detalle).val(data[e].valor);
                    recalc=true;
                }
                if(recalc){
                    recalcTotal();  
                }
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

    //create new task / update existing task
    $("#btnGuardar").click(function (e) {   
        $('#loading').modal('show');
        
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
            }
        });
        items+="]}";
        
        var cuenta=$(this).val();
        var idePresupuestoColaborador=(""+$('meta[name="_presupuestoColaborador"]').attr('content'));
  
        var formData = {
            ide_cuenta: cuenta,
            ide_presupuesto_colaborador: idePresupuestoColaborador,
            items:JSON.parse(items)
        };   
        
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        var url=(""+$('meta[name="_url"]').attr('content')).replace("#","");
        url+="/addDetalle";

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                $('#agregarEditarModal').modal('hide');
                $("#btn"+cuenta).removeClass('btn-primary').addClass('btn-success');
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