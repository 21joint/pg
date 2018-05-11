<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Sitebackup
 * @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: _formCheckbox.tpl 2010-10-25 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
?>
<style type="text/css">
  .form-options-wrapper li{
    padding:0px;
  }
</style> 
<div id="hide" class="form-wrapper" style="border-top:none;padding:0px;margin-top:-15px;">

  <div class="form-label" >

    <label>
      &nbsp;
    </label>
  </div>
  <div class="form-element">
    <p class="description">

    </p>
    <ul class="form-options-wrapper">
      <li><input type='checkbox' value=1 id='tables' name='Uncheck_all'  onclick="doCheckAll();" /> <?php echo 'Uncheck all' ?></li>
      <li>
        <?php
        $filename = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR . 'tables_name.txt';
        $file = fopen($filename, "r");
        $content = fread($file, filesize($filename));
        $tablesinfo = Zend_Json_Decoder::decode($content);

        foreach( $tablesinfo as $values ) {
          foreach( $values as $result_table ) {
            if( $result_table != 'engine4_sitebackup_backups' && $result_table != 'engine4_sitebackup_backuplogs' ) {
              ?>	
              <div style="clear:both;">
                <input type='checkbox' value=1 onclick='checkbox();' id='tables_all' name=<?php echo $result_table ?> checked='checked' /> <?php echo $result_table ?>
              </div>
              <?php
            }
          }
        }
        fclose($file);
        ?>
      </li>
    </ul>	
  </div>
</div>
<script type="text/javascript">
  var checkboxcount = 0;


  function doCheckAll() {

    if (checkboxcount == 0) {
      $$('.global_form').each(function (elements) {
        for (var i = 0; i < elements.length; i++) {
          if (elements[i].type == 'checkbox' && elements[i].id != 'backup_includedirectory' && elements[i].id != 'backup_optionsettings' && elements[i].id != 'files_all' && elements[i].id != 'files' && elements[i].id != 'fileroots_all' && elements[i].id != 'rootfiles') {

            elements[i].checked = false;

          }
        }
        checkboxcount = checkboxcount + 1;
      }
      );
    } else {
      $$('.global_form').each(function (elements) {
        for (var i = 0; i < elements.length; i++) {
//     if (elements[i].type == 'checkbox' && elements[i].id != 'backup_includedirectory' && elements[i].id != 'backup_optionsettings' && elements[i].id != 'tables' && elements[i].id != 'backup_files-wrapper') {
          if (elements[i].type == 'checkbox' && elements[i].id != 'backup_includedirectory' && elements[i].id != 'backup_optionsettings' && elements[i].id != 'files_all' && elements[i].id != 'files' && elements[i].id != 'fileroots_all' && elements[i].id != 'rootfiles') {
            elements[i].checked = true;
          }
        }
        checkboxcount = checkboxcount - 1;
      }
      );
    }
  }

  function checkbox() {
    if ($('tables').type == 'checkbox') {
      $('tables').checked = false;
    }
  }
</script>