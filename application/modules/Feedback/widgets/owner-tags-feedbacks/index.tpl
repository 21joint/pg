<?php 
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Document
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2010-08-11 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<div class="seaocore_gutter_blocks generic_layout_container">
  <ul class="seaocore_sidebar_list">
    <li>
      <?php foreach($this->tag_id_array as $key => $frequency):?>
        <a href='javascript:void(0);' onclick='javascript:tagAction(<?php echo $this->tag_id_array[$key]; ?>);'>
          #<?php echo $key ?>
        </a>
      <?php endforeach; ?>
    </li>
  </ul>
</div>  
