<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: deleteselected.tpl 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
?>

<?php if( $this->ids ): ?>
  <form method="post" class='global_form_popup'>
    <div>
      <h3><?php echo $this->translate("Delete the selected backup listings?") ?></h3>
      <p>
        <?php echo $this->translate("Are you sure that you want to delete the %d backup listings? It will not be recoverable after being deleted.", $this->count) ?>
      </p>
      <br />
      <p>
        <input type="hidden" name="confirm" value='true'/>
        <input type="hidden" name="ids" value="<?php echo $this->ids ?>"/>

        <button type='submit'><?php echo $this->translate("Delete") ?></button>
        <?php echo $this->translate(" or ") ?> <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'><?php echo $this->translate("cancel") ?></a>
      </p>
    </div>
  </form>
<?php else: ?>
  <?php echo $this->translate("Please select a backup listing to delete.") ?> <br/><br/>
  <a href="<?php echo $this->url(array('action' => 'index')) ?>" class="buttonlink icon_back">
    <?php echo $this->translate("Go Back") ?>
  </a>
<?php endif; ?>
<?php if( @$this->closeSmoothbox ): ?>
  <script type="text/javascript">
    TB_close();
  </script>
<?php endif; ?>
