<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: destination-faq.tpl 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('Website Backup and Restore Plugin') ?></h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<p>Browse the different FAQ sections of this plugin by clicking on the corresponding tabs below.</p>
<br />
<?php if( count($this->faq_navigation) ): ?>
  <div class='seaocore_admin_tabs sitebackup_faq_admin_tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->faq_navigation)->render() ?>
  </div>
<?php endif; ?>
<?php if( $this->show ): ?>
  <script type="text/javascript">
    en4.core.runonce.add(function () {
      var faq = '<?php echo $this->show ?>';
      var id = $('faq_' + faq);
      if ($(id)) {
        faq_show(id);
      }
    });
  </script>
<?php endif; ?>

<script type="text/javascript">
  function faq_show(id) {
    if ($(id).style.display == 'block') {
      $(id).style.display = 'none';
    } else {
      $(id).style.display = 'block';
    }
  }
</script>
<style type="text/css">
  .sitebackup_faq li div.faq{
    clear:both;
    border-left:3px solid #ccc;
    padding:5px 5px 5px 10px;
    margin:5px;
    font-family:arial;
    line-height: 18px; 
  }
  .faq_frame {
    padding: 5px 5px 5px 0px;
    margin: 5px 5px 5px 0px;
  }
  ul.sitebackup_faq b {
    font-weight: bold;
  }
  ul.sitebackup_faq {
    padding-left:15px;
    max-height: 4000px;
  }
  ul.sitebackup_faq {
    border-radius:0 0 5px 5px;
  }
</style>

<div class="admin_files_wrapper" style="width:100%;">

  <ul class="admin_files sitebackup_faq">
    <br style="clear:both;" />
    <h3>Download</h3>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo $this->translate('1. When I am trying to download a backup, I am being asked to enter username and password. Where do I get these details from?'); ?></a>
      <div class='faq' style='display: none;' id='faq_1'>
        <?php echo $this->translate("Your downloadable backups are stored in your server's backup directory. This directory is password protected, which can be configured by you. You can view / edit these credentials in the \"Destinations\"→\"Server\" section in the admin panel."); ?>
      </div>
    </li>

    <br style="clear:both;" />
    <h3>Email</h3>	

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo $this->translate('1. Why I am not able to take backup of my Database on Email destination?'); ?></a>
      <div class='faq' style='display: none;' id='faq_2'>
        <?php echo $this->translate("In case of Email, backup is mailed as an attachment. Size limit of the attachment is 20Mb. So, if size of your database is greater than 20Mb then you will not be able to use Email as backup destination."); ?>
      </div>
    </li>

    <br style="clear:both;" />
    <h3>Amazon S3</h3>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo $this->translate("1. How can I setup the Amazon S3 as backup destination?"); ?></a>
      <div class='faq' style='display: none;' id='faq_3'>
        <ul>
          Follow below steps to setup the Amazon S3 as backup destination:<br />
          a. Go to <a href="https://aws.amazon.com/s3/" target="_blank">Amazon S3</a> and Choose Get started with Amazon S3.<br />
          b. Follow the on-screen instructions.<br />
          c. AWS will notify you by email when your account is active and available for you to use.<br />
          d. You now add Amazon S3 as a backup destination from: ‘Destinations’ → ‘Remote Destinations’ → ‘Create New Destination’.
        </ul>
      </div>
    </li>

    <br style="clear:both;" />
    <h3>Google Drive</h3>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php echo $this->translate("1. How do I generate my Google Drive API Key?"); ?></a>
      <div class='faq' style='display: none;' id='faq_4'>
        <ul>
          1. Go to <a href="https://code.google.com/apis/console/" target="_blank">Google API Console</a> sign in with your google account.<br />
          2. Click on → Create Project. [Note: Don’t include space and special characters in the name of the project.]<br />
          3. Enable ‘Drive API’ to activate Drive API Services.<br /> 
          4. Go to ‘Credentials’ on the left panel of your screen.<br />
          5. Now, click on ‘Create Credentials’ and select ‘OAuth client ID’.<br />
          6. To create an OAuth client ID, we must first set a product name on the consent screen. To do so, click on ‘Configure Consent Screen’ and fill the required details. <br />
          7. Select ‘Web Application’ in Application Type. Fill the required details like name of the web application and Authorized redirect URIs.<br />
          Copy this url for Authorized redirect URIs: <?php echo $this->absoluteUrl($this->baseUrl('admin/sitebackup/destinationsettings/verify')); ?><br />
          8. Open the Web Application you have created to get the ‘Client ID’ and ‘Client Secret Key’.<br />
          9. After saving the ‘Client ID’ and ‘Client Secret Key’ in the global setting, you will be directed to a different URL. You will see a pop-up there, click on ‘Allow Access’ to authorize your product (created in step 5) to access your Google Drive.<br /><br />
          <b>Note:</b> You can refer this video to know how to perform the above steps for Google Drive API configuration for your website:

          <div class="faq_frame"><iframe width="850" height="400" src="https://www.youtube.com/embed/YpdZB4F79mk" frameborder="0" allowfullscreen></iframe>
          </div>
        </ul>  
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_5');"><?php echo $this->translate("2.  I have used full quota of the Google Drive added as backup destination. How can I increase the storage to be able to use Google Drive for backup process?"); ?></a>
      <div class='faq' style='display: none;' id='faq_5'>
        <?php echo "If you have consumed the existing quota of your Google Drive, then, please <a class='' href='https://www.google.com/drive/pricing/' target='_blank'>click here</a> to get details about the storage subscription."; ?>
      </div>
    </li>

    <br style="clear:both;" />
    <h3>Dropbox</h3>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_6');"><?php echo $this->translate("1. How do I generate my Dropbox App Key?"); ?></a>
      <div class='faq' style='display: none;' id='faq_6'>
        <ul>
          1. Go to sign-in page of <a href="https://www.dropbox.com/developers" target="_blank">Dropbox</a> for developers.<br />
          2. Sign-in into your dropbox account.<br />
          3. Click on → Create your app.<br />
          4. When asked to choose an API, select <b>Dropbox API</b>. Choose the type of access your application, select <b>Full Dropbox</b>. The app name can be whatever you like.<br /> 
          5. When you're done, click <b>Create app</b>.<br />
          6. On the next page, specify the callback URL below as a <b>Redirect URIs</b>.<br />
          Copy this url for Authorized redirect URIs: <?php echo $this->absoluteUrl($this->baseUrl('admin/sitebackup/destinationsettings/verify')); ?><br />
          7. Now, use this App Key and App Secret for creating backup destination on Dropbox.
        </ul>  
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_7');"><?php echo $this->translate("2. Error is coming when I try to add the Dropbox as backup destination. What might be the reason behind it?"); ?></a>
      <div class='faq' style='display: none;' id='faq_7'>
        <?php echo $this->translate("Check the version of PHP on your website to use Dropbox for backup of your website."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("Version Requirement: PHP 5.5.0"); ?>
      </div>
    </li>

    <br style="clear:both;" />
    <h3>FTP Directory</h3>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_8');"><?php echo $this->translate("1. How do you install and configure \"PHP-SSH2\" with your Server.?"); ?></a>
      <div class='faq' style='display: none;' id='faq_8'>
        <?php echo $this->translate("To install and configure \"PHP-SSH2\" with your server, please contact to your hosting administrator."); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_9');"><?php echo $this->translate("2. I am not able to create a \"FTP Server\" destination. What should I do? \"OR\" After submitting the form for creating a \"FTP Server\" destination, I get a blank page. Why is this so?"); ?></a>
      <div class='faq' style='display: none;' id='faq_9'>
        <?php echo $this->translate("You will be able to create a \"FTP Server\" destination only if your site's server has permissions to make outgoing FTP connections with other FTP servers for transfers. Please contact your server administrator for this."); ?>
      </div>
    </li>

    <br style="clear:both;" />
    <h3>Common</h3>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_10');"><?php echo $this->translate("1. What is the storage limit for all the 7 backup destinations? \"OR\" How can I expand the current storage limit of my backup destination?"); ?></a>

      <div class='faq' style='display: none;' id='faq_10'>
        <?php echo $this->translate("a) <b>Download from Server:</b> Storage limit for backup is dependent on the memory limit available on server. Expand the memory limit of server to expand the storage for website’s backup on your server."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("b) <b>FTP Directory:</b> Storage limit for backup is dependent on the disk space of FTP Server. To expand this storage limit, please contact your FTP Server Provider."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("c) <b>Google Drive:</b> Google provides 15 GB of free storage to use across Google Drive, Gmail, and Google Photos. To expand the quota of Google Drive and to get details about the storage subscription, please <a href ='https://www.google.com/drive/pricing/' target='_blank'>click here</a>."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("d) <b>Dropbox:</b> It provides 2 GB of free storage. To expand the quota of Dropbox and to get details about the pricing, please <a href='https://www.dropbox.com/individual/plans-comparison' target='_blank'>click here</a>."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("e) <b>Amazon S3:</b> Amazon provides 5 GB of free space with AWS S3 service. To expand the space of AWS S3 and to get the details about the pricing, please <a href='https://aws.amazon.com/s3/pricing/' target='_blank'>click here</a>."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("f) <b>MySQL Database:</b> The storage limit of MySQL Database depends on the Hosting Provider. To expand the storage limit, please contact your Hosting Provider."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("g) <b>Email:</b> There is no storage limit for email as backup is send as an attachment here. So, size of the attachment should be less than or equal to 20Mb."); ?>
      </div>
    </li>
  </ul>
</div>