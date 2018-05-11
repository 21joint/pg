<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: newcreate.tpl 6590 2010-09-01 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php if( count($this->navigation) ): ?>
	<div class="headline">
		<div class='tabs'> 
			<?php echo $this->navigation()->setContainer($this->navigation)->menu()->render() ?> 
		</div>
	</div>
<?php endif; ?>

<?php echo $this->form->render($this) ?>