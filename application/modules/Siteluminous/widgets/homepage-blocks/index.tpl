 <?php 
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 
  if(!empty($this->siteluminousLendingBlockValue)): 
    echo $this->siteluminousLendingBlockValue;
  else:
    ?>
  <div>
    <div style="transition: opacity 0.8s ease, top 800ms ease;float: left; margin: 10px 0; opacity: 1; padding: 56px 0; text-align: center; width: 33.3%;">
     <div style="background-color: #EBEBEB; background-position: center 50%; background-repeat: no-repeat; border-radius: 50% 50% 50% 50%; height: 175px; margin: 0 auto; width: 175px; background-image: url(application/themes/luminous/images/discover-events.png); display:block;"></div>
     <a href="/events">
        <span style="color: #282828; float: left; font-family: Ubuntu, sans-serif; font-size: 22px; margin-top: 20px; text-align: center; width: 100%;">Discover Events</span>
        <span style="color: #707070; float: left; font-family: Open Sans, sans-serif; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Find out the best parties and events happening around you.</span>
      </a>
    </div>
   
    <div style="transition: opacity 0.8s ease, top 800ms ease;float: left; margin: 10px 0; opacity: 1; padding: 56px 0; text-align: center; width: 33.3%;">
      <div style="background-color: #EBEBEB; background-position: center 50%; background-repeat: no-repeat; border-radius: 50% 50% 50% 50%; height: 175px; margin: 0 auto; width: 175px; background-image: url(application/themes/luminous/images/engage-icon.png); display:block;"></div>
      <a href="/groups">
        <span style="color: #282828; float: left; font-family: Ubuntu, sans-serif; font-size: 22px; margin-top: 20px; text-align: center; width: 100%;">Engage</span>
        <span style="color: #707070; float: left; font-family: Open Sans, sans-serif; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Join our interest based groups and share stuff.</span>
      </a>
    </div>
	
    <div style="transition: opacity 0.8s ease, top 800ms ease;float: left; margin: 10px 0; opacity: 1; padding: 56px 0; text-align: center; width: 33.3%;">
     <div style="background-color: #EBEBEB; background-position: center 50%; background-repeat: no-repeat; border-radius: 50% 50% 50% 50%; height: 175px; margin: 0 auto; width: 175px; background-image: url(application/themes/luminous/images/meetpeople.png); display:block;"></div>
      <a href="/members">
          <span style="color: #282828; float: left; font-family: Ubuntu, sans-serif; font-size: 22px; margin-top: 20px; text-align: center; width: 100%;">Meet New People</span>
          <span style="color: #707070; float: left; font-family: Open Sans, sans-serif; font-size: 15px; margin-top: 10px; padding: 0 11%; text-align: center; width: 71%;">Make new friends with common interests, Get your own party buddies.</span>
     </a>
   </div>
  </div>
  
    <?php
  endif; 
?>