<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteluminous
 * @copyright  Copyright 2013-2014 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2014-10-09 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Siteluminous_Api_Core extends Core_Api_Abstract {

  /**
   * This function return the complete path of image, from the photo id.
   *
   * @param $id: The photo id.
   * @param $type: The type of photo required.
   * @return Image path.
   */
  public function displayPhoto($id, $type = 'thumb.profile') {
    if (empty($id)) {
      return null;
    }
    $file = Engine_Api::_()->getItemTable('storage_file')->getFile($id, $type);
    if (!$file) {
      return null;
    }

    // Get url of the image
    $src = $file->map();
    return $src;
  }
  
  /**
   * Plugin which return the error, if Siteadmin not using correct version for the plugin.
   *
   */
  public function isModulesSupport() {
    $isSiteluminousActivate = Engine_Api::_()->getApi('settings', 'core')->getSetting('siteluminous.isActivate', 0);
    if(empty($isSiteluminousActivate))
      return array();
    
    $modArray = array(
        'sitegroup' => '4.8.6p3',
        'siteevent' => '4.8.6p5',
        'sitemenu' => '4.8.6p4'
    );
    $finalModules = array();
    foreach ($modArray as $key => $value) {
      $isModEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled($key);
      if (!empty($isModEnabled)) {
        $getModVersion = Engine_Api::_()->getDbtable('modules', 'core')->getModule($key);
        $isModSupport = $this->checkVersion($getModVersion->version, $value);
        if (empty($isModSupport)) {
          $finalModules[] = $getModVersion->title;
        }
      }
    }
    return $finalModules;
  }
      private function checkVersion($databaseVersion, $checkDependancyVersion) {
        $f = $databaseVersion;
        $s = $checkDependancyVersion;
        if (strcasecmp($f, $s) == 0)
            return -1;

        $fArr = explode(".", $f);
        $sArr = explode('.', $s);
        if (count($fArr) <= count($sArr))
            $count = count($fArr);
        else
            $count = count($sArr);

        for ($i = 0; $i < $count; $i++) {
            $fValue = $fArr[$i];
            $sValue = $sArr[$i];
            if (is_numeric($fValue) && is_numeric($sValue)) {
                if ($fValue > $sValue)
                    return 1;
                elseif ($fValue < $sValue)
                    return 0;
                else {
                    if (($i + 1) == $count) {
                        return -1;
                    } else
                        continue;
                }
            }
            elseif (is_string($fValue) && is_numeric($sValue)) {
                $fsArr = explode("p", $fValue);

                if ($fsArr[0] > $sValue)
                    return 1;
                elseif ($fsArr[0] < $sValue)
                    return 0;
                else {
                    return 1;
                }
            } elseif (is_numeric($fValue) && is_string($sValue)) {
                $ssArr = explode("p", $sValue);

                if ($fValue > $ssArr[0])
                    return 1;
                elseif ($fValue < $ssArr[0])
                    return 0;
                else {
                    return 0;
                }
            } elseif (is_string($fValue) && is_string($sValue)) {
                $fsArr = explode("p", $fValue);
                $ssArr = explode("p", $sValue);
                if ($fsArr[0] > $ssArr[0])
                    return 1;
                elseif ($fsArr[0] < $ssArr[0])
                    return 0;
                else {
                    if ($fsArr[1] > $ssArr[1])
                        return 1;
                    elseif ($fsArr[1] < $ssArr[1])
                        return 0;
                    else {
                        return -1;
                    }
                }
            }
        }
    }
  /**
   * Get language array
   *
   * @param string $page_url
   * @return array $localeMultiOptions
   */
  public function getLanguageArray() {

    //PREPARE LANGUAGE LIST
    $languageList = Zend_Registry::get('Zend_Translate')->getList();

    //PREPARE DEFAULT LANGUAGE
    $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en');
    if (!in_array($defaultLanguage, $languageList)) {
      if ($defaultLanguage == 'auto' && isset($languageList['en'])) {
        $defaultLanguage = 'en';
      } else {
        $defaultLanguage = null;
      }
    }
    //INIT DEFAULT LOCAL
    $localeObject = Zend_Registry::get('Locale');
    $languages = Zend_Locale::getTranslationList('language', $localeObject);
    $territories = Zend_Locale::getTranslationList('territory', $localeObject);

    $localeMultiOptions = array();
    foreach ($languageList as $key) {
      $languageName = null;
      if (!empty($languages[$key])) {
        $languageName = $languages[$key];
      } else {
        $tmpLocale = new Zend_Locale($key);
        $region = $tmpLocale->getRegion();
        $language = $tmpLocale->getLanguage();
        if (!empty($languages[$language]) && !empty($territories[$region])) {
          $languageName = $languages[$language] . ' (' . $territories[$region] . ')';
        }
      }

      if ($languageName) {
        $localeMultiOptions[$key] = $languageName;
      } else {
        $localeMultiOptions[$key] = Zend_Registry::get('Zend_Translate')->_('Unknown');
      }
    }
    $localeMultiOptions = array_merge(array(
        $defaultLanguage => $defaultLanguage
            ), $localeMultiOptions);
    return $localeMultiOptions;
  }
  
}
