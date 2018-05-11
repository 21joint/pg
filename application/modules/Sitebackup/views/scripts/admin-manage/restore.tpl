<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: restore.tpl 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
?>
<h2><?php echo $this->translate('Website Backup and Restore Plugin') ?></h2>
<div class='tabs'>
  <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
</div>

<div class='clear'>
  <div class='settings'>
    <?php if( !empty($this->success) ): ?>
      <div class="sitebackup_form">
        <div class="sitebackup_form_inner">
          <h3><?php echo $this->translate("Restore Completed Successfully"); ?></h3>
          <div class="sitebackup_success">
            <?php echo $this->translate("Congratulations! Your database has been successfully restored.");
            echo "<br />"
            ?>
          </div>

          <div style="clear:both;font-weight:bold;"><?php echo $this->translate("Database restore information"); ?></div> 
          <table class="sitebackup_table">
            <tr>
              <td>
                <u>Time taken</u>: <?php echo $this->translate("$this->duration"); ?>
              </td>	
            </tr>
          </table>  

        </div>
      </div>
    <?php endif; ?>

    <?php if( $this->flage == 1 ): ?>
      <?php echo $this->form->render($this); ?>
<?php endif; ?>
  </div>
</div>
<script language="javascript" type="text/javascript">
  function hidebutton() {
    if (document.getElementById('submit_button'))
      document.getElementById('submit_button').style.display = 'none';
    if (document.getElementById('loading_img'))
      document.getElementById('loading_img').style.display = 'block';
  }

  function showlightbox() {
    document.getElementById('light').style.display = 'block';
    document.getElementById('fade').style.display = 'block';
  }
</script>

<div id="light" class="sitebackup_white_content">
<?php echo $this->translate('Restoring'); ?>
  <img src="application/modules/Sitebackup/externals/images/backup-restore.gif" alt="" style="vertical-align:middle;margin-left:10px;" />
</div>
<div id="fade" class="sitebackup_black_overlay"></div>