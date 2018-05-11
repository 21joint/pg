<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$showBalance = array(
    'Radio',
    'showBalance',
    array(
        'label' => 'Do you want to show current credit balance?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => 1,
    )
);
$showRank = array(
    'Radio',
    'showRank',
    array(
        'label' => 'Do you want to show member’s current rank?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
            
        ),
        'value' => 1,
    )
);
$showNextRank = array(
    'Radio',
    'showNextRank',
    array(
        'label' => 'Do you want to show member’s next rank?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
            
        ),
        'value' => 1,
    )
);
$showlimit = array(
    'Radio',
    'showlimit',
    array(
        'label' => 'Do you want to show how many more credits a member can earn in a day?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
            
        ),
        'value' => 1,
    )
);
$showViewMoreContent = array(
    'Select',
    'show_content',
    array(
        'label' => 'What do you want for view more content?',
        'description' => '',
        'multiOptions' => array(
            '1' => 'Pagination',
            '2' => 'Show View More Link at Bottom',
            '3' => 'Auto Load Activities on Scrolling Down'),
        'value' => 2,
    )
);
$showViewMoreContentOptions = array(
    'Select',
    'show_content_credit',
    array(
        'label' => 'What do you want for view more content?',
        'description' => '',
        'multiOptions' => array(
            '1' => 'Pagination',
            '2' => 'Show View More Link at Bottom',
            '3' => 'Auto Load Transaction Details on Scrolling Down'),
        'value' => 2,
    )
);
$truncationActivityElement = array(
    'Text',
    'truncationActivity',
    array(
        'label' => 'Truncation limit of activity type',
        'value' => 35,
    )
);
$showOptions = array(
    'Radio',
    'topmember',
    array(
        'label' => 'Popularity criteria on the basis of which members will be displayed.',
        'multiOptions' => array(
            'activities' => 'Maximum Performed Activities',
            'earned' => 'Maximum Earned Credits',
            'current' => 'Maximum Current Credits',
        ),
        'value' => 'activities',
    )
);
$count=array(
        'text',
    'count',
    array(
        'label' => 'Number of top active members to be shown (Note: Maximum 10 will be shown).',
         'value' => '',
    ));
$countbadge=array(
        'text',
    'countbadge',
    array(
        'label' => 'Number of Badges to show (Maximum 5).',
         'value' => '',
    ));
$countactivity=array(
        'text',
    'countactivity',
    array(
        'label' => 'Number of recent activities to be shown (Note: Maximum 10 will be shown).',
         'value' => '',
    ));
$targetType = array(
    'Select',
    'targetType',
    array(
        'label' => 'Target Type',
        'multiOptions' => array(
            'badge' => 'Badge',
            'link' => 'Referral Sign ups',
        ),
        'value' => 'badge',
            )
);

$showlevel = array(
    'Radio',
    'showlevel',
    array(
        'label' => 'Member levels you want to display?',
        'multiOptions' => array(
            1 => 'Immediate Next',
            0 => 'All',
            
        ),
        'value' => 1,
    )
);
$sendCredits = array(
    'Radio',
    'sendCredits',
    array(
        'label' => 'Members can send credits to',
        'multiOptions' => array(
            1 => 'Friends only',
            0 => 'All members',
        ),
        'value' => 1,
    )
);

return array(
  array(
    'title' => 'Send Credits to Friends',
    'description' => 'Member can send credits to other on-site members along with a note. If this widget is placed on ‘Member Profile’ page then that member’s name will be pre-filled. And on other pages, autosuggest textbox will be present.',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.send-to-friend',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
    'adminForm' => array(
        'elements' => array(
            $sendCredits
        ),
    ),
    'requirements' => array(
      'subject' => 'user',
    ),
  ),

    array(
    'title' => 'Top Active Members',
    'description' => 'Displays a list of top active members on your site based on: maximum activities performed, total earned credits or current credit balance.',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.top-member',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                $showOptions,$count
            ),
        ),
    'requirements' => array(
      'subject' => 'user',
    ),
  ),
       
        array(
    'title' => 'Buy Credits',
    'description' => 'Members can buy credits from here.',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.buy-credits',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
    'requirements' => array(
      'subject' => 'user',
    ),
  ),
      
        array(
    'title' => 'Upgrade Member Level',
    'description' => 'List of all the next member levels will be visible to a member. He can request for member level upgradation as per his current credits.',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.upgrade-level',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),'adminForm' => array(
            'elements' => array(
             $showlevel
            ),
        ),
    'requirements' => array(
      'subject' => 'user',
    ),
  ),    
         
          array(
    'title' => 'Member’s Badges',
    'description' => 'Display a list of badges achieved by a member by performing various activities on your site.',
   'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.badges',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
            'adminForm' => array(
            'elements' => array(
             $countbadge
            ),
        ),
    'requirements' => array(
      'subject' => 'user',
    ),
  ),    

    array(
    'title' => 'Recent Activities',
    'description' => 'Recent activities performed by a member to earn credits will be shown here.',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.recent-activities',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
            'adminForm' => array(
            'elements' => array(
             $countactivity,
            ),
        ),
    'requirements' => array(
      'subject' => 'user',
    ),
  ),        
 
     array(
    'title' => 'User’s Credit Information',
    'description' => 'Displays various credit information of a member on your site, including: Current Credit Balance, Current and Next Rank, Credit Usage Limit (on per day basis).',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.user-credit-information',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
            'adminForm' => array(
            'elements' => array(
             $showBalance,$showRank,$showNextRank,$showlimit,
            ),
        ),
    'requirements' => array(
      'subject' => 'user',
    ),
  ),

        array(
    'title' => 'How to Earn Credits',
    'description' => 'Displays steps for earning credits on your site. You can edit content displaying by this widget from the ‘Global Settings’ → ‘Instructions’ section of the admin panel of this plugin.',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.earn-credits',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),            
    'requirements' => array(
      'subject' => 'user',
    ),
  ),  
                array(
    'title' => 'Next Target',
    'description' => 'Displays next target ‘Badge’ and ‘Referral Sign-ups’ to earn credits.',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.next-target',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),        
    'adminForm' => array(
            'elements' => array(
             $targetType,
            ),
        ),    
    'requirements' => array(
      'subject' => 'user',
    ),
  ),

    array(
    'title' => 'Credits Navigation Tabs',
    'description' => 'Displays the Navigation tabs with links of My Credits, Transactions and Earn Credits.This widget should be placed at the top of any widgetized page.',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.main-navigation-menu',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),            
    'requirements' => array(
      'subject' => 'user',
    ),
  ), 
    array(
    'title' => 'Terms & Conditions',
    'description' => 'Displays ‘Terms and Conditions’ a member must keep in mind to earn credits on your site. You can edit content displaying by this widget from the ‘Global Settings’ → ‘Instructions’ section of the admin panel of this plugin.',
   'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.terms-and-conditions',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),            
    'requirements' => array(
      'subject' => 'user',
    ),
  ), 
    array(
    'title' => 'My Credits',
    'description' => 'Displays complete credit information of a member: total credits, credit validity, addition and deduction of credits with respect to the various activities performed.',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.my-credits',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),            
    'requirements' => array(
      'subject' => 'user',
    ),
  ),
    array(
    'title' => 'My Transactions',
    'description' => 'Displays complete transaction details of a member related to the activities performed and credit earned / deducted.',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.browse-transaction',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
    'adminForm' => array(
            'elements' => array(
             array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count
                                    (number of items to show)',
                    ),
                    'value' => 9,
                ),$showViewMoreContent,$truncationActivityElement,
            ),
        ),                
    'requirements' => array(
      'subject' => 'user',
    ),
  ),   
    array(
    'title' => 'Browse Activities Credit Value',
    'description' => 'Displays all the enabled activities of various modules along with their credit value.',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.show-activity-credit',
    'defaultParams' => array(
            'title' => '',
            'titleCount' => true,
        ),
    'adminForm' => array(
            'elements' => array(
            array(
                    'Text',
                    'itemCountPage',
                    array(
                        'label' => 'Count
                                    (number of items to show)',
                    ),
                    'value' => 9,
                ),$showViewMoreContentOptions , $truncationActivityElement,
            ),
        ),                
    'requirements' => array(
      'subject' => 'user',
    ),
  ),
  array(
    'title' => 'My Credits Link',
    'description' => 'Display a quick link to My Credits Page',
    'category' => 'Credits',
    'type' => 'widget',
    'name' => 'sitecredit.my-credit-link',
    'requirements' => array(
      'subject' => 'user',
    ),
  ), 
) ?>
