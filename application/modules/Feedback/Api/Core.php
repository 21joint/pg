<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Api_Core extends Core_Api_Abstract
{

  /**
   * Get tags data
   *
   * @param int $owner_id
   * @return tags data
   */
  public function getOwnerTags($owner_id) 
	{
		$tableTagmaps = Engine_Api::_()->getDbtable('tagmaps', 'core')->info('name');
		$tableTags = Engine_Api::_()->getDbtable('tags', 'core')->info('name');

		$tableFeedback = Engine_Api::_()->getDbtable('feedbacks', 'feedback');
		$tableFeedbackName = $tableFeedback->info('name');
		
		$select = $tableFeedback->select()
										->setIntegrityCheck(false)
										->from($tableFeedbackName, array(''))
										->joinInner($tableTagmaps, "$tableFeedbackName.feedback_id = $tableTagmaps.resource_id", array('COUNT(resource_id) AS Frequency'))
										->joinInner($tableTags, "$tableTags.tag_id = $tableTagmaps.tag_id",array('text', 'tag_id'))
										->where($tableFeedbackName . '.owner_id = ?', $owner_id)
										->where($tableFeedbackName . '.feedback_private = ?', 'public')
										->where($tableTagmaps . '.resource_type = ?', 'feedback')
										->group("$tableTags.text")
										->order("Frequency")
										->limit(100);
		return $select->query()->fetchAll();
	}

  /**
   * check can comment or not
   *
   * @param int $viewer_id
   * @param string $remote_address
   * @return can comment or not
   */
	public function canComment($remote_address, $viewer_id)
	{
		if(empty($viewer_id)) {
			return 0;
		}

		$blockIpTable = Engine_Api::_()->getitemTable('feedback_blockip');    
		$blociIpData = $blockIpTable->select()
												 ->from($blockIpTable->info('name'), array('blockip_comment'))
												 ->where('blockip_address = ?', $remote_address)
												 ->query()
                         ->fetchColumn();
		$ipCanComment = 1;
		if(!empty($blociIpData)) {
			return 0;
		}
    
		$blockUserTable = Engine_Api::_()->getitemTable('feedback_blockuser');    
		$blockUserData = $blockUserTable->select()
												 ->from($blockUserTable->info('name'), array('block_comment'))
												 ->where('blockuser_id = ?', $viewer_id)
												 ->query()
                         ->fetchColumn();
		$userCanComment = 1;
		if(!empty($blockUserData)) {
			return 0;
		}

		if($ipCanComment && $userCanComment) {
			return 1;
		}
	}

  /**
   * image creation for non-loggedin user
   *
   * @param array $file
   * @param array $params
   * @return created image infor
   */
  public function create($file, $params)
  {
		$params = array_merge(array(
      'storage_type' => 'local', // $this->getDefaultService() @todo fix this
    ), (array) $params);
    $space_limit = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('core_general_quota', 0);
    
    $tableName = Engine_Api::_()->getItemTable('storage_file')->info('name');

    //fetch user
    if( !empty($params['user_id']) &&
        null != ($user = Engine_Api::_()->getItem('user', $params['user_id'])) ) {
      $user_id = $user->getIdentity();
      $level_id = $user->level_id;
    } else if( null != ($user = Engine_Api::_()->user()->getViewer()) ) {
      $user_id = $user->getIdentity();
      //$level_id = $user->level_id;
    } else {
      $user_id = null;
      $level_id = null;
    }

    // member level quota
    if( null !== $user_id && null !== $level_id ) {
      $space_limit = (int) Engine_Api::_()->authorization()->getPermission($level_id, 'user', 'quota');
      $space_used = (int) $this->select()
        ->from($tableName, new Zend_Db_Expr('SUM(size) AS space_used'))
        ->where("user_id = ?", (int) $user_id)
        ->query()
        ->fetchColumn(0);
      $space_required = (is_array($file) && isset($file['tmp_name'])
        ? filesize($file['tmp_name']) : filesize($file));

      if( $space_limit > 0 && $space_limit < ($space_used + $space_required) ) {
        throw new Engine_Exception("File creation failed. You may be over your " .
          "upload limit. Try uploading a smaller file, or delete some files to " .
          "free up space. ", self::SPACE_LIMIT_REACHED_CODE);
      }
    }

    $row = Engine_Api::_()->getItemTable('storage_file')->createRow();
    $row->setFromArray($params);
    $row->store($file);

    return $row;
  }

  /**
   * check can create feedback or not
   *
   * @param int $viewer_id
   * @param string $remote_address
   * @return can create feedback or not
   */
	public function canCreateFeedback($remote_address, $viewer_id)
	{
		if(empty($viewer_id)) {
			return 0;
		}

		$blockIpTable = Engine_Api::_()->getitemTable('feedback_blockip');    
		$blociIpData = $blockIpTable->select()
												 ->from($blockIpTable->info('name'), array('blockip_feedback'))
												 ->where('blockip_address = ?', $remote_address)
												 ->query()
                         ->fetchColumn();
		$ipCanCreate = 1;
		if(!empty($blociIpData)) {
			return 0;
		}
    
		$blockUserTable = Engine_Api::_()->getitemTable('feedback_blockuser');    
		$blockUserData = $blockUserTable->select()
												 ->from($blockUserTable->info('name'), array('block_feedback'))
												 ->where('blockuser_id = ?', $viewer_id)
												 ->query()
                         ->fetchColumn();
		$userCanCreate = 1;
		if(!empty($blockUserData)) {
			return 0;
		}

		if($ipCanCreate && $userCanCreate) {
			return 1;
		}
	}
  
  public function getCaptchaOptions(array $params = array())
  {
    $spamSettings = Engine_Api::_()->getApi('settings', 'core')->core_spam;
    if(empty($spamSettings['recaptchapublic']) || empty($spamSettings['recaptchaprivate'])) {
      // Image captcha
      return array_merge(array(
        'label' => 'Human Verification',
        'description' => 'Please type the characters you see in the image.',
        'captcha' => 'image',
        'required' => true,
        'captchaOptions' => array(
          'wordLen' => 6,
          'fontSize' => '30',
          'timeout' => 300,
          'imgDir' => APPLICATION_PATH . '/public/temporary/',
          'imgUrl' => Zend_Registry::get('Zend_View')->baseUrl() . '/public/temporary',
          'font' => APPLICATION_PATH . '/application/modules/Core/externals/fonts/arial.ttf',
        ),
      ), $params);
    } else {
      // Recaptcha
      return array_merge(array(
        'label' => 'Human Verification',
        'description' => 'Please type the characters you see in the image.',
        'captcha' => 'reCaptcha',
        'required' => true,
        'captchaOptions' => array(
          'privkey' => $spamSettings['recaptchaprivate'],
          'pubkey' => $spamSettings['recaptchapublic'],
          'theme' => 'white',
          'lang' => Zend_Registry::get('Locale')->getLanguage(),
          'tabindex' => (isset($params['tabindex']) ? $params['tabindex'] : null ),
          'ssl' => constant('_ENGINE_SSL')   // Fixed Captcha does not work well when ssl is enabled on website
        ),
      ), $params);
    }
  }  
}
