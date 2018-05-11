<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: index.tpl 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('Website Backup and Restore Plugin – AWS S3, Dropbox, Google Drive, FTP, etc.') ?></h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
    //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>
<?php include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_upgrade_messages.tpl'; ?>
<div class='clear' >
  <div class='settings'>

    <?php if( !empty($this->success) && !Engine_Api::_()->getApi('settings', 'core')->sitebackup_backupoptions ): ?>
      <ul class="form-notices" >
        <li style="font-size:12px;" >
          <?php echo $this->translate($this->success); ?>
        </li>
      </ul>

    <?php elseif( !empty($this->error) ): ?>
      <ul class="form-notices" >
        <li style="font-size:12px;">
          <?php echo $this->error; ?>
        </li>
      </ul>
    <?php endif; ?>

    <?php echo $this->form->render($this); ?>

  </div>

</div>
<style type="text/css">
  .settings form {float:none;}
  .settings .form-description {max-width: none;}
  .settings .form-element{max-width:650px;}
  .settings .form-element .description {max-width:none;}
</style>
<script type="text/javascript">
  var checkboxcount = 0;
  var display_msg = 0;
  //HERE WE CREATE THE FUNCTION FOR CHECK ALL BOXES FOR BACKUP.
  function doCheckAll() {
    if (checkboxcount == 0) {
      $$('.global_form').each(function (elements) {
        for (var i = 0; i < elements.length; i++) {
          if (elements[i].type == 'checkbox') {
            elements[i].checked = false;
          }
        }
        checkboxcount = checkboxcount + 1;
      }
      );
    } else {
      $$('.global_form').each(function (elements) {
        for (var i = 0; i < elements.length; i++) {
          if (elements[i].type == 'checkbox') {
            elements[i].checked = true;
          }
        }
        checkboxcount = checkboxcount - 1;
      }
      );
    }
  }

  //HERE WE CREATE A FUNCTION FOR SHOWING THE DROPDOWN BLOCK OF AUTOMATIC BACKUP OR SIMPLE BACKUP OPTIONS.
  window.addEvent('domready', function () {
    showautomaticblock(<?php echo $this->autobackupoption; ?>);
    display_msg = 1;
  });

  //HERE WE CREATE A FUNCTION FOR SHOWING THE DROPDOWN BLOCK OF AUTOMATIC BACKUP OR SIMPLE BACKUP OPTIONS.
  function showautomaticblock(option) {

    if (option == 0) {
      if (display_msg == 1)
        Smoothbox.open('<div style="margin:5px 10px 0 0;">Please make sure that the time interval between automatic database backups selected by you is atleast more than 5 times the duration taken for a manual backup. Please refer to the Take Backup section for a manual backup.</div><br /> <center> <button  onclick="javascript:parent.Smoothbox.close()">ok</button> </center>');
      if ($('sitebackup_dropdowntime-wrapper')) {
        $('sitebackup_dropdowntime-wrapper').style.display = 'block';
      }
      if ($('sitebackup_mailoption-wrapper'))
        $('sitebackup_mailoption-wrapper').style.display = 'block';
      if ($('sitebackup_lockoptions-wrapper')) {
        $('sitebackup_lockoptions-wrapper').style.display = 'block';
      }
      if ($('sitebackup_destinations-wrapper'))
        $('sitebackup_destinations-wrapper').style.display = 'block';
      if ($('sitebackup_autofilename-wrapper'))
        $('sitebackup_autofilename-wrapper').style.display = 'block';

      var mail_option = 0;
      if ($('sitebackup_mailoption-1').checked == true)
        mail_option = 1;

      showmailblock(mail_option);
    } else {
      if ($('sitebackup_autofilename-wrapper')) {
        $('sitebackup_autofilename-wrapper').style.display = 'none';
      }
      if ($('sitebackup_dropdowntime-wrapper')) {
        $('sitebackup_dropdowntime-wrapper').style.display = 'none';
      }

      if ($('sitebackup_lockoptions-wrapper')) {
        $('sitebackup_lockoptions-wrapper').style.display = 'none';
      }
      if ($('sitebackup_mailoption-wrapper')) {
        $('sitebackup_mailoption-wrapper').style.display = 'none';
      }
      if ($('sitebackup_destinations-wrapper')) {
        $('sitebackup_destinations-wrapper').style.display = 'none';
      }
      showmailblock(0);
    }
  }

  //HERE WE CREATE A FUNCTION FOR SHOWING THE DROPDOWN BLOCK OF AUTOMATIC BACKUP OR SIMPLE BACKUP OPTIONS.
  function showmailblock(option) {
    if ($('sitebackup_mailsender-wrapper')) {
      if (option == 1) {
        $('sitebackup_mailsender-wrapper').style.display = 'block';
      } else {
        $('sitebackup_mailsender-wrapper').style.display = 'none';
      }
    }
  }

</script>

<?php if( @$this->closeSmoothbox ): ?>
  <script type="text/javascript">
    TB_close();
  </script>
<?php endif; ?>