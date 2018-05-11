<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: faq.tpl 2010-10-25 9:40:21Z SocialEngineAddOns $
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
    border-radius:0 0 5px 5px;
  }
</style>

<div class="admin_files_wrapper" style="width:100%;">
  <ul class="admin_files sitebackup_faq" style="max-height:2500px;">

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo $this->translate("1. What are backups? Why do I need them?"); ?></a>
      <div class='faq' style='display: none;' id='faq_1'>
        <?php echo $this->translate("Backups are like insurance for your site. You need them the most when your site is in trouble like data loss, data corruption, server crash, site attack, etc. Backups are the copies of your data which may be used to restore your site after such mishappenings. Backups can also be used to migrate your site to another server. Your site's data and content are its life. The Website Backup and Restore Plugin helps you protect them."); ?>
      </div>
    </li>	

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo $this->translate("2.  What types of backups I can take with this plugin?"); ?></a>
      <div class='faq' style='display: none;' id='faq_2'>
        <?php echo $this->translate("This plugin enables you to take backup of all your website’s content. Thus, it allows you to take both:"); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("a) Database backup"); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("b) Files backup"); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo $this->translate("3. What is Files Backup? Why is it needed?"); ?></a>
      <div class='faq' style='display: none;' id='faq_3'>
        <?php echo $this->translate("Files backup allows you to take the backup of your site’s code, and the files uploaded on your site like user profile photos, album photos, group photos, music content, etc."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("Files backup is needed so that you do not lose any customizations done to your site, or content uploaded on your site during a server crash, or any other accident."); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php echo $this->translate("4. What are backup destinations? What are their different types?"); ?></a>
      <div class='faq' style='display: none;' id='faq_4'>
        <?php echo $this->translate('Backup destinations are the locations where your backup files are saved. This plugin allows you to create multiple remote destinations of the following types:'); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("a) Email: Database backups can be emailed as attachments.") ?>
        <br style="clear:both;" />
        <?php echo $this->translate("b) FTP Directory: Backups can be directly saved on external FTP servers.") ?> 
        <br style="clear:both;" />
        <?php echo $this->translate("c) MySQL Database: Database backups can be taken on other MySQL databases on the server. There is also a backup directory on the site server.") ?> 
        <br style="clear:both;" />
        <?php echo $this->translate("d) Amazon S3: Backups can be directly saved in Amazon S3 buckets.") ?> 
        <br style="clear:both;" />
        <?php echo $this->translate("e) Google Drive: Backups can be saved in Google Drive of the associated gmail address.") ?> 
        <br style="clear:both;" />
        <?php echo $this->translate("f) Dropbox: Directly save your site’s backups on Dropbox’s server.") ?> 
        <br style="clear:both;" /><br style="clear:both;" />
        <?php echo $this->translate("Backup files can also be downloaded to your computer and saved on storage devices like hard disks, CDs, DVDs, etc.") ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_5');"><?php echo $this->translate('5. What is automatic backup?'); ?></a>
      <div class='faq' style='display: none;' id='faq_5'>
        <?php echo $this->translate("You can schedule backups to be automatically performed after specified intervals (like every 6 hours, 12 hours, 1 day, 2 days, 1 week, etc.). Automatic backups can be configured from the \"Global Settings\" section in the Admin Panel."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("[Note: If your site is inactive, no auto-backups will be taken till there is activity on site.]"); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_6');"><?php echo $this->translate("6. How will I know when an automatic backup has completed?"); ?></a>
      <div class='faq' style='display: none;' id='faq_6'>
        <?php echo $this->translate("In the \"Global Settings\" section, you can enable email notifications and can set the email address to receive email notification on completion of automatic backup process."); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_7');"><?php echo $this->translate("7. I want to take automatic backup of my site's database. Are there any points that I should take care of before starting the automatic backup process?"); ?></a>
      <div class='faq' style='display: none;' id='faq_7'>
        <?php echo $this->translate("Yes, below are some recommended points that you should take care of before going for automatic backups of your site's database:"); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("1) Your site should have good server resources.") ?>
        <br style="clear:both;" />
        <?php echo $this->translate("2) Your site should be active.") ?> 
        <br style="clear:both;" />
        <?php echo $this->translate("3) Regularly check the backup logs for the success / failure status of the automatic backups done.") ?> 
        <br style="clear:both;" />
        <?php echo $this->translate("4) If you have activated automatic database backups for your site, then sometime you should also take manual backups.") ?>				
        <br style="clear:both;" />
        <?php echo $this->translate("5) The time interval selected by you should be at least 5 times the time taken for a manual backup of your site. You can take a manual backup of your site to find the time duration. For example, if your site's manual backup takes 4 hours to complete, then the time interval chosen by you here must be equal or greater then than 20 hours.") ?>
      </div>
    </li>		

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_8');"><?php echo $this->translate("8. What is the recommended limit for the automatic deletion of backups in the Destinations Server tab?"); ?></a>
      <div class='faq' style='display: none;' id='faq_8'>
        <?php echo $this->translate("The limit should be according to the size of your backup files, and the space on your server. Normally, you can enter a value of 3 for the count of backups, but if the space on your server is less, you can enter 1."); ?>
      </div>
    </li>		

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_9');"><?php echo $this->translate("9. Does this plugin provide automatic files backup also?"); ?></a>
      <div class='faq' style='display: none;' id='faq_9'>
        <?php echo $this->translate("No, this plugin does not provide automatic files backup. Although, you can take automatic database backups."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("Automatic files backups are not provided by this plugin because size of these files can be very large as they contain media files of your site (user profile pictures, album pictures, music, etc)."); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_10');"><?php echo $this->translate('10. Are there any precautions which I should take while restoring my database?'); ?></a>
      <div class='faq' style='display: none;' id='faq_10'>
        <?php echo $this->translate("Yes, a failed restore can destroy your database. Always test database backup files on a non-production (test environment) first."); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_11');"><?php echo $this->translate('11. Can I use other tools for restoring the database using the backup created by this plugin?'); ?></a>
      <div class='faq' style='display: none;' id='faq_11'>
        <?php echo $this->translate("Yes, the database backup files created by this plugin are a list of SQL statements which can also be imported / executed by other database handling tools like phpMyAdmin or the mysql command-line client."); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_12');"><?php echo $this->translate('12. In the “Restore Database” section, can I restore the database by uploading a backup / SQL file taken from another database backup source?'); ?></a>
      <div class='faq' style='display: none;' id='faq_12'>
        <?php echo $this->translate("No, the “Restore Database” functionality of this plugin requires a backup file generated only by this plugin for restoring your database."); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_13');"><?php echo $this->translate('13. What will happen if I restore my database from a backup not having all the database tables?'); ?></a>
      <div class='faq' style='display: none;' id='faq_13'>
        <?php echo $this->translate("The database tables available in your backup will be restored and the other tables will remain as they are."); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_14');"><?php echo $this->translate('14.  Does this plugin provide both database and files restore functionalities?'); ?></a>
      <div class='faq' style='display: none;' id='faq_14'>
        <?php echo $this->translate("No, this plugin only restores the database."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("To restore the files of your site from a files backup, you could simply use an FTP client which you would have used for uploading SocialEngine code on your server."); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_15');"><?php echo $this->translate('15. My site is in Maintenance Mode, and I do not remember the Access Code. How can I get it online again?'); ?></a>
      <div class='faq' style='display: none;' id='faq_15'>
        <?php echo $this->translate("You may follow the following steps to get your site online again:"); ?>
        <br style="clear:both; "/>
        <?php echo $this->translate("1) On your site's server, go to the directory: \"application/settings\"."); ?>
        <br style="clear:both; "/>
        <?php echo $this->translate("2) Open the file: \"general.php\"."); ?>
        <br style="clear:both; "/>
        <?php echo $this->translate("3) In this file, change the line: \"'enabled' => true\" to: \"'enabled' => false\"."); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_16');"><?php echo $this->translate('16. How can this plugin help me in migrating my site?'); ?></a>
      <div class='faq' style='display: none;' id='faq_16'>
        <?php echo $this->translate("The Website Backup and Restore Plugin is very useful if you want to migrate your website. You may follow the below steps:"); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("1) Take recent Database and Files Backups of your site using the Website Backup and Restore Plugin."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("2) In order to install your site, you will need the following four pieces of information. If you don't have any of these, please contact your hosting provider and ask them for assistance."); ?>
        <br style="clear:both;" />
        &nbsp;&nbsp;<?php echo $this->translate("- MySQL Server Address (often \"localhost\", \"127.0.0.1\", or the server IP address)"); ?>
        <br style="clear:both;" />
        &nbsp;&nbsp;<?php echo $this->translate("- MySQL Database Name"); ?>
        <br style="clear:both;" />
        &nbsp;&nbsp;<?php echo $this->translate("- MySQL Username"); ?>
        <br style="clear:both;" />
        &nbsp;&nbsp;<?php echo $this->translate("- MySQL Password"); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("3) On your new server, create a database with a name of your choice."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("4) Use a database handling tool like phpMyAdmin or the mysql command line to import the database backup file into the new database."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("5) Extract the zipped Files backup on your computer and upload all of the files to your hosting account (it can exist either in the root HTML directory, or a subdirectory)."); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("6) If you are using a Unix server (or Unix variant, like Linux, OS X, FreeBSD, etc.) you must set the permissions (CHMOD) of the following directories and files to 777:"); ?>
        <br style="clear:both;" />
        &nbsp;&nbsp;<?php echo $this->translate("- /install/config/ (recursively; all directories and files contained within this must also be changed)"); ?>
        <br style="clear:both;" />
        &nbsp;&nbsp;<?php echo $this->translate("- /temporary/ (recursively; all directories and files contained within this must also be changed)"); ?>
        <br style="clear:both;" />
        &nbsp;&nbsp;<?php echo $this->translate("- /public/ (recursively; all directories and files contained within this must also be changed)"); ?>
        <br style="clear:both;" />
        &nbsp;&nbsp;<?php echo $this->translate("- /application/themes/ (recursively; all directories and files contained within this should also be changed)"); ?>
        &nbsp;&nbsp;<?php echo $this->translate("- /application/packages/ (recursively; all directories and files contained within this should also be changed)"); ?>
        <br style="clear:both;" />
        &nbsp;&nbsp;<?php echo $this->translate("- /application/languages/ (recursively; all directories and files contained within this must also be changed)"); ?>
        <br style="clear:both;" />
        &nbsp;&nbsp;<?php echo $this->translate("- /application/settings/ (recursively; all files contained within this must also be changed)"); ?>
        <br style="clear:both;" />
        <?php echo $this->translate("7) Update the settings of your new server in the file: \"/application/settings/database.php\" to point to your new database."); ?>s
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_17');"><?php echo $this->translate("17. When I am taking the database and file backup then the exception “ 'RuntimeException' with message 'SplFileInfo::isFile():' open_basedir restriction in effect error ” is coming. What should I do?"); ?></a>
      <div class='faq' style='display: none;' id='faq_17'>
        <?php echo $this->translate("Please go to the Admin => Stats => Server Information and search the 'open_basedir'. In this, you have set the path '/var/www/vhosts/www.matesspace.net/httpdocs/:/tmp/' please replace that path to the '/var/www/vhosts/www.matesspace.net/httpdocs/'."); ?>
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_18');"><?php echo $this->translate('18. The CSS of this plugin is not coming on my site. What should I do?'); ?></a>
      <div class='faq' style='display: none;' id='faq_18'>
        <?php echo $this->translate("Please enable the \"Development Mode\" system mode for your site from the Admin homepage and then check the page which was not coming fine. It should come fine now. You can again change the system mode to \"Production Mode\"."); ?>
      </div>
    </li>

  </ul>
</div>