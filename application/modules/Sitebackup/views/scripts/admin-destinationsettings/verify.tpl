<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license http://www.socialengineaddons.com/license/
 * @version $Id: verify.tpl 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author  SocialEngineAddOns
 */
?>
<div class='clear'>
  <div class='settings'>
    <?php if( !empty($this->accessToken) ): ?>
      <div class="sitebackup_form">
        <div class="sitebackup_form_inner">
          <h3><?php echo $this->translate("Access Token Generated Successfully"); ?></h3>
          <div class="sitebackup_success">
            <?php echo $this->translate("Congratulations! Your dropbox access token has been successfully generated.");
            echo "<br />"
            ?>
          </div>

        </div>
      </div>
      <script type="text/javascript">
        var value = true;
        en4.core.runonce.add(function () {
          window.onunload = function (e) {
            window.opener.callParentfunction(value);
          };
          window.close();
        });
      </script>
<?php else: ?>
      <div class="sitebackup_form">
        <div class="sitebackup_form_inner">
          <h3><?php echo $this->translate("Access Token Not Generated"); ?></h3>
          <div class="sitebackup_danger">
            <?php if( !empty($this->msg) ): ?>
              <?php echo $this->msg; ?>
            <?php else: ?> 
              <?php echo $this->translate("Your access token has not generated. Please fill the data in the form correctly.");
              echo "<br />"
              ?>
  <?php endif; ?>
          </div>

        </div>
      </div>
<?php endif; ?>
  </div>
</div>

