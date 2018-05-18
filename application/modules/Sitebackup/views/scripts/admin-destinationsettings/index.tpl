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
<style type="text/css">
  .settings form{
    width:930px;
  }
  .sitebackup_form
  {
    -moz-border-radius: 7px;
    -webkit-border-radius: 7px;
    border-radius:  7px;
    background-color: #e9f4fa;
    padding: 10px;
    float: left;
    overflow: hidden;
    margin-top:10px;
    clear:both;
    width:930px;
  }
  .sitebackup_form .sitebackup_form_inner
  {
    background: #fff;
    border: 1px solid #d7e8f1;
    overflow: hidden;
    padding: 20px;
  }
  .settings h3
  {
    margin-left: -1px;
    color:#717171;
    font-size:13pt;
    font-weight:bold;
    letter-spacing:-1px;
    margin:0 0 10px 0;
  }
  .settings .form-description
  {
    max-width:900px;
  }
  .settings .form-element .description{
    max-width:650px;
  }
</style>
<h2><?php echo $this->translate('Website Backup and Restore Plugin') ?></h2>


<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
    //->setUlClass()
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>
<div style="margin: 0 0 10px 0;text-align: justify;">
<?php echo $this->translate('Destinations are the storage locations where your site’s backup files and database are saved. There are two options available as destination: Server and Remote Destinations. In case of Server, backup directory will be used for backup process. Whereas for Remote Destinations, there are various options available like Amazon S3, Dropbox, Google Drive etc.') ?>
</div>
  <div class='sitebackup_admin_tabs'>
    <ul>
      <li class="active" id='server_directory_temp'>
        <a href="javascript:void(0);" onclick="showdestination_block('server_directory', 'other_server_directory', 'server_directory_temp', 'other_server_directory_temp')" ><?php echo $this->translate('Server') ?></a>
      </li>
      <li id='other_server_directory_temp'>	
        <a href="javascript:void(0);" onclick="showdestination_block('other_server_directory', 'server_directory', 'other_server_directory_temp', 'server_directory_temp')"><?php echo $this->translate('Remote Destinations') ?></a>
      </li>
    </ul>		

  </div>

<div id="server_directory">
  <div class='settings' style="margin-top:10px;float:left;">

    <?php if( $this->message == 1 ): ?>
      <ul class="form-notices" >
        <li style="font-size:12px;">
          <?php $base_url = Zend_Controller_Front::getInstance()->getBaseUrl(); ?>
          <?php echo "Your settings have been saved successfully. You may confirm the password protection for your backup directory by <a href='$base_url/public/$this->currentdirectory/password_check.txt' target='_blank'>clicking here</a>."; ?>
        </li>
      </ul>
    <?php elseif( $this->message == 2 ): ?>
      <ul class="form-notices" >
        <li style="font-size:12px;">
          <?php $base_url = Zend_Controller_Front::getInstance()->getBaseUrl(); ?> 
          <?php echo "Your settings have been saved successfully."; ?>

        </li>
      </ul>

    <?php elseif( !empty($this->error) ): ?>
      <ul class="form-errors" >
        <li style="font-size:12px;">
          <?php echo $this->error; ?>
        </li>
      </ul>
    <?php endif; ?>
    <?php echo $this->form->render($this); ?>
  </div>
</div>

<div id='other_server_directory' style="display:none;">
  <div class="sitebackup_form">
    <div class="sitebackup_form_inner">
      <h3>
        <?php echo $this->translate('Remote Destinations') ?>
      </h3>
      <p class="form-description">
        <?php echo $this->translate('Here, you can manage the remote destinations created to take backup of your site’s database and files. Click on ‘Create a Destination’ link to add a new storage location for the backup process.') ?>
      </p>
      <div class="sitebackup_create_destination">
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sitebackup', 'controller' => 'destinationsettings', 'action' => 'destination'), $this->translate('Create a Destination')) ?>
      </div>
      <div class='clear' style="margin-top:10px;float:left;clear:both;">
        <div class='settings'>
          <?php if( count($this->paginator) != 0 ): ?>
            <table class='admin_table' style="width:890px;" >
              <thead>
                <tr>
                  <th style="text-align:left;"> <?php echo $this->translate('Name') ?></th>
                  <th style="text-align:left;"> <?php echo $this->translate('Type') ?> </th>
                  <th style="text-align:left;"> <?php echo $this->translate('Location') ?> </th>
                  <th style="text-align:center;">
                    <?php echo $this->translate('Options') ?>
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach( $this->paginator as $item ): ?>
                  <tr>
                    <td title="<?php echo $item->destinationname ?>"style="white-space:normal;">
                      <div style="min-width:10%;overflow:hidden;">
                        <?php echo $item->destinationname ?>
                      </div>
                    </td>
                    <td>
                      <?php
                      switch( $item->destination_mode ) {

                        case 1: echo $this->htmlImage('application/modules/Sitebackup/externals/images/email.png', '', array('title' => $this->translate('Email')));
                          break;
                        case 2: echo $this->htmlImage('application/modules/Sitebackup/externals/images/ftp.png', '', array('title' => $this->translate('FTP Directory')));
                          break;
                        case 3: echo $this->htmlImage('application/modules/Sitebackup/externals/images/database.png', '', array('title' => $this->translate('MySQL Database')));
                          break;
                        case 4: echo $this->htmlImage('application/modules/Sitebackup/externals/images/s3.png', '', array('title' => $this->translate('Amazon S3')));
                          break;
                        case 5: echo $this->htmlImage('application/modules/Sitebackup/externals/images/drive.png', '', array('title' => $this->translate('Google Drive')));
                          break;
                        case 6: echo $this->htmlImage('application/modules/Sitebackup/externals/images/dropbox.png', '', array('title' => $this->translate('Google Drive')));
                          break;
                      }
                      ?>

                    </td>
                    <td>
                      <div title="<?php
                      switch( $item->destination_mode ) {
                        case 0: echo "$item->sitebackup_directoryname";
                          break;
                        case 1: echo "$item->email";
                          break;
                        case 2: echo "ftp://$item->ftpuser@$item->ftphost:$item->ftpportno/$item->ftppath";
                          break;

                        case 3: echo "mysqli://$item->dbuser@$item->dbhost/$item->dbname";
                          break;
                        case 4: echo "$item->bucket";
                          break;
                        case 5: echo "https://drive.google.com/drive/my-drive";
                          break;
                        case 6: echo "https://www.dropbox.com/home/backup";
                          break;
                      }
                      ?>" style="width:400px;">
                           <?php
                           switch( $item->destination_mode ) {
                             case 0: echo "$item->sitebackup_directoryname";
                               break;
                             case 1: echo "$item->email";
                               break;
                             case 2: echo substr(strip_tags("ftp://$item->ftpuser@$item->ftphost:$item->ftpportno/$item->ftppath"), 0, 80);
                               if( strlen("ftp://$item->ftpuser@$item->ftphost:$item->ftpportno/$item->ftppath") > 80 )
                                 echo "...";
                               break;
                             case 3: echo substr(strip_tags("mysqli://$item->dbuser@$item->dbhost/$item->dbname"), 0, 80);
                               if( strlen("mysqli://$item->dbuser@$item->dbhost/$item->dbname") > 80 )
                                 echo "...";
                               break;
                             case 4:echo "$item->bucket";
                               break;
                             case 5:echo "https://drive.google.com/drive/my-drive";
                               break;
                             case 6: echo "https://www.dropbox.com/home/backup";
                               break;
                           }
                           ?>
                      </div>
                    </td>

                    <td class="admin_table_centered">
                      <?php
                      if( $item->destination_mode != 0 ):
                        echo $this->htmlLink(
                          array('route' => 'admin_default', 'module' => 'sitebackup', 'controller' => 'destinationsettings', 'action' => 'edit', 'id' => $item->destinations_id, 'show' => 1), $this->translate('Edit'), array()
                        );
                      endif;
                      ?>
                      |
                      <?php
                      echo $this->htmlLink(
                        array('route' => 'admin_default', 'module' => 'sitebackup', 'controller' => 'destinationsettings', 'action' => 'delete', 'id' => $item->destinations_id, 'show' => 1), $this->translate('Delete'), array('class' => 'smoothbox')
                      )
                      ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
//HERE WE CREATE A FUNCTION FOR SHOWING THE DROPDOWN BLOCK OF AUTOMATIC BACKUP OR SIMPLE BACKUP OPTIONS.
  window.addEvent('domready', function () {
    showhide(<?php echo $this->backup_enable; ?>);

    var show_block = '<?php echo $this->destination_block; ?>'

    if (show_block == 1) {
      $('other_server_directory').style.display = 'block';
      $('server_directory').style.display = 'none';
    } else {
      $('other_server_directory').style.display = 'none';
      $('server_directory').style.display = 'block';
    }
    if (show_block == 1) {
      showdestination_block('other_server_directory', 'server_directory', 'other_server_directory_temp', 'server_directory_temp')
    } else {
      showdestination_block('server_directory', 'other_server_directory', 'server_directory_temp', 'other_server_directory_temp')
    }
  });

  function showhide(option) {

    if (option == 1) {

      $('htusername-wrapper').style.display = 'block';
      $('htpassword-wrapper').style.display = 'block';
    } else {

      $('htusername-wrapper').style.display = 'none';
      $('htpassword-wrapper').style.display = 'none';
    }
  }


  function showdestination_block(showid, hideid, id1, id2) {

    $(id1).set('class', 'active');
    $(id2).erase('class');
    $(showid).style.display = 'block';
    $(hideid).style.display = 'none';

  }
</script>
<script type="text/javascript">
  var display_msg = 0;

  //HERE WE CREATE A FUNCTION FOR SHOWING THE DROPDOWN BLOCK OF AUTOMATIC BACKUP OR SIMPLE BACKUP OPTIONS.
  window.addEvent('domready', function () {
    showdeleteblock(<?php echo $this->autodeleteoption; ?>);
    showdeletecodeblock(<?php echo $this->autodeletecodeoption; ?>);
    display_msg = 1;
  });

  //HERE WE CREATE A FUNCTION FOR SHOWING THE DROPDOWN BLOCK OF AUTOMATIC BACKUP OR SIMPLE BACKUP OPTIONS.
  function showdeleteblock(option) {
    if ($('sitebackup_deletelimit-wrapper')) {
      if (option == 1) {
        $('sitebackup_deletelimit-wrapper').style.display = 'block';
      } else {
        $('sitebackup_deletelimit-wrapper').style.display = 'none';
      }
    }
  }

  //HERE WE CREATE A FUNCTION FOR SHOWING THE DROPDOWN BLOCK OF AUTOMATIC BACKUP OR SIMPLE BACKUP OPTIONS.
  function showdeletecodeblock(option) {
    if ($('sitebackup_deletecodelimit-wrapper')) {
      if (option == 1) {
        $('sitebackup_deletecodelimit-wrapper').style.display = 'block';
      } else {
        $('sitebackup_deletecodelimit-wrapper').style.display = 'none';
      }
    }
  }

</script>