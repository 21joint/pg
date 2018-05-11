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
<script type="text/javascript">
  function clickable() {
    var i;
    var multidelete_form = $('multidelete_form');
    var inputs = multidelete_form.elements;
    var href;
    for (i = 1; i < inputs.length; i++) {
      if (!inputs[i].disabled) {
        if (inputs[i].checked) {
          if (href != undefined) {
            href = href + ',' + inputs[i].value;
          } else {
            href = en4.core.baseUrl + 'sitebackup/admin-codebackup/deleteselected/ids/' + inputs[i].value;
          }
        }
      }
    }
    $('selectedd').href = href;
  }

  function selectAll()
  {
    var i;
    var href;
    var multidelete_form = $('multidelete_form');
    var inputs = multidelete_form.elements;
    for (i = 1; i < inputs.length; i++) {
      if (!inputs[i].disabled) {
        inputs[i].checked = inputs[0].checked;
        if (href != undefined) {
          href = href + ',' + inputs[i].value;
        } else {
          href = en4.core.baseUrl + 'sitebackup/admin-codebackup/deleteselected/ids/' + inputs[i].value;
        }
      }
    }
    $('selectedd').href = href;
  }
</script>
<style type="text/css">
  table.admin_table thead tr th ,
  table.admin_table tbody tr td {
    padding:7px 6px;
  }
</style>
<h2><?php echo $this->translate('Website Backup and Restore Plugin') ?></h2>
<div class='tabs'>
  <?php
  echo $this->navigation()->menu()->setContainer($this->navigation)->render()
  ?>
</div>


<p> 
<h3><?php echo $this->translate('Files Backup List'); ?></h3>
<?php echo $this->translate("This page lists all the files backups taken for this site. You can use this page to download / delete the backup files.") ?><br />
<?php if( !empty($this->backup_id) ) : ?>
  <div class="tip" style="margin:10px 0 0;">
    <span>
      <?php echo $this->translate('The last files backup was taken ' . $this->latesttime) ?> ago.
    </span>
  </div> 
<?php else: ?>

<?php endif; ?>
</p>
<?php
$link = $this->htmlLink(
  array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'confirm-delete-code-backup'), $this->translate('Delete All File Backups'), array('class' => 'smoothbox buttonlink sitebackup_icon_delete'));
?>
<?php
$viewlog = $this->htmlLink(
  array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'deleteselected', 'ids' => ''), $this->translate('Delete Selected File Backups'), array('class' => 'smoothbox buttonlink sitebackup_icon_delete', 'id' => 'selectedd'));
?> 

  <?php if( !empty($this->is_filebackup) ) { ?>
    <?php if( count($this->paginator) ): ?>
    <form id='multidelete_form' method='post' >
      <?php echo '<div class="sitebackup_links">'; ?>
      <?php echo $this->translate("%1s", "$link"); ?>
    <?php echo $this->translate("%1s", "$viewlog"); ?>
    <?php echo '</div>'; ?>
      <table class='admin_table'>
        <thead>
          <tr>
            <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
              <?php if( $this->order == 'ASC' ): ?>
              <th class='admin_table_short'><?php
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'index', 'id' => 'sitebackup', 'order' => 'DESC'), $this->translate('Id'))
              ?></th>
              <?php else: ?>
              <th class='admin_table_short'><?php
          echo $this->htmlLink(
            array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'index', 'id' => 'sitebackup', 'order' => 'ASC'), $this->translate('Id'))
          ?></th>
              <?php endif; ?>
            <th style="text-align:left;"><?php echo $this->translate("Filename") ?></th>
            <?php if( $this->order == 'ASC' ): ?>
              <th style="text-align:left;"><?php
                echo $this->htmlLink(
                  array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'index', 'id' => 'time', 'order' => 'DESC'), $this->translate('Date'))
                ?></th>
            <?php else: ?>
              <th style="text-align:left;"><?php
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'index', 'id' => 'time', 'order' => 'ASC'), $this->translate('Date'))
              ?></th>
            <?php endif; ?>

              <?php if( $this->order == 'ASC' ): ?>
              <th style="text-align:left;"><?php
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'index', 'id' => 'method', 'order' => 'DESC'), $this->translate('Method'))
              ?></th>
              <?php else: ?>
              <th style="text-align:left;"><?php
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'index', 'id' => 'method', 'order' => 'ASC'), $this->translate('Method'))
              ?></th>
              <?php endif; ?>
            <?php if( $this->order == 'ASC' ): ?>
              <th style="text-align:left;"><?php
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'index', 'id' => 'destinationname', 'order' => 'DESC'), $this->translate('Destination'))
              ?></th>
              <?php else: ?>
              <th style="text-align:left;"><?php
          echo $this->htmlLink(
            array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'index', 'id' => 'destinationname', 'order' => 'ASC'), $this->translate('Destination'))
                ?></th>
            <?php endif; ?>	
            <th style="text-align:left;"><?php echo $this->translate("Type") ?></th>
            <?php if( $this->order == 'ASC' ): ?>
              <th style="text-align:left;"><?php
                echo $this->htmlLink(
                  array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'index', 'id' => 'status', 'order' => 'DESC'), $this->translate('Status'))
                ?></th>
              <?php else: ?>
              <th style="text-align:left;"><?php
                echo $this->htmlLink(
                  array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'index', 'id' => 'status', 'order' => 'ASC'), $this->translate('Status'))
                ?></th>
    <?php endif; ?>		   

          <?php if( $this->order == 'ASC' ): ?>
              <th style="text-align:left;"><?php
            echo $this->htmlLink(
              array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'index', 'id' => 'filesize', 'order' => 'DESC'), $this->translate('Filesize'))
            ?></th>
            <?php else: ?>
              <th style="text-align:left;"><?php
        echo $this->htmlLink(
          array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'index', 'id' => 'filesize', 'order' => 'ASC'), $this->translate('Filesize'))
        ?></th>
            <?php endif; ?>
            <th style="text-align:left;"><?php echo $this->translate("Options") ?></th>
          </tr>
        </thead>
        <tbody>
            <?php $base_url = Zend_Controller_Front::getInstance()->getBaseUrl(); ?>
            <?php foreach( $this->paginator as $item ): ?>
            <tr>
      <?php
      $backup_filename1 = strip_tags($item->backup_filename1);
      $backup_filename2 = Engine_String::strlen($backup_filename1) > 15 ? Engine_String::substr($backup_filename1, 0, 15) . '..' : $backup_filename1;
      ?>

              <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->backup_id ?>' value="<?php echo $item->backup_id ?>" onclick = "clickable();"/></td>
              <td><?php echo $item->backup_id ?></td>
                <?php if( file_exists(APPLICATION_PATH . '/public/' . $this->dir_name_temp . '/' . $item->backup_filename1) ): ?>
                <td title="Download :<?php echo $item->backup_filename1 ?>">
                  <a href=<?php echo $this->url(array('action' => 'download', 'controller' => 'manage')) ?><?php echo!empty($item->backup_filename1) ? '?path=' . urlencode($item->backup_filename1) : '' ?> target='downloadframe'><?php echo $this->translate($backup_filename2) ?>
                  </a>           
                </td>
                <?php else: ?>
                <td title="<?php echo $item->backup_filename1 ?>"><?php echo $backup_filename2 ?></td>
                <?php endif; ?>
              <td title="<?php echo str_replace("+0000", "", $item->backup_timedescription) ?>" style="white-space:normal;">
                <div style="width:90px;">
                <?php echo str_replace("+0000", "", $item->backup_timedescription); ?>
                </div>
              </td>   
                <?php if( empty($item->backup_auto) ): ?>
                <td style="text-align:left;" title="<?php if( $item->backup_method == 'Download' ): ?><?php echo 'Downloaded to computer' ?><?php elseif( $item->backup_method == 'Server Backup Directory & Download' ): echo 'Backed up on Server & Downloaded to Computer'; ?><?php else: echo $item->backup_method ?><?php endif; ?>">
                <?php else: ?>
                <td style="text-align:left;" title="<?php if( $item->backup_method == 'Download' ): ?><?php echo 'Downloaded to computer' ?><?php elseif( $item->backup_method == 'Server Backup Directory & Download' ): echo 'Backed up on Server'; ?><?php else: echo $item->backup_method ?><?php endif; ?>">
      <?php endif; ?>
                  <?php if( $item->backup_method == 'Download' ): ?>
                    <?php echo $this->htmlImage('application/modules/Sitebackup/externals/images/computer.png', '') ?>
                  <?php elseif( $item->backup_method == 'Server Backup Directory & Download' ): ?>
                    <?php echo $this->htmlImage('application/modules/Sitebackup/externals/images/server.png', '') ?>
                  <?php elseif( $item->backup_method == 'Amazon S3' ): ?>
                    <?php echo $this->htmlImage('application/modules/Sitebackup/externals/images/s3.png', '') ?>
                  <?php elseif( $item->backup_method == 'Google Drive' ): ?>
                    <?php echo $this->htmlImage('application/modules/Sitebackup/externals/images/drive.png', '') ?>
      <?php elseif( $item->backup_method == 'Dropbox' ): ?>
        <?php echo $this->htmlImage('application/modules/Sitebackup/externals/images/dropbox.png', '') ?>
                <?php else: ?>
                  <?php echo $this->htmlImage('application/modules/Sitebackup/externals/images/ftp.png', '') ?>
                <?php endif; ?>
              </td>
              <td title="<?php echo $item->destination_name ?>" style="white-space:normal;"> 
                <div style="width:100px;text-align:center;overflow:hidden;">	
      <?php
      if( $item->destination_name == 'Download to computer' ) {
        echo "Local (My Computer)";
      } else {
        echo $item->destination_name;
      }
      ?> 
                </div>
              </td>
              <td style="white-space:normal;"> 
                <?php if( empty($item->backup_auto) ): ?><?php echo $this->translate('Manual'); ?> <?php else: echo $this->translate('Automatic');
                endif;
                ?> 
              </td>              
              <td> 
      <?php echo $this->translate('Success'); ?>  
              </td>

              <td><?php echo $item->backup_filesize1 ?></td>
              <td>
      <?php if( empty($item->backup_status) ): ?>
        <?php if( file_exists(APPLICATION_PATH . '/public/' . $this->dir_name_temp . '/' . $item->backup_filename1) ): ?>
                    <a href=<?php echo $this->url(array('action' => 'download', 'controller' => 'manage')) ?><?php echo!empty($item->backup_filename) ? '?path=' . urlencode($item->backup_filename1) : '' ?> target='downloadframe'><?php echo $this->translate('download') ?>
                    </a>
          <?php endif; ?>
        <?php endif; ?>

      <?php
      echo $this->htmlLink(
        array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-codebackup', 'action' => 'delete', 'id' => $item->backup_id), $this->translate("delete"), array('class' => 'smoothbox'))
      ?>
              </td>
            </tr>
    <?php endforeach; ?>
        </tbody>
      </table><br /> 

    </form>
    <br/>
    <br/>
    <div>
    <?php echo $this->paginationControl($this->paginator); ?>
    </div>
  <?php else: ?>
    <div class="tip" style="margin:10px 0 0;">
      <span>
    <?php echo $this->translate("No files backups could be found.") ?>
      </span>
    </div>
  <?php endif;
}
?>