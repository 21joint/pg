<?php 

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    index.tpl 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<ul>
    <?php
    $url = $this->baseUrl()."/hashtag?search=";
    for ($i = 0; $i < count($this->hashtags); $i++) {?>
        <li>
            <a href='<?php echo $url.urlencode($this->hashtags[$i]); ?>'><?php echo $this->hashtags[$i]; ?></a>
        </li>  
    <?php } ?>
</ul>