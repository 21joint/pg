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
            href = en4.core.baseUrl + 'sitebackup/admin-manage/deleteselected/ids/' + inputs[i].value;
          }
        }
      }
    }
    $('selected').href = href;
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
          href = en4.core.baseUrl + 'sitebackup/admin-manage/deleteselected/ids/' + inputs[i].value;
        }
      }
    }
    $('selected').href = href;
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
  // Render the menu
  //->setUlClass()
  echo $this->navigation()->menu()->setContainer($this->navigation)->render()
  ?>
</div>

<h3><?php echo $this->translate("Database Backup List"); ?></h3>
<?php echo $this->translate("This page lists all the successful database backups taken for this site. You can use this page to download / restore / delete the backup files. [Note: Before restoring your database from a backup file, please test it on a non-production server.]") ?><br />
<?php if( !empty($this->backup_id) ) : ?>

  <div class="tip" style="margin:10px 0 0;">
    <span>

      <?php echo $this->translate('The last database backup was taken ' . $this->latesttime) ?> <?php echo $this->translate("ago.") ?>
    </span>
  </div>
<?php else: ?>

<?php endif; ?>

<?php
$link = $this->htmlLink(
  array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'confirm-delete-database-backup'), $this->translate('Delete All Database Backups'), array('class' => 'smoothbox buttonlink sitebackup_icon_delete'));
?>
<?php
$viewlog = $this->htmlLink(
  array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'deleteselected', 'ids' => ''), $this->translate('Delete Selected Database Backups'), array('class' => 'smoothbox buttonlink sitebackup_icon_delete', 'id' => 'selected'));
?> 

  <?php if( !empty($this->is_sitebackup) ) { ?>
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
              <th class='admin_table_short'>
                <?php
                echo $this->htmlLink(
                  array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'index', 'id' => 'sitebackup', 'order' => 'DESC'), $this->translate('Id'))
                ?>
              </th>
              <?php else: ?>
              <th class='admin_table_short'>
              <?php
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'index', 'id' => 'sitebackup', 'order' => 'ASC'), $this->translate('Id'))
              ?>
              </th>
              <?php endif; ?>
            <th style="text-align:left;"><?php echo $this->translate("Filename") ?></th>
            <?php if( $this->order == 'ASC' ): ?>
              <th style="text-align:left;"><?php
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'index', 'id' => 'time', 'order' => 'DESC'), $this->translate('Date'))
              ?>
              </th>
            <?php else: ?>
              <th style="text-align:left;"><?php
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'index', 'id' => 'time', 'order' => 'ASC'), $this->translate('Date'))
              ?>
              </th>
            <?php endif; ?>

              <?php if( $this->order == 'ASC' ): ?>
              <th style="text-align:left;"><?php
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'index', 'id' => 'method', 'order' => 'DESC'), $this->translate('Method'))
              ?></th>
              <?php else: ?>
              <th style="text-align:left;"><?php
                echo $this->htmlLink(
                  array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'index', 'id' => 'method', 'order' => 'ASC'), $this->translate('Method'))
                ?></th>
              <?php endif; ?>

            <?php if( $this->order == 'ASC' ): ?>
              <th style="text-align:left;"><?php
        echo $this->htmlLink(
          array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'index', 'id' => 'destinationname', 'order' => 'DESC'), $this->translate('Destination'))
        ?></th>
              <?php else: ?>
              <th style="text-align:left;"><?php
              echo $this->htmlLink(
                array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'index', 'id' => 'destinationname', 'order' => 'ASC'), $this->translate('Destination'))
              ?></th>
              <?php endif; ?>	

            <th style="text-align:left;"><?php echo $this->translate("Type") ?></th>
            <?php if( $this->order == 'ASC' ): ?>
              <th style="text-align:left;"><?php
                echo $this->htmlLink(
                  array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'index', 'id' => 'status', 'order' => 'DESC'), $this->translate('Status'))
                ?></th>
              <?php else: ?>
              <th style="text-align:left;"><?php
                echo $this->htmlLink(
                  array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'index', 'id' => 'status', 'order' => 'ASC'), $this->translate('Status'))
                ?></th>
    <?php endif; ?>			

    <?php if( $this->order == 'ASC' ): ?>
              <th style="text-align:left;"><?php
            echo $this->htmlLink(
              array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'index', 'id' => 'filesize', 'order' => 'DESC'), $this->translate('Filesize'))
            ?></th>
          <?php else: ?>
              <th style="text-align:left;"><?php
            echo $this->htmlLink(
              array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'index', 'id' => 'filesize', 'order' => 'ASC'), $this->translate('Filesize'))
            ?></th>
    <?php endif; ?>
            <th style="text-align:left;"><?php echo $this->translate("Options") ?></th>

          </tr>
        </thead>
        <tbody>
    <?php $base_url = Zend_Controller_Front::getInstance()->getBaseUrl(); ?>
            <?php foreach( $this->paginator as $item ): ?>

              <?php
              $backup_filename = strip_tags($item->backup_filename);
              $backup_filename1 = Engine_String::strlen($backup_filename) > 12 ? Engine_String::substr($backup_filename, 0, 12) . '..' : $backup_filename;
              ?>

            <tr> 
              <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->backup_id ?>' value="<?php echo $item->backup_id ?>" onclick = "clickable();"/></td>
              <td><?php echo $item->backup_id ?></td>

      <?php if( file_exists(APPLICATION_PATH . '/public/' . $this->dir_name_temp . '/' . $item->backup_filename) ): ?>
                <td title="Download: <?php echo $item->backup_filename ?>">
                  <a href=<?php echo $this->url(array('action' => 'download')) ?><?php echo!empty($item->backup_filename) ? '?path=' . urlencode($item->backup_filename) : '' ?> target='downloadframe'><?php echo $this->translate($backup_filename1) ?>
                  </a>
                </td>
                <?php else: ?>
                  <?php if( $item->backup_method == 'Database' ): ?>
                  <td title="<?php echo $item->destination_name ?>"><?php echo $item->destination_name ?></td>
                  <?php else: ?>
                  <td title="<?php echo $item->backup_filename ?>"><?php echo $backup_filename1 ?></td>
                  <?php endif; ?>
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
                  <?php elseif( $item->backup_method == 'Email' ): ?>
                    <?php echo $this->htmlImage('application/modules/Sitebackup/externals/images/email.png', '') ?>
                  <?php elseif( $item->backup_method == 'Database' ): ?>
                    <?php echo $this->htmlImage('application/modules/Sitebackup/externals/images/database.png', '') ?>
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
              <td style="white-space:normal;" title="<?php echo $item->destination_name ?>"> 
                <div style="min-width:10%;overflow:hidden;">	
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
                <?php
                if( !empty($item->backup_status) ) {
                  echo $this->translate('Success');
                }
                ?>
              </td>
              <td><?php
                if( $item->backup_method == 'Database' ): echo 'N.A.';
                else: echo $item->backup_filesize;
                endif;
                ?></td>
              <td>
        <?php if( file_exists(APPLICATION_PATH . '/public/' . $this->dir_name_temp . '/' . $item->backup_filename) ): ?>
                  <a href=<?php echo $this->url(array('action' => 'download')) ?><?php echo!empty($item->backup_filename) ? '?path=' . urlencode($item->backup_filename) : '' ?> target='downloadframe'><?php echo $this->translate('download') ?></a>

      <?php endif; ?>

      <?php if( file_exists(APPLICATION_PATH . '/public/' . $this->dir_name_temp . '/' . $item->backup_filename) ): echo $this->htmlLink(array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'restore', 'filename' => $item->backup_filename, 'filesize' => $item->backup_filesize, 'flage' => 1), $this->translate("restore"));
      endif;
      ?>

      <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitebackup', 'controller' => 'admin-manage', 'action' => 'delete', 'id' => $item->backup_id), $this->translate("delete"), array('class' => 'smoothbox')) ?>
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
    <?php echo $this->translate("No database backups could be found.") ?>
      </span>
    </div>
  <?php
  endif;
}?>