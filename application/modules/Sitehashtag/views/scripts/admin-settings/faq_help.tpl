<?php 

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    faq_help.tpl 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type = "text/javascript">
    function faq_show(id) {
        if ($(id).style.display == 'block') {
            $(id).style.display = 'none';
        } else {
            $(id).style.display = 'block';
        }
    }
</script>

<div class = "admin_seaocore_files_wrapper">
    <ul class = "admin_seaocore_files seaocore_faq">
        <?php $i = 0;?>
        
        <li>
            <a href = "javascript:void(0);" onClick = "faq_show('faq_<?php echo ++$i;?>');"><?php echo "Which words work as a hashtag?"; ?></a>
            <div class = 'faq' style = 'display: none;' id = 'faq_<?php echo $i++;?>'>
                <?php echo "Hashtag is written by using a single word, combining group of words / phrase, preceded by a hash (#) sign. You can also include numbers in a hashtag, but punctuation and special characters won't work. Like “#FeelingWonderfulWithFriends” is a hashtag."; ?>
            </div>
        </li>         
        
        <li>
            <a href = "javascript:void(0);" onClick = "faq_show('faq_<?php echo ++$i;?>');"><?php echo "How hashtag work for a content module?"; ?></a>
            <div class = 'faq' style = 'display: none;' id = 'faq_<?php echo $i++;?>'>
                <?php echo "While creating a content, user can hashtag some words / whole title of the content. These hashtags will get display in the Activity Feed (related to this content) at the bottom."; ?> <a href="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sitehashtag/externals/images/3_4.png" title="Hashtag While Content Creation" class="seaocore_icon_view" target="_blank"></a>
            </div>
        </li>            
        
           
        
        <li>
            <a href = "javascript:void(0);" onClick = "faq_show('faq_<?php echo ++$i;?>');"><?php echo " I am not able to search my recently created hashtags for one of my Content Module, what might be the problem?"; ?></a>
            <div class = 'faq' style = 'display: none;' id = 'faq_<?php echo $i++;?>'>
                <?php echo "  It might be possible that you have not enabled that content module from the manage module section of this plugin. [Note: Hashtags will work for those contents which are created after enabling the respective content module.]"; ?>
            </div>
        </li>         
        
          <li >
            <a href = "javascript:void(0);" onClick = "faq_show('faq_<?php echo ++$i;?>');"><?php echo "The CSS of this plugin is not coming on my site. What should I do?"; ?></a>
            <div class = 'faq' style = 'display: none;' id = 'faq_<?php echo $i++;?>'>
                <?php echo "Please enable the 'Development Mode' system mode for your site from the Admin homepage and then check the page which was not coming fine. It should now seem fine. Now you can again change the system mode to 'Production Mode'."; ?>
            </div>
        </li>     
        
    </ul>
</div>