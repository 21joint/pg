<?php
/**
* SocialEngine
*
* @category   Application_Extensions
* @package    Sitegifplayer
* @copyright  Copyright 2017-2018 BigStep Technologies Pvt. Ltd.
* @license    http://www.socialengineaddons.com/license/
* @version    $Id: faq_help.tpl 2017-05-15 00:00:00Z SocialEngineAddOns $
* @author     SocialEngineAddOns
*/
?>

<script type="text/javascript">
  function faq_show(id) {
    if($(id).style.display == 'block') {
      $(id).style.display = 'none';
    } else {
      $(id).style.display = 'block';
    }
  }
</script>

<div class="admin_seaocore_files_wrapper">
  <ul class="admin_seaocore_files seaocore_faq">	
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo $this->translate("GIF Player works for which file extensions?");?></a>
      <div class='faq' style='display: none;' id='faq_1'>
       <?php echo $this->translate('GIF Player works only for the files with "gif" extension. It doesn\'t work for any other extensions after this plugin installation.');?>
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo $this->translate("Why existing gif files are not working with this plugin?");?></a>
      <div class='faq' style='display: none;' id='faq_2'>
       <?php echo $this->translate('This plugin works only with those \'gif\' files which are uploaded after plugin installation. So, gif files added before this plugin\'s installation will not work in GIF Player.');?>
      </div>
    </li>
  </ul>
</div>
        
