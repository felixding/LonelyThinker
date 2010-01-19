/**
 * a dirty implementation for joining strings into one string
 *
 * @author Felix Ding
 * @date 2008-02-22
 * @param String separator
 * @param Array myArray
 * @return String
 */
function implode(separator, myArray) {
	//define the string to be returned
	var myString = '';

	//traverse
	for(var i=0;i<myArray.length;i++)
	{
		myString += separator + myArray[i];
	}

	//delete the first separator
	myString = myString.slice(separator.length, myString.length);

	//return
	return myString;
}

/**
 * also a dirty implementation for spliting a string into strings
 *
 * @author Felix Ding
 * @date 2008-02-24
 * @param String separator
 * @param String myString
 * @return Array
 */
function explode(separator, myString) {
	//define the string to be returned
	var myArray = new Array();

	//split
	myArray = myString.split(separator);

	//return
	return myArray;
}

/**
 * Main
 */
$(document).ready(function() {

	/**
	 * Debug
	 */
	//alert('go');	
	
	/**
	 * animation for messages
	 *
	 * @date 2009-04-20
	 */
	if($('div.message').length) $.growlUI($('div.message').html());	

	/**
	 * bulletin board
	 *
	 * @date 2009-01-24  
	 */
	if($('div#bulletin').length && !$.browser.msie) $('div#bulletin').corners();

	/**
	 * emoticons
	 *
	 * @date 2008-02-22
	 */
	$('#emoticons img').click(function() {
		//debug
		//alert($(this).attr('src'));
			
		//read current text
		var commentBody = $('form#CommentAddForm').find('textarea').val();

		//separator
		var separator = ':';

		//get the emoticon set
		//var emoticonsSet = parseUri($(this).attr('src')).queryKey.set;

		//emoticon's filename
		var emoticonFilename = $(this).attr('alt');

		//emoticon's name
		var emoticonArray = explode('.', emoticonFilename);
		var emoticon = emoticonArray[0];

		var tag = new Array('emoticon', emoticon);
		tag = ' [' + implode(separator, tag) + '] ';

		//wrap up
		commentBody = commentBody + tag;
		
		//debug
		//alert(commentBody);

		//insert
		$('form#CommentAddForm').find('textarea').val(commentBody);		
	});	

	/**
	 * Return to the previous page
	 *
	 * @date 2009-01-22
	 */
	 $('a.return').click(function() {
	 	history.back();
	 	return false;
	 });	
	 
	/**
	 * Ajax commenting
	 *
	 * @date 2009-01-24
	 */
	$('form#CommentAddForm1').submit(function() {
		//debug
		//alert('let the shit goes');
		//return true;
		
		//init
		var spinner = 'img#spinner';		
		
		//remove error class
		$('#CommentAddForm .error').removeClass('error');
		
		//block ui
		$('#CommentAddForm').block({message: null});				
		
		//trigger the form
		$(this).ajaxSubmit({
			dataType:'json', 		
			success:function(data) {		
				//the result?
				//alert('data.errorCode: ' + data.errorCode);
				
				if(data.errorCode == 0)
				{			
					//comments saved, append the return html to ol.commentlist
					
					//any existing comments? if not, we need to change the title
					if($('div#comments ol li').length == 0)
					{
						$('div#comments h2').html(data.h2Title);
					}					
					
					//update overallCommentCount
					var overallCommentsCount = $('div#comments ol li').length + data.latestCommentsCount;

					$('div.post p.meta span.tags_comments a span.loaded_comment_count').html(overallCommentsCount);
					$('div#comments h2 span').html(overallCommentsCount);
					
					//append data
					$('div#comments ol').append(data.commentbody);					
					
					//display & animate, jQuery UI addClass animation doesn't support Safari
					if($.browser.safari)
					{
						$('div#comments ol li.hidden').fadeIn('slow');
					}
					else
					{
						$('div#comments ol li.hidden').show();
						
						//hack for ie - ie will first display the element then append the style to it, which sucks
						if($.browser.msie) $('div#comments ol li').css('border', 'none');
						
						//animate
						$('li#comment-' + data.thisCommentId).addClass("animation").removeClass("animation", 2000);
					}									

					//clear form
					$('textarea#CommentBody').clearFields();
				}
				else if(data.errorCode == 1)
				{
					//debug
					//alert(data.errorCode);
					
					//something wrong, mark invalid fields
					$.each(data.invalidFields, function(field, message) {
						//append error message into the div where the element is contained	
						//var errorMessageSpan = '<span class="error">' + message + '</span>';
						//$('#' + field).parent('li').append(errorMessageSpan);	
						
						//default values
						//setCommentAddFormDefaultValue();
						
						//add class 'error' to the element
						$('#' + field).addClass('error');
						
						//debug
						//alert(field+':'+message);
					});

				}
				else if(data.errorCode == 2)
				{				
					alert(data.message);
					
					//clear form
					$('textarea#CommentBody').clearFields();
										
					//setTimeout($('#CommentAddForm').unblock(), 2000);
				}
				else
				{
					//
				}
				
				//hide indicator
				//$(spinner).fadeOut();				
				
				//unblock ui
				$('#CommentAddForm').unblock();
				
				//hack for ie, because ie doesn't redraw the cursor
				if($.browser.msie)
				{
					setTimeout(function() {
				      $('body').css('cursor', 'default');
				    }, 1000);
				}
			}
		});
		return false;
	});
});