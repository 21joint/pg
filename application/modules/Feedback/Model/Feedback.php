<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Feedback.php 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Feedback_Model_Feedback extends Core_Model_Item_Abstract
{
  protected $_owner_type = 'user';
  
  protected $_parent_type = 'user';

  protected $_searchColumns = array('feedback_title', 'feedback_description');

  protected $_parent_is_owner = true;

	const IMAGE_WIDTH = 720;
  const IMAGE_HEIGHT = 720;
	const THUMB_WIDTH = 140;
  const THUMB_HEIGHT = 160;
  
	public function createImage($params, $file)
  {
    if( $file instanceof Storage_Model_File ) {
      	$params['file_id'] = $file->getIdentity();
    }
		else {
      // Get image info and resize
      $name = basename($file['tmp_name']);
      $path = dirname($file['tmp_name']);
      $extension = ltrim(strrchr($file['name'], '.'), '.');

      $mainName = $path.'/m_'.$name . '.' . $extension;
      $thumbName = $path.'/t_'.$name . '.' . $extension;

      $image = Engine_Image::factory();
      $image->open($file['tmp_name'])
            ->resize(self::IMAGE_WIDTH, self::IMAGE_HEIGHT)
              ->write($mainName)
              ->destroy();

      $image = Engine_Image::factory();
      $image->open($file['tmp_name'])
            ->resize(self::THUMB_WIDTH, self::THUMB_HEIGHT)
            ->write($thumbName)
            ->destroy();

      $image_params = array(
        'parent_id' => $params['feedback_id'],
        'parent_type' => 'feedback',
      );
	
			if(empty($params['user_id'])) {
				$imageFile = Engine_Api::_()->feedback()->create($mainName, $image_params);
				$thumbFile = Engine_Api::_()->feedback()->create($thumbName, $image_params);
			}
			else {
				$imageFile = Engine_Api::_()->storage()->create($mainName, $image_params);
				$thumbFile = Engine_Api::_()->storage()->create($thumbName, $image_params);
			}

      $imageFile->bridge($thumbFile, 'thumb.normal');

      $params['file_id'] = $imageFile->file_id;
      $params['image_id'] = $imageFile->file_id;
     
    }

    $row = Engine_Api::_()->getDbtable('images', 'feedback')->createRow();
    $row->setFromArray($params);
    $row->save();
    return $row;
  }

  /**
   * Gets an absolute URL to the page to view this item
   *
   * @return string
   */
  public function getHref($params = array())
  { 
    $params = array_merge(array(
      'route' => 'feedback_detail_view',
      'reset' => true,
      'user_id' => $this->owner_id,
      'feedback_id' => $this->feedback_id,
      'slug' => $this->getSlug(),
    ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
      ->assemble($params, $route, $reset);
  }
  
	/**
   * Return keywords
   *
   * @param char separator 
   * @return keywords
   * */	
  public function getKeywords($separator = ' ')
  {
    $keywords = array();
    foreach( $this->tags()->getTagMaps() as $tagmap ) {
      $tag = $tagmap->getTag();
      $keywords[] = $tag->getTitle();
    }

    if( null === $separator ) {
      return $keywords;
    }

    return join($separator, $keywords);
  }

  /**
   * Return a feedback title
   *
   * @return title
   * */
  public function getTitle()
  {
    return $this->feedback_title;
  }
  
  /**
   * Return a feedback trunacte description
   *
   * @return truncate description
   * */
  public function getDescription()
  {
    $tmpBody = strip_tags($this->feedback_description);
    return ( Engine_String::strlen($tmpBody) > 255 ? Engine_String::substr($tmpBody, 0, 255) . '..' : $tmpBody );
  }

	/**
   * Return album object
   * 
   * @return album object
   * */
  public function getSingletonAlbum()
  {
    $table = Engine_Api::_()->getItemTable('feedback_album');
    $select = $table->select()
      				->where('feedback_id = ?', $this->getIdentity())
      				->order('album_id ASC')
      				->limit(1);

    $album = $table->fetchRow($select);

    if( null === $album ) {
      $album = $table->createRow();
      $album->setFromArray(array(
        'title' => $this->getTitle(),
        'feedback_id' => $this->getIdentity()
      ));
      $album->save();
    }

    return $album;
  }

  /**
   * Get total participants
   *
   * @param int $feedback_id
   * @return total participants
   */
	public function getTotalParticipants()
	{
   
		//GET COMMENT TABLE
		$tableComment   = Engine_Api::_()->getDbTable('comments', 'core');

		//FETCH DATA
		$count = 0;
		$count = $tableComment->select()
                         ->from($tableComment->info('name'), array('COUNT(DISTINCT `poster_id`) AS count'))
                         ->where('resource_id = ?', $this->feedback_id)
					    					 ->where('resource_type = ?', 'feedback')
					    					 ->limit(1)
												 ->query()
                         ->fetchColumn();
		//RETURN DATA
		return $count;
	}
  
    /**
     * Delete the event and belongings
     * 
     */
    public function _delete() {
        
        //DELETE VOTES 
        Engine_Api::_()->getDbtable('votes', 'feedback')->delete(array('feedback_id = ?' => $this->feedback_id));

        //DELETE IMAGE 
        $table   = Engine_Api::_()->getItemTable('feedback_image');
        $select  = $table->select()->where('feedback_id = ?', $this->feedback_id);						
        $rows = $table->fetchAll($select);
        if(!empty($rows)) {
          foreach($rows as $image) {
            $image->delete();
          }
        } 

        //DELETE ALBUM 
        $table   = Engine_Api::_()->getItemTable('feedback_album');
        $select  = $table->select()->where('feedback_id = ?', $this->feedback_id);
        $rows = $table->fetchRow($select);
        if(!empty($rows)) {
          $rows->delete();
        }        
        
        //DELETE EVENT
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
   * Gets a proxy object for the tags handler
   *
   * @return Engine_ProxyObject
   **/
  public function tags() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('tags', 'core'));
  }
}

