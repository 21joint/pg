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

<div class="quicklinks seaocore_gutter_blocks">
	<ul>
		<li> 
			<a href='<?php echo $this->url(array(), 'feedback_create', true) ?>' class='buttonlink icon_feedback_new' onclick="owner(this);return false">
				<?php echo $this->translate('Create New Feedback');?>
			</a> 
		</li>
	</ul>
</div>

<script type="text/javascript" >

function owner(thisobj) {
	var Obj_Url = thisobj.href  ;

	Smoothbox.open(Obj_Url);
}
</script>