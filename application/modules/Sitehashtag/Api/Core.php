<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Core.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitehashtag_Api_Core extends Core_Api_Abstract {

  protected $hashTagReguExp = "/\s(#[^\s[!\"\#$%&'()*+,\-.\/\\:;<=>?@\[\]\^`{|}~]+)/";

  public function getHashTags($string) {
    preg_match_all($this->hashTagReguExp, ' ' . $string, $hashtags);

    if (!empty($hashtags[0])) {
      foreach ($hashtags[0] as $key => $hashtag) {
        $hashtags[0][$key] = trim($hashtag);
      }
    }
    return $hashtags;
  }

}
