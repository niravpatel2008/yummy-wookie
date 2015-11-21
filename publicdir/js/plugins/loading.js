/** 
 *  Loading Screen JQuery Plugin
 *  Developed By : Nirav Patel
 *  
 * Import this file and it will automatically show loading screen on every ajax call.
 * Don't need to call any function.
 *
 **/
var loadingMessage = "";
(function( $ ){

	  var methods = {
	    show : function( ) {
			var top = ($(window).height()/2) - 5;
			var left =  ($(window).width()/2) - 5;

			if (typeof($('#loadingImage').attr('id')) != 'undefined') {return false;}
			
			$(this).append("<div id='loadingFade' style='top:0px;left:0px;height:100%;width:100%;position:fixed;z-index:1062;background:rgba(255,255,255,0.8);'></div>");
			$('<img/>', {
				src:     admin_path()+'/publicdir/img/ajax-loading.gif',
				title:   'Loading .. ..',
				style: 'position:fixed;z-index:1100;top:'+top+'px;left:'+left+'px;',
				id: "loadingImage",
				'class': 'loadingImage', 
				click:   function( e ){
					alert("This is testing");
				}
			}).appendTo($(this));
			
			if (loadingMessage != "")
			{
				$("<span>",{class:'ajx-loading-msg',style: 'position:fixed;z-index:1100;top:'+(top+40)+'px;left:'+(left-131)+'px;width:300px;text-align:center;'}).html(loadingMessage).appendTo($(this));
			}

	    },
	    hide : function( ) { 
	      $('#loadingImage').remove();
	      $('#loadingFade').remove();
	      $('.ajx-loading-msg').remove();
	    }
	  };

	  $.fn.loading = function( method ) {
	    if ( methods[method] ) {
	      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
	    } else if ( typeof method === 'object' || ! method ) {
	      return methods.init.apply( this, arguments );
	    } else {
	      $.error( 'Method ' +  method + ' does not exist on jQuery.loading' );
	    }     
	  };

})( jQuery );