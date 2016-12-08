/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
   ;(function($) {
   $.fn.fixMe = function() {
      return this.each(function() {
         var $this = $(this),
            $t_fixed;
         function init() {
            $this.wrap('<div class="container" />');
            $t_fixed = $this.clone();
            $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
            resizeFixed();
         }
         function resizeFixed() {
            $t_fixed.find("th").each(function(index) {
               $(this).css("width",$this.find("th").eq(index).outerWidth()+"px");
            });
         }
         function scrollFixed() {
            var offset = $(this).scrollTop(),
            tableOffsetTop = $this.offset().top,
            tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
            if(offset < tableOffsetTop || offset > tableOffsetBottom)
               $t_fixed.hide();
            else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
               $t_fixed.show();
         }
         $(window).resize(resizeFixed);
         $(window).scroll(scrollFixed);
         init();
      });
   };
})(jQuery);

$(document).ready(function(){
   $("table").fixMe();
   $(".up").click(function() {
      $('html, body').animate({
      scrollTop: 0
   }, 2000);
 });
});
*/
// ;(function($) {
//   $.fn.fixMe = function() {
//      return this.each(function() {
//         var $this = $(this),
//            $t_fixed;
//         function init() {
//            $this.wrap('<div class="container" />');
//            $t_fixed = $this.clone();
//            $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
//            resizeFixed();
//         }
//         function resizeFixed() {
//            $t_fixed.find("th").each(function(index) {
//               $(this).css("width",$this.find("th").eq(index).outerWidth()+"px");
//            });
//         }
//         function scrollFixed() {
//            var offset = $(this).scrollTop(),
//            tableOffsetTop = $this.offset().top,
//            tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
//            if(offset < tableOffsetTop || offset > tableOffsetBottom)
//               $t_fixed.hide();
//            else if(offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
//               $t_fixed.show();
//         }
//         $(window).resize(resizeFixed);
//         $(window).scroll(scrollFixed);
//         init();
//      });
//   };
//})(jQuery);

$(document).ready(function(){
    	//$("#dataTableItems").freezeHeader({ 'height': '400px' });
//        jQuery.browser = {};
//(function () {
//    jQuery.browser.msie = false;
//    jQuery.browser.version = 0;
//    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
//        jQuery.browser.msie = true;
//        jQuery.browser.version = RegExp.$1;
//    }
//})();
   // $('.tbl').fixedtableheader(); 
//    var table = $('#dataTableItems').DataTable();
// new FixedHeader(table);
// $('#dataTableItems').DataTable( {
//    fixedHeader: true
//} );
//new $.fn.dataTable.FixedHeader( table, {
//    // options
//} );
    //var dataTable=$('#dataTableItems').DataTable(window.lang);
    //new $.fn.dataTable.FixedHeader( dataTable );
    //new FixedHeader(dataTable);
//    jQuery.browser = {};
//(function () {
//    jQuery.browser.msie = false;
//    jQuery.browser.version = 0;
//    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
//        jQuery.browser.msie = true;
//        jQuery.browser.version = RegExp.$1;
//    }
//})();
//
//    new FixedHeader(document.getElementById('dataTableItems') );
   //new $.fn.dataTable.FixedHeader($('#dataTableItems'));
   
//   $("#dataTableItems").fixMe();
//   $(".up").click(function() {
//      $('html, body').animate({scrollTop: 0}, 2000)
//   });

    //Agregar nuevo usuario
    $('#btnAprobarPlan').click(function(){
        $('#loading').modal('show');
        $('#aprobarPlanificacion').modal('show');
        $('#btnAprobar').val($(this).val());
        $('#loading').modal('hide');
    });
    
    $("#btnAprobar").click(function (e) {      
        $('#loading').modal('show');
        var formData = {
            ide_presupuesto_departamento: $(this).val()
        };   
              
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        var url_target=(""+$('meta[name="_urlTarget"]').attr('content'))+'/aprobar';
        $.ajax({
            type: 'POST',
            url: url_target,
            data: formData,
            dataType: 'json',
            success: function (data) {
                location.reload();
                $('#aprobarPlanificacion').modal('hide');
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
                    errHTML+='<li>Error al aprobar el presupuesto.</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');                
            }
        });
    });
    
    $("#btnMarcar").click(function (e) {      
        $('#loading').modal('show');
        var formData = {
            ide_presupuesto_departamento: $(this).val()
        };   
              
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });      
        var url_target=(""+$('meta[name="_urlTarget"]').attr('content'))+'/marcar';
        $.ajax({
            type: 'POST',
            url: url_target,
            data: formData,
            dataType: 'json',
            success: function (data) {    
                location.reload(true);
                $('#cerrarObservacion').modal('hide');
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
                    errHTML+='<li>Error al marcar las observaciones.</li>';
                }
                $("#erroresContent").html(errHTML); 
                $('#erroresModal').modal('show');                
            }
        });
    });

});