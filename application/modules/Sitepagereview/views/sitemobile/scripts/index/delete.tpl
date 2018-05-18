<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: delete.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */ 
?>


<div class="layout_middle">
	<form method="post" class="global_form" action="<?php echo $this->url(array('action'=>'delete'));?>">
		<div>
			<div>
				<h3><?php echo $this->translate('Delete Review?'); ?></h3>
				<p>
					<?php echo $this->translate('Are you sure that you want to delete this Page review? It will not be recoverable after being deleted.'); ?>
				</p>
				<br />
			
					<input type="hidden" name="confirm" value="true"/>
					<button type='submit' data-theme="b" data-inline="true" ><?php echo $this->translate('Delete'); ?></button>
          <?php echo $this->translate('or'); ?> 
            <a href="#" data-rel="back" data-role="button" data-inline="true" >
              <?php echo $this->translate('Cancel') ?>
            </a>

			
			</div>
		</div>
	</form>
</div>