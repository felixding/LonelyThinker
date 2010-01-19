/**
 * LonelyThinker Javascript Utilities for Administrators
 *
 * @date 2009-02-06
 */


/**
 * Main
 *
 * @date 2009-02-06
 */
$(document).ready(function() {
	/**
	 * Init & Configurations
	 */
	//deprecated, the url that LT runs
	//var baseUrl = 'http://localhost/lt049';
	
	/**
	 * display the full URL when the user is inputting the slug
	 *
	 * @date 2009-07-01
	 */
	$('form#PostAddForm input#PostSlug').simpleslug({
		source: 'input#PostTitle',
		preview: 'dd.slug span'
	});
	$('form#PostEditForm input#PostSlug').simpleslug({
		source: 'input#PostSlug',
		preview: 'dd.slug span'
	});
	
	/**
	 * for 'delete' link action
	 *
	 * @date 2009-02-06
	 */
	$('td.actions ul.actions a.delete').click(function() {		
		//what does the user want to do?
		//var action = $(this).attr('class');
		
		//no ajax edit at the moment
		//if(action == 'edit') return true;
		
		//delete the comment from where?
		var folder = $('div#comments-moderation div.titlebar li a.current').parent('li').attr('class');
		
		//get the tr for animation
		var tr = $(this).parents('tr');
		var tds = $(tr).children('td');
		
		//debug
		//alert(action);
		//return true;
		
		//indicator
		$.blockUI({message: $('div#dialog').html()});
		
		//ajax call
		$.getJSON($(this).attr('href'), function(data) {
			//redirect to login page when session expires
			var loginMarker = /login/gi;
			if(loginMarker.test(data)) {
				//I hope there is a bettery way to do this...
				//document.write(data);
				window.location = baseUrl + '/users/login';
				
				return false;
			}
			
			//debug
			//alert(data.errorCode);					
			
			//nothing wrong
			if(data.errorCode == 0) {
				//ask for confirmation
				$('div#dialog').html(data.message);
				$.blockUI({message: data.message});
				
				//bind the events				
				$('a.dialog-no').bind('click', function() {
					$.unblockUI();
					return false;
				});
				$('form.dialog-delete-form').bind('submit', function() {
					$(this).ajaxSubmit({
						dataType:'json', 		
						success:function(data) {
							//debug
							//alert(data.errorCode);
							
							//animate
							//The extra animation only(?) supports Firefox
							if($.browser.mozilla) $(tr).addClass('animation', 200);
							else $(tr).css('background-color', '#369');							
							$(tr).fadeOut();
							
							//for comments:
							//get current counter
							var counter = Number($('div#comments-moderation div.titlebar li.'+folder+' span').html());
							//update the counter
							$('div#comments-moderation div.titlebar li.'+folder+' span').html(--counter);
						}
					});
					
					$.unblockUI();
					return false;
				});
				
				return false;
			}
		});
		
		//
		return false;
	});
	
	/**
	 * for move a comment to other boxes
	 *
	 * @date 2009-03-10
	 */
	$('div#comments-moderation td.actions ul.actions form').submit(function() {
		//get the tr for animation
		var tr = $(this).parents('tr');
		var tds = $(tr).children('td');
		//alert($(tr).html()); return false;
		//return true;
		
		//submit the form	
		$(this).ajaxSubmit({
			dataType:'json',
			success:function(data) {
				//nothing wrong
				if(data.errorCode == 0) {
					//animate
					//The transfer animation only(?) supports Firefox
					if($.browser.mozilla) $(tr).addClass('animation', 200);
					else $(tr).css('background-color', '#369');
					
					$(tr).effect('transfer', {
						to: 'div#comments-moderation div.titlebar li.'+data.to,
						className: 'ui-effects-transfer'
					}, 800).fadeOut();
														
					//get current counter
					var counterFrom = Number($('div#comments-moderation div.titlebar li.'+data.from+' span').html());
					var counterTo = Number($('div#comments-moderation div.titlebar li.'+data.to+' span').html());										
					
					//update the counters
					$('div#comments-moderation div.titlebar li.'+data.from+' span').html(--counterFrom);
					$('div#comments-moderation div.titlebar li.'+data.to+' span').html(++counterTo);
				}
			}
		});
		
		return false;
	});
	
	/**
	 * for get more body text of a comment
	 *
	 * @date 2009-03-11
	 */
	$('div#comments-moderation table a.more').click(function() {
		var link = this;
		$.getJSON($(this).attr('href'), function(data) {			
			$(link).before(data.body).remove();
		});
		
		return false;
	});
});