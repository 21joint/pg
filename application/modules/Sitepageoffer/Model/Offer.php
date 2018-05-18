<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Offer.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitepageoffer_Model_Offer extends Core_Model_Item_Abstract {

  protected $_searchColumns = array('title', 'description', 'search', 'end_settings', 'end_time');
  protected $_parent_type = 'sitepage_page';
  protected $_owner_type = 'user';
  protected $_parent_is_owner = false;

  public function isSearchable() {

    $sitepage = Engine_Api::_()->getItem('sitepage_page', $this->page_id);

    $isPageSearchable = ( (!isset($sitepage->search) || $sitepage->search) && empty($sitepage->closed) && $sitepage->approved && empty($sitepage->declined) && !empty($sitepage->draft) && ($sitepage->expiration_date > date("Y-m-d H:i:s")));

    return ( $isPageSearchable && (!isset($this->search) || $this->search) && ($this->end_settings == 1 && $this->end_time >=  date("Y-m-d H:i:s") || $this->end_settings == 0));
  }

  public function getMediaType() {
    return 'offer';
  }

  /**
   * Gets an absolute URL to the page to view this item
   *
   * @return string
   */
  public function getHref($params = array()) {
    if (Zend_Controller_Front::getInstance()->getRequest()->getParam('offer_id')) {
      $offer_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('offer_id');
      $table = Engine_Api::_()->getDbtable('offers', 'sitepageoffer');
      $select = $table->select()
              ->where('offer_id = ?', $offer_id)
              ->limit(1);

      $row = $table->fetchRow($select);
      if ($row !== null) {
        $pageid = $row->page_id;
      }
    } else {
      $pageid = $this->page_id;
    }

    $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
    $tab_id = '';
    if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
      $tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.sitemobile-profile-sitepageoffers', $this->page_id, $layout);
    } else {
      $tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $this->page_id, $layout);
    }
    $params = array_merge(array(
        'route' => 'sitepageoffer_view',
        'reset' => true,
        'user_id' => $this->owner_id,
        'offer_id' => $this->offer_id,
        'slug' => $this->getOfferSlug($this->title),
        'tab' => $tab_id,
            ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
                    ->assemble($params, $route, $reset);
  }

  /**
   * Truncation of owner name
   *
   * @param string $owner_name
   * @return truncate owner name
   */
  public function truncateOwner($owner_name) {
    $tmpBody = strip_tags($owner_name);
    return ( Engine_String::strlen($tmpBody) > 10 ? Engine_String::substr($tmpBody, 0, 10) . '..' : $tmpBody );
  }

  /**
   * Create photo
   *
   * @param array $photo
   * @return photo object
   */
  public function setPhoto($photo) {
    if ($photo instanceof Zend_Form_Element_File) {
      $file = $photo->getFileName();
    } else if (is_array($photo) && !empty($photo['tmp_name'])) {
      $file = $photo['tmp_name'];
    } else if (is_string($photo) && file_exists($photo)) {
      $file = $photo;
    } else {
      $error_msg1 = Zend_Registry::get('Zend_Translate')->_('invalid argument passed to setPhoto');
      throw new Event_Model_Exception($error_msg1);
    }

    $name = basename($file);
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_id' => $this->getIdentity(),
        'parent_type' => 'sitepageoffer_offer'
    );

    $storage = Engine_Api::_()->storage();

    // Add autorotation for uploded images. It will work only for SocialEngine-4.8.9 Or more then.
    $usingLessVersion = Engine_Api::_()->seaocore()->usingLessVersion('core', '4.8.9');
    if(!empty($usingLessVersion)) {
      //RESIZE IMAGE (MAIN)
      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(720, 720)
              ->write($path . '/m_' . $name)
              ->destroy();

      //RESIZE IMAGE (PROFILE)
      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(200, 400)
              ->write($path . '/p_' . $name)
              ->destroy();

      //RESIZE IMAGE (NORMAL)
      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(140, 160)
              ->write($path . '/in_' . $name)
              ->destroy();
    }else {
      //RESIZE IMAGE (MAIN)
      $image = Engine_Image::factory();
      $image->open($file)
              ->autoRotate()
              ->resize(720, 720)
              ->write($path . '/m_' . $name)
              ->destroy();

      //RESIZE IMAGE (PROFILE)
      $image = Engine_Image::factory();
      $image->open($file)
              ->autoRotate()
              ->resize(200, 400)
              ->write($path . '/p_' . $name)
              ->destroy();

      //RESIZE IMAGE (NORMAL)
      $image = Engine_Image::factory();
      $image->open($file)
              ->autoRotate()
              ->resize(140, 160)
              ->write($path . '/in_' . $name)
              ->destroy();
    }

    $image = Engine_Image::factory();
    $image->open($file);

    $size = min($image->height, $image->width);
    $x = ($image->width - $size) / 2;
    $y = ($image->height - $size) / 2;

    $image->resample($x, $y, $size, $size, 48, 48)
            ->write($path . '/is_' . $name)
            ->destroy();

    //STORE
    $iMain = $storage->create($path . '/m_' . $name, $params);
    $iProfile = $storage->create($path . '/p_' . $name, $params);
    $iIconNormal = $storage->create($path . '/in_' . $name, $params);
    $iSquare = $storage->create($path . '/is_' . $name, $params);

    $iMain->bridge($iProfile, 'thumb.profile');
    $iMain->bridge($iIconNormal, 'thumb.normal');
    $iMain->bridge($iSquare, 'thumb.icon');

    //REMOVE TEMP FILES
    @unlink($path . '/p_' . $name);
    @unlink($path . '/m_' . $name);
    @unlink($path . '/in_' . $name);
    @unlink($path . '/is_' . $name);

    //UPDATE ROW
    $this->photo_id = $iMain->file_id;
    $this->save();

    //ADD TO ALBUM
    $viewer = Engine_Api::_()->user()->getViewer();
    $photoTable = Engine_Api::_()->getItemTable('sitepageoffer_photo');
    $sitepageofferAlbum = $this->getSingletonAlbum();
    $photoItem = $photoTable->createRow();
    $photoItem->setFromArray(array(
        'offer_id' => $this->getIdentity(),
        'album_id' => $sitepageofferAlbum->getIdentity(),
        'user_id' => $viewer->getIdentity(),
        'file_id' => $iMain->getIdentity(),
        'collection_id' => $sitepageofferAlbum->getIdentity(),
    ));
    $photoItem->save();

    return $this;
  }

  /**
   * Get album
   *
   * @return album object
   */
  public function getSingletonAlbum() {
    $table = Engine_Api::_()->getItemTable('sitepageoffer_album');
    $select = $table->select()
            ->where('offer_id = ?', $this->getIdentity())
            ->order('album_id ASC')
            ->limit(1);

    $album = $table->fetchRow($select);

    if (null === $album) {
      $album = $table->createRow();
      $album->setFromArray(array(
          'offer_id' => $this->getIdentity()
      ));
      $album->save();
    }

    return $album;
  }

  /**
   * Get photo
   *
   * @param int $photo_id
   * @return photo object
   */
  public function getPhoto($photo_id) {
    $photoTable = Engine_Api::_()->getItemTable('sitepageoffer_photo');
    $select = $photoTable->select()
            ->where('file_id = ?', $photo_id)
            ->limit(1);

    $photo = $photoTable->fetchRow($select);
    return $photo;
  }

  /**
   * Truncation of url
   *
   * @return truncate url
   */
  public function truncate40Url() {
    $tmpBody = strip_tags($this->url);
    return ( Engine_String::strlen($tmpBody) > 40 ? Engine_String::substr($tmpBody, 0, 40) . '..' : $tmpBody );
  }

  /**
   * Truncation of url
   *
   * @return truncate url
   */
  public function truncate20Url() {
    $tmpBody = strip_tags($this->url);
    return ( Engine_String::strlen($tmpBody) > 20 ? Engine_String::substr($tmpBody, 0, 20) . '..' : $tmpBody );
  }

  protected function _delete() {
    // Delete create activity feed of offer before delete offer 
    Engine_Api::_()->getApi('subCore', 'sitepage')->deleteCreateActivityOfExtensionsItem($this, array('sitepageoffer_new', 'sitepageoffer_admin_new', 'sitepageoffer_home'));
    parent::_delete();
  }

  /**
   * Gets a proxy object for the comment handler
   *
   * @return Engine_ProxyObject
   * */
  public function comments() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('comments', 'core'));
  }

  /**
   * Gets a proxy object for the like handler
   *
   * @return Engine_ProxyObject
   * */
  public function likes() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
  }

  /**
   * Make format for activity feed
   *
   * @return activity feed content
   */
  public function getRichContent() {

    $view = Zend_Registry::get('Zend_View');
    $view = clone $view;
    $view->clearVars();

    //GET LOGGED IN USER INFORMATION
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    if (!empty($viewer_id)) {
      // Convert times
      $oldTz = date_default_timezone_get();
      date_default_timezone_set($viewer->timezone);
    }

    $today = date("Y-m-d H:i:s");
    $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
    $offer_tab_id = '';
    if (!Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode')) {
      $offer_tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.sitemobile-profile-sitepageoffers', $this->page_id, $layout);
    } else {
      $offer_tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.profile-sitepageoffers', $this->page_id, $layout);
    }
    if (!empty($this->photo_id)) {
      $offer_photo = $view->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $this->owner_id, 'offer_id' => $this->offer_id, 'tab' => $offer_tab_id, 'slug' => $this->getOfferSlug($this->title)), $view->itemPhoto($this, 'thumb.icon'));
    } else {
      $offer_photo = $view->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $this->owner_id, 'offer_id' => $this->offer_id, 'tab' => $offer_tab_id, 'slug' => $this->getOfferSlug($this->title)), "<img src='" . $view->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/offer_thumb.png' alt='' />");
    }

    $content = '';

    $content .= '
      <div class="sitepage_offer_block" style="margin-bottom:10px;">
        <div class="sitepage_offer_photo">
				 ' . $offer_photo . '
        </div>
      	<div class="sitepage_offer_details">
        <div class="sitepage_offer_title">
          ' . $view->htmlLink(array('route' => 'sitepageoffer_view', 'user_id' => $this->owner_id, 'offer_id' => $this->offer_id, 'tab' => $offer_tab_id, 'slug' => $this->getOfferSlug($this->title)), $this->title) . '
        </div>
        <div class="sitepage_offer_date seaocore_txt_light">	
    ';

    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    $claim_value = Engine_Api::_()->getDbTable('claims', 'sitepageoffer')->getClaimValue($viewer_id, $this->offer_id, $this->page_id);

    if ($this->claim_count == -1 && ($this->end_time > $today || $this->end_settings == 0)) {
      $show_offer_claim = 1;
    } elseif ($this->claim_count > 0 && ($this->end_time > $today || $this->end_settings == 0)) {
      $show_offer_claim = 1;
    } else {
      $show_offer_claim = 0;
    }

    $request = Zend_Controller_Front::getInstance()->getRequest();
    $urlO = $request->getRequestUri();
    $request_url = explode('/', $urlO);
    $param = 1;
    if (empty($request_url['2'])) {
      $param = 0;
    }
    $return_url = (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) ? "https//" : "http//";
    $currentUrl = urlencode($urlO);

    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)
    ) {
      //RENDER THE THING
      if (!empty($show_offer_claim) && empty($claim_value)) {
        $content .= '<span><img src="' . Zend_Registry::get('Zend_View')->layout()->staticBaseUrl . 'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />';

        if (!empty($viewer_id)) {
          $content .= $view->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'getoffer', 'id' => $this->offer_id), Zend_Registry::get('Zend_Translate')->_('Get Offer'), array(
                      'class' => 'smoothbox',
                  )) . '</span>';
        } else {
          $offer_tabinformation = $view->url(array('action' => 'getoffer', 'id' => $this->offer_id, 'param' => $param, 'request_url' => $request_url['1']), 'sitepageoffer_general') . "?" . "return_url=" . $return_url . $_SERVER['HTTP_HOST'] . $currentUrl;
          $title = Zend_Registry::get('Zend_Translate')->_('Get Offer');
          $content .= "<a href=$offer_tabinformation>$title</a>" . '</span>';
        }
      } elseif (!empty($claim_value) && !empty($show_offer_claim) || ($this->claim_count == 0 && $this->end_time > $today && !empty($claim_value))) {
        $content .= '<span><img src="' . Zend_Registry::get('Zend_View')->layout()->staticBaseUrl . 'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />';
        $content .= $view->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'resendoffer', 'id' => $this->offer_id), Zend_Registry::get('Zend_Translate')->_('Resend Offer'), array(
                    'class' => 'smoothbox',
                )) . '</span>';
      } elseif ($this->claim_count == 0 || ($this->end_settings == 1 && $this->end_time < $today)) {
        $content .= '<span><b>' . Zend_Registry::get('Zend_Translate')->_('Expired') . '</b></span>';
      }

      $content .= '<span><b>&middot;</b></span><span>' . $this->claimed . ' ' . Zend_Registry::get('Zend_Translate')->_('claimed') . '</span>';

      if ($this->claim_count != -1) {
        $content .= '<span><b>&middot;</b></span><span>' . $this->claim_count . ' ' . Zend_Registry::get('Zend_Translate')->_(array('claim left', 'claims left', $this->claim_count), $view->locale()->toNumber($this->claim_count)) . '</span>';
      }
    }

    if (!empty($viewer_id)) {
      // Convert times
      date_default_timezone_set($oldTz);
    }

    $content .= '
      </div></div>
    ';
    return $content;
  }

  /**
   * Return slug corrosponding to offer title
   *
   * @return offertitle
   */
  public function getOfferSlug() {

    $string = $this->title;

    setlocale(LC_CTYPE, 'pl_PL.utf8');
    $string = @iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    $string = strtolower($string);
    $string = strtr($string, array('&' => '-', '"' => '-', '&' . '#039;' => '-', '<' => '-', '>' => '-', '\'' => '-'));
    $string = preg_replace('/^[^a-z0-9]{0,}(.*?)[^a-z0-9]{0,}$/si', '\\1', $string);
    $string = preg_replace('/[^a-z0-9\-]/', '-', $string);
    $string = preg_replace('/[\-]{2,}/', '-', $string);
    return $string;
  }

}

?>