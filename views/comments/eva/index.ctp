<?php
//build the form
echo $form->create(array('id'=>'eva', 'action'=>'eva'));

//container
echo '<div class="tools">';

//paginator
echo $this->element('pagination');

//the button
echo $form->submit(__('Train!', true), array('class'=>'button'));
//echo $form->button(__('Train!', true));

//container
echo '</div>';
?>
<script type="text/javascript">
/*
jQuery.fn.check = function() {
   return this.each(function() {
     this.checked = true;
   });
 };
*/
$(document).ready(function(){
	$('a.markspam').click(function() {
		//alert('asdfsdf');
		
		$(":radio ").each(function() {
			//if(!$(this).val()!='published' || !$(this).val()) this.checked = true;
		});
		
		return false;
	});
});
</script>
<a class="markspam" href="##">mark all as spam</a>
<table class="beautified">
	<thead>
		<tr>
			<th width="150"><?php __('Mark as'); ?></th>
			<th><?php __('Id'); ?></th>
			<th><?php __('Name'); ?></th>
			<th><?php __('Email'); ?></th>
			<th><?php __('Website'); ?></th>
			<th><?php __('IP'); ?></th>
			<th><?php __('Body'); ?></th>
			<th><?php __('Subscription'); ?></th>
			<th><?php __('Created'); ?></th>
			<th><?php __('Post'); ?></th>										
		</tr>
	</thead>
	<tbody>
		<?php
		$i = 0;
		foreach($unlearnedComments as $unlearnedComment)
		{			
			//just for better display
			$trClass = is_int($i / 2) ? 'odd' : 'even';
			$unlearnedComment['Comment']['website'] = !empty($unlearnedComment['Comment']['website']) ? $unlearnedComment['Comment']['website'] : '&nbsp;';
			$unlearnedComment['Comment']['subscription'] = !empty($unlearnedComment['Comment']['subscription']) ? $unlearnedComment['Comment']['subscription'] : '&nbsp;';
			
			echo 
			'<tr class="'.$trClass.'">
				<td><ul><li>'.$form->radio('Comment.status.'.$unlearnedComment['Comment']['id'], array('published'=>__('published', true), 'spam'=>__('spam', true), 'trash'=>__('trash', true)), array('legend'=>false, 'value'=>'spam', 'separator'=>'</li><li>')).'</li></ul>'.$form->hidden('Comment.default.'.$unlearnedComment['Comment']['id'], array('value'=>'spam')).'</td>
				<td>'.$unlearnedComment['Comment']['id'].'</td>
				<td>'.$unlearnedComment['Comment']['name'].'</td>
				<td>'.$unlearnedComment['Comment']['email'].'</td>
				<td>'.$unlearnedComment['Comment']['website'].'</td>
				<td>'.$unlearnedComment['Comment']['ip'].'</td>
				<td>'.mb_substr($unlearnedComment['Comment']['body'], 0, 100).'</td>
				<td>'.$unlearnedComment['Comment']['subscription'].'</td>
				<td>'.$unlearnedComment['Comment']['created'].'</td>
				<td>'.$html->link($unlearnedComment['Post']['title'], 'posts/view/'.$unlearnedComment['Post']['slug'], array('target'=>'_blank')).'</td>						
			</tr>
			';
			
			$i++;
		}
		?>
	</tbody>
	<tfoot>
	</tfoot>
</table>
<?php
echo $form->end();
?>