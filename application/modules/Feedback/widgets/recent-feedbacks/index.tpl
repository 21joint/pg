<?php 
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Document
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2010-08-11 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<!--<script type="text/javascript">
    var mostvotedAction = function(mostvoted){
  	    $('orderby_mostvoted').value = 'total_votes';
  	    $('filter_form_mostvoted').submit();
  	}
</script>    -->

<?php //echo $this->formmostvoted->render($this) ?>

<ul class="seaocore_sidebar_list">
  <?php foreach( $this->paginator as $feedback ): ?>
    <li>
			<?php $user = Engine_Api::_()->getItem('user', $feedback->owner_id); ?>
      <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon')) ?>
	    <div class='seaocore_sidebar_list_info'>
	    	<div class='seaocore_sidebar_list_title'>
	      	<?php echo $this->htmlLink($feedback->getHref(), Engine_Api::_()->seaocore()->seaocoreTruncateText($feedback->feedback_title, $this->truncationLimit), array('title' => $feedback->feedback_title)) ?>
	    	</div>
	      <div class='seaocore_sidebar_list_details'>
      		<?php echo $this->translate(array('%s comment', '%s comments', $feedback->comment_count), $this->locale()->toNumber($feedback->comment_count)) ?> |
     	 		<?php echo $this->translate(array('%s vote', '%s votes', $feedback->total_votes), $this->locale()->toNumber($feedback->total_votes)) ?> |
     	 		<?php echo $this->translate(array('%s view', '%s views', $feedback->views), $this->locale()->toNumber($feedback->views)) ?>
	    	</div>
	  	</div>
	  </li>
  <?php endforeach; ?>
</ul>
