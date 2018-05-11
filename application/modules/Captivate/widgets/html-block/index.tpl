<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Captivate
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>


<?php

$baseURL = $this->baseUrl();
if ($this->captivateLendingBlockValue):
    echo '<div id="show_help_content">' . $this->captivateLendingBlockValue . '</div>';
else:
    ?>
    <?php

    $captivateLendingBlockValue = '<div style="display: inline-block;"><div style="float: left; margin: 10px 0; opacity: 1; text-align: center; width: 33.3%;">
  <a href="' . $baseURL . '/events">
      <div style="background-color: #EBEBEB; background-position: center 50%; background-repeat: no-repeat; border-radius: 50% 50% 50% 50%; height: 175px; margin: 0 auto; width: 175px; background-image: url(' . $baseURL . '/application/themes/captivate/images/discover-events.png); display:block;"></div></a>
        <a href="' . $baseURL . '/events">
          <span style="color: #282828; float: left; font-size: 22px; margin-top: 20px; text-align: center; width: 100%;">Discover Events</span>
          <span style="color: #707070; float: left; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Find out the best parties and events happening around you.</span>
        </a>
    </div>
    <div style="float: left; margin: 10px 0; opacity: 1; text-align: center; width: 33.3%;">
    <a href="' . $baseURL . '/groups"><div style="background-color: #EBEBEB; background-position: center 50%; background-repeat: no-repeat; border-radius: 50% 50% 50% 50%; height: 175px; margin: 0 auto; width: 175px; background-image: url(' . $baseURL . '/application/themes/captivate/images/engage-icon.png); display:block;"></div> </a>
        <a href="' . $baseURL . '/groups">
          <span style="color: #282828; float: left; font-size: 22px; margin-top: 20px; text-align: center; width: 100%;">Engage</span>
          <span style="color: #707070; float: left; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Join our interest based groups and share stuff.</span>
        </a>
   </div>
   <div style="float: left; margin: 10px 0; opacity: 1; text-align: center; width: 33.3%;">
     <a href="' . $baseURL . '/members"><div style="background-color: #EBEBEB; background-position: center 50%; background-repeat: no-repeat; border-radius: 50% 50% 50% 50%; height: 175px; margin: 0 auto; width: 175px; background-image: url(' . $baseURL . '/application/themes/captivate/images/meetpeople.png); display:block;"></div></a>
    <a href="' . $baseURL . '/members">
      <span style="color: #282828; float: left; font-size: 22px; margin-top: 20px; text-align: center; width: 100%;">Meet New People</span>
      <span style="color: #707070; float: left; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Make new friends with common interests, Get your own party buddies.</span>
    </a>
  </div></div>';

    echo $captivateLendingBlockValue;
endif;
?>