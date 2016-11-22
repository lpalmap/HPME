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
   
   $("#dataTableItems").fixMe();
   $(".up").click(function() {
      $('html, body').animate({scrollTop: 0}, 2000)
   });


});