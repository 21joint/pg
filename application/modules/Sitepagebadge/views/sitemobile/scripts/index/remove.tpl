<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: remove.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class='global_form_popup'>
  <?php if ($this->success): ?>
    <script type="text/javascript">
      parent.$('page-item-<?php echo $this->page_id ?>').destroy();
      setTimeout(function() {
        parent.Smoothbox.close();
      }, 1000 );
    </script>
    <div class="global_form_popup_message">
      <?php echo $this->translate('Your Page badge has been removed successfully.'); ?> 
    </div>
  <?php else: ?>
	  <form method="POST" action="<?php echo $this->url() ?>">
	    <div>
	      <h3><?php echo $this->translate('Remove Badge?'); ?></h3>
	      <p>
	        <?php echo $this->translate('Are you sure that you want to remove badge from this page?'); ?>
	      </p>
	      <p>&nbsp;
	      </p>
	      <p>
	        <input type="hidden" name="page_id" value="<?php echo $this->page_id?>"/>
	        <button type='submit' data-theme="b"><?php echo $this->translate('Remove'); ?></button>
	        <?php echo $this->translate(' or ')?> <a href="javascript:void(0);" onclick="parent.Smoothbox.close();"><?php echo $this->translate('cancel')?></a>
	      </p>
	    </div>
	  </form>
  <?php endif; ?>
</div>

<?php if( @$this->closeSmoothbox ): ?>
	<script type="text/javascript">
  	TB_close();
	</script>
<?php endif; ?>