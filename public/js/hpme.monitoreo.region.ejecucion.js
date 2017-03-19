/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){    
    //Agregar nuevo usuario
    
    $( document ).on( 'click', '.btn-graficar', function() {
        $('#loading').modal('show');
        var targetURL=$('meta[name="_urlTarget"]').attr('content');  
        $('#myModal1').modal('show'); 
        var ideRegionProducto=$(this).val();
        $.get(targetURL + '/' + ideRegionProducto, function (data) {
            //success data
            //console.log(data);
            $("#myModalLabel").html("Ejecuci&oacute;n "+data.producto);
            
            google.charts.load('current', {'packages':['corechart','bar']});
            google.charts.setOnLoadCallback(drawChart);
            //var producto=data.producto;
            //console.log(producto);
            function drawChart() {
                var datos=[];
                datos.push(['Trimestre','Planificado']);
                for(var e in data.detalles){
                    datos.push([data.detalles[e].encabezado,parseInt(data.detalles[e].plan)]);
                }
                
                //var tmp=[['Test', 'valor'],['INGRESOS',     11],['EGRESOS',      2],['AMORTIZACIONES',  2]];
                //console.log(tmp);
                console.log(datos);
//                var dataChart = google.visualization.arrayToDataTable([
//                  ['Test', 'valor'],
//                  ['INGRESOS',     11],
//                  ['EGRESOS',      2],
//                  ['AMORTIZACIONES',  2],
//                ]);
                var dataChart = google.visualization.arrayToDataTable(datos);


                var options = {
                  title: data.producto,
                  width: 900,
                  height: 500
                };

                var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                chart.draw(dataChart, options);
                
                
                var datosbar=[];
                datosbar.push(['Trimestre','Planificado','Ejecutado']);
                for(var e in data.detalles){
                    datosbar.push([data.detalles[e].encabezado,parseInt(data.detalles[e].plan),parseInt(data.detalles[e].ejecutado)]);
                }
                var data2 = google.visualization.arrayToDataTable(datosbar);
                   var options2 = {
                       width: 900,
                       height: 500,
                      chart: {
                        title: 'Rendimiento Anual',
                        subtitle: 'Planificado/Ejecutado',
                        
                      }
                    };

                    var bchart = new google.charts.Bar(document.getElementById('columnchart_material'));

                    bchart.draw(data2, options2);
        
                $('#loading').modal('hide');
            }  
        });
        
//        google.charts.load('current', {'packages':['corechart']});
//        google.charts.setOnLoadCallback(drawChart);
//
//      function drawChart() {
//
//        var data = google.visualization.arrayToDataTable([
//          ['Test', 'valor'],
//          ['INGRESOS',     11],
//          ['EGRESOS',      2],
//          ['AMORTIZACIONES',  2],
//        ]);
//
//        var options = {
//          title: 'Distribucion por Cuentas'
//        };
//
//        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
//
//        chart.draw(data, options);
//    }  
       
//        var ideRegionProductoDetalle=$(this).val();
//        $('#btnGuardarDetalle').val(ideRegionProductoDetalle);
//        var targetURL=$('meta[name="_urlTarget"]').attr('content');
//        var requiereComprobante=$("#comprobante"+ideRegionProductoDetalle).val();
//        //var periodo=$('meta[name="_periodo"]').attr('content');
//        $.get(targetURL + '/detalleproducto/'+ideRegionProductoDetalle, function (data) {
//            //success data
//            $('#planificado').val(data.valor);
//            $('#ejecutado').val(data.ejecutado); 
//            $('#tabla_archivos tbody').html('');
//            if(data.hasOwnProperty("archivos") && data.archivos){
//                var tabla=$('#tabla_archivos tbody');
//                var url_download=(""+$('meta[name="_urlDownload"]').attr('content'));
//                var download_image=(""+$('meta[name="_download"]').attr('content'));
//                //var url_delete=(""+$('meta[name="_urlDelete"]').attr('content'));
//                var delete_image=(""+$('meta[name="_delete"]').attr('content'));
//                var estado=(""+$('meta[name="_estado"]').attr('content'));
//                var download='<img src="'+download_image+'" class="menu-imagen" alt="" title="Descargar archivo"></img>'            
//                var deletefile='<img src="'+delete_image+'" class="menu-imagen" alt="" title="Eliminar archivo"></img>'
//                var item;
//                for(var e in data.archivos){
//                  item="<tr id=arc"+data.archivos[e].ide_archivo_producto+"><td>" + data.archivos[e].nombre + "</td><td>" + data.archivos[e].fecha + "</td><td><a href="+url_download+'/'+data.archivos[e].ide_archivo_producto+">"+download+"</a>";                    
//                  if(estado==="ABIERTO"){
//                    item+='<button type="button" value="'+data.archivos[e].ide_archivo_producto+'" class="btn-borrar-archivo">'+deletefile+"</button>"
//                  }
//                  item+="</td>";
//                  tabla.append(item);
//                }
//                if(requiereComprobante==='S'){
//                    $("#fileUpload").attr('disabled', false);
//                    $("#subirArchivo").attr('disabled', false);
//                    $("#archivoLabel").html('** Este producto requiere cargar archivos para comprobar la ejecuci&oacute;n.');
//                }else{
//                    $("#fileUpload").attr('disabled', true);
//                    $("#subirArchivo").attr('disabled', true);
//                    $("#subirArchivo").removeAttr('disabled');
//                    $("#archivoLabel").html('');
//                }     
//            }
//            $('#loading').modal('hide');
//        });
    });
});