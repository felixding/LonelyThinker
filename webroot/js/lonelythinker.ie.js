/**
 * Hacks for IE
 */
$(document).ready(function() {	 
	if($.browser.msie) {
		/**
		 * add a hover effect for emoticons
		 *
		 * @author Felix Ding
		 * @date 2008-02-24
		 */	
		$('#emoticons img').hover(
			function() {
				$(this).addClass('hover');
			},
			function() {
				$(this).removeClass('hover');
			}
		);

		/**
		 * add a hover effect for formActions
		 *
		 * @author Felix Ding
		 */	
		$('table tr.odd ul.actions input').hover(
			function() {
				$(this).addClass('hover');
			},
			function() {
				$(this).removeClass('hover');
			}
		);
		
		/**
		 * debug
		 */
		//$('#primary').animate({left:'30px', top:'40px'});
		//var $p = $('primary');
	}	
});