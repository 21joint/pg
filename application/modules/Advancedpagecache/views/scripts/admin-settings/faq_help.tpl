<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Advancedpagecache
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq_help.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
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
          <a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo $this->translate("What are the main settings required for Multiple Users and Single User Caching to work?");?></a>
          <div class='faq' style='display: none;' id='faq_1'>
            <?php echo $this->translate('Your site should be in “Production Mode” and cache should be enabled from ‘Performance and Caching’ in the Settings tab for Multiple Users and Single User Caching to work.');?>
          </div>
        </li>
        <li>
          <a href="javascript:void(0);" onClick="faq_show('faq_91');"><?php echo $this->translate("What is Single User Caching?");?></a>
          <div class='faq' style='display: none;' id='faq_91'>
            <?php echo $this->translate('This caching will store html file of all the pages when they load for the first time. When any url is called for the second time by the same user the cache response page will be served instead of processing the heavy php scripts again and again. Hence page load speed will increase dynamically.');?>
          </div>
        </li>
        <li>
          <a href="javascript:void(0);" onClick="faq_show('faq_92');"><?php echo $this->translate("What is Multiple Users Caching?");?></a>
          <div class='faq' style='display: none;' id='faq_92'>
            <?php echo $this->translate('Like the name suggests this caching will store the pages as a common file for multiple users. You can choose any configuration on the basis of which page will be cached, when loaded for the first time. The next time same url will be called even for a different user, cache response page will render based on the configuration set instead of loading the page all over again. Hence page load speed will be much improved for the second user while loading the page for the first time.');?>
          </div>
        </li>
        <li>
          <a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo $this->translate("What should be the time duration for Multiple Users and Single User caching?");?></a>
          <div class='faq' style='display: none;' id='faq_2'>
            <?php echo $this->translate('It could be anything as per your choice but it will be preferable if you will keep duration for multiple users cache more than single User cache.');?>
          </div>
        </li>
        <li>
          <a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo $this->translate("Is the ‘Caching Feature’ and ‘Flush Cache’ functionality in the global settings and 'Performance & Caching' are same?");?></a>
          <div class='faq' style='display: none;' id='faq_3'>
            <?php echo $this->translate('No, the caching feature in the performance & caching is for the caching done by SocialEngine core whereas the caching feature in the global settings is for Single User caching done by this plugin and Flush Cache in the Performance & Caching will clear the cache of SocialEngine core only whereas the cache in the global settings of this plugin will clear both core and Single User cache.');?>
          </div>
        </li>
        <li>
          <a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php echo $this->translate("What if my site don’t have that much storage space as required by this plugin?");?></a>
          <div class='faq' style='display: none;' id='faq_4'>
            <?php echo $this->translate('You can set minimum available disk space for Single User caching to work. Not only this you can enter the time duration after which a check will be done for the available disk space and if the minimum space entered will fall short of the available space Single User caching will stop working. This setting will work in case of file based caching only as it is handled automatically in memory based caching systems.');?>
          </div>
        </li>
        <li>
          <a href="javascript:void(0);" onClick="faq_show('faq_5');"><?php echo $this->translate("What are excluding URLs?");?></a>
          <div class='faq' style='display: none;' id='faq_5'>
            <?php echo $this->translate('In excluding URLs, you can add URL of those pages which you think changes frequently, hence do not required caching. This will save space utilized for storage of such pages.');?>
          </div>
        </li>
        <li>
          <a href="javascript:void(0);" onClick="faq_show('faq_6');"><?php echo $this->translate("What will be the benefit of Multiple Users caching?");?></a>
          <div class='faq' style='display: none;' id='faq_6'>
            <?php echo $this->translate('Multiple Users caching will enhance the performance of your website by doing caching which will be beneficial for multiple users. You can cache some of the pages on the basis of various settings when loaded for the first time, hence increasing first time page load speed for the other users.');?>
          </div>
        </li>
        <li>
          <a href="javascript:void(0);" onClick="faq_show('faq_7');"><?php echo $this->translate("What is the disk space available tip in the “Global Settings” indicating?");?></a>
          <div class='faq' style='display: none;' id='faq_7'>
            <?php echo $this->translate('That tip is indicating available amount of disc space so that you can clear the cache manually if required.');?>
          </div>
        </li> 
        <li>
          <a href="javascript:void(0);" onClick="faq_show('faq_8');"><?php echo $this->translate("Does Multiple users caching will also cache full page as in the case of Single User caching?");?></a>
          <div class='faq' style='display: none;' id='faq_8'>
            <?php echo $this->translate('No, in case of Multiple users caching the main content of the page will be cached excluding the footer and the header that too on the basis of configuration chosen.');?>
          </div>
        </li>                      
          </ul>
        </div>
        