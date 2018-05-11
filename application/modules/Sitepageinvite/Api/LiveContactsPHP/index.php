<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageinvite
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
//  Example of how to use the library  -- contents put in $ret_array
include "contacts_fn.php";
$ret_array = get_people_array();

//to see a array dump...
print_r($ret_array);
?>   
