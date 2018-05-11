<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: destination.tpl 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
?>
<h2><?php echo $this->translate('Website Backup and Restore Plugin') ?></h2>
<script type="text/javascript">
  var fetchDestinationSettings = function (mode) {
    window.location.href = en4.core.baseUrl + 'admin/sitebackup/destinationsettings/destination/mode/' + mode;

  }
</script>
<?php if( $this->destination_mode == 5 || $this->destination_mode == 6 ): ?>
  <script type="text/javascript">
    window.authenticationFlag = false;
    window.callParentfunction = function (value) {
      window.authenticationFlag = value;
      $('sitebackup_create').elements.submit.click();
    }

    isHttpWebsite = window.location.protocol == "http:" && window.location.host != 'localhost';
    en4.core.runonce.add(function () {
      if (<?php echo $this->destination_mode == 6 ? 1 : 0 ?> && isHttpWebsite) {
        var codeWrapper = new Element('div', {'id': 'access_token-wrapper'}).inject($('appsecret-wrapper'), 'after');
        var labelDiv = new Element('div', {'id': 'access_token-label', 'class': 'form-label', 'html': '<label for="code" class="optional">Enter Access Token</label>'}).inject(codeWrapper);
        var inputDiv = new Element('div', {'id': 'access_token-element', 'class': 'form-element', 'html': '<p class="description"> Dropbox do not allow authorization for non-local and non secured websites (like http based sites). So, to get to provide access to your site generate Access Code from your Dropbox App Console and paste it here.</p><input type="text" name="access_token" id="access_token" value="">'}).inject(codeWrapper);
      }
      $('sitebackup_create').addEvent('submit', function ($this) {
        if (window.authenticationFlag)
          return true;
        authWindowParams = 'menubar=no, toolbar=no, location=no, directories=no, status=no, scrollbars=no, resizable=no, dependent, width=800, height=620';
        verifyPath = en4.core.baseUrl + 'admin/sitebackup/destinationsettings/verify?' + this.toQueryString();
        var authWindow = window.open(verifyPath, authWindow, authWindowParams);
        return false;
      });
    });
  </script>

<?php endif; ?>
<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
    //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigation)->render();
    ?>
  </div>
<?php endif; ?>
<div class="sitebackup_destination_list">
  <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitebackup', 'controller' => 'destinationsettings', 'action' => 'index', 'show' => 1), $this->translate('Back to Remote Destination'))
  ?>
</div>
<br />
<?php if( $this->destination_mode == 2 && !function_exists("ssh2_connect") ): ?>
  <div class="tip" style="margin:10px 0 0;">
    <span>
      <?php echo $this->translate("Note: SFTP service should be enabled on the server in case of creation of SFTP destination, <a  href='admin/sitebackup/manage/destination-faq/show/8#faq_8' target='_blank'>Go to</a> FAQ.");
      ?>
    </span>
  </div> 
<?php endif; ?>
<?php if( $this->destination_mode == 6 && version_compare(phpversion(), '5.5.0', '<=') ): ?>
  <div class="tip" style="margin:10px 0 0;">
    <span>
      <?php echo $this->translate("Note: To add Dropbox as destination, you must have PHP version greater than or equal to 5.5.0 on your website.");
      ?>
    </span>
  </div> 
<?php endif; ?>
<div class='clear'>
  <div class='settings'>
    <?php if( $this->destination_mode == 5 ): ?>
      <a href="admin/sitebackup/manage/destination-faq/show/4#faq_4" target="_blank"
         class="buttonlink" style="background-image:url(<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sitebackup/externals/images/help16.gif);padding-left:23px;"><?php
           echo $this->translate("How do I generate my Google Drive API Key?")
           ?>
      </a>
    <?php elseif( $this->destination_mode == 6 ): ?>
      <a href="admin/sitebackup/manage/destination-faq/show/6#faq_6" target="_blank"
         class="buttonlink" style="background-image:url(<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sitebackup/externals/images/help16.gif);padding-left:23px;"><?php
        echo $this->translate("How do I generate my Dropbox App Key?")
        ?>
      </a>
    <?php endif; ?>
    <?php echo $this->form->render($this); ?>

  </div>

</div>

<script type="text/javascript" >
//HERE WE CREATE A FUNCTION FOR SHOWING THE DROPDOWN BLOCK OF AUTOMATIC BACKUP OR SIMPLE BACKUP OPTIONS.
  window.addEvent('domready', function () {
    showfields(<?php echo $this->destination_mode; ?>);

  });

  function 	showfields(destination_mode) {

    $('email-wrapper').style.display = 'none';

    $('ftphost-wrapper').style.display = 'none';
    $('ftpuser-wrapper').style.display = 'none';
    $('ftppassword-wrapper').style.display = 'none';
    $('ftpportno-wrapper').style.display = 'none';
    $('ftppath-wrapper').style.display = 'none';
    $('ftpdirectoryname-wrapper').style.display = 'none';
    $('ftpmdb-wrapper').style.display = 'none';
    $('ftpmfile-wrapper').style.display = 'none';
    $('ftpadb-wrapper').style.display = 'none';
    // $('ftpafile-wrapper').style.display='none';
    $('ftpmsg-wrapper').style.display = 'none';

    $('dbhost-wrapper').style.display = 'none';
    $('dbuser-wrapper').style.display = 'none';
    $('dbpassword-wrapper').style.display = 'none';
    $('dbname-wrapper').style.display = 'none';

    $('accesskey-wrapper').style.display = 'none';
    $('secretkey-wrapper').style.display = 'none';
    $('region-wrapper').style.display = 'none';
    $('bucket-wrapper').style.display = 'none';

    $('clientid-wrapper').style.display = 'none';
    $('clientsecret-wrapper').style.display = 'none';

    $('appkey-wrapper').style.display = 'none';
    $('appsecret-wrapper').style.display = 'none';

    switch (destination_mode)
    {

      case 1:
        $('email-wrapper').style.display = 'block';
        break;
      case 2:
        $('ftphost-wrapper').style.display = 'block';
        $('ftpuser-wrapper').style.display = 'block';
        $('ftppassword-wrapper').style.display = 'block';
        $('ftpportno-wrapper').style.display = 'block';
        $('ftppath-wrapper').style.display = 'block';
        $('ftpdirectoryname-wrapper').style.display = 'block';
        $('ftpmsg-wrapper').style.display = 'block';
        $('ftpmdb-wrapper').style.display = 'block';
        $('ftpmfile-wrapper').style.display = 'block';
        $('ftpadb-wrapper').style.display = 'block';
        break;
      case 3:
        $('dbhost-wrapper').style.display = 'block';
        $('dbname-wrapper').style.display = 'block';
        $('dbuser-wrapper').style.display = 'block';
        $('dbpassword-wrapper').style.display = 'block';
        break;
      case 4:
        $('accesskey-wrapper').style.display = 'block';
        $('secretkey-wrapper').style.display = 'block';
        $('region-wrapper').style.display = 'block';
        $('bucket-wrapper').style.display = 'block';
        break;
      case 5:
        $('clientid-wrapper').style.display = 'block';
        $('clientsecret-wrapper').style.display = 'block';
        break;
      case 6:
        $('appkey-wrapper').style.display = 'block';
        $('appsecret-wrapper').style.display = 'block';
        break;
    }
  }
</script>
