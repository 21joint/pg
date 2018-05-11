<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitehashtag
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    Tags.php 2016-01-25 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitehashtag_Model_DbTable_Tags extends Core_Model_Item_DbTable_Abstract
{
  protected $_rowClass = "Sitehashtag_Model_Tag";

  /**
   * Get the tag table
   *
   * @return Engine_Db_Table
   */
  public function getHashTagTable()
  {
    return Engine_Api::_()->getDbtable('tags', 'sitehashtag');
  }

  /**
   * Get the tag map table
   *
   * @return Engine_Db_Table
   */
  public function getMapTable()
  {
    return Engine_Api::_()->getDbtable('tagmaps', 'sitehashtag');
  }

  // Tags
  /**
   * Get an existing or create a new text tag
   *
   * @param string $text The tag text
   */
  public function getHashTag($text, $createNew = false)
  {

    $table = $this->getHashTagTable();
    $select = $table->select()
      ->where('text = ?', $text);

    $row = $table->fetchRow($select);

    if ($createNew && null === $row) {
      $row = $table->createRow();
      $row->text = $text;
      $row->save();
    }

    return $row;
  }

  /**
   * Tag a resource
   *
   * @param $action_id
   * @param string|Core_Model_Item_Abstract $tag What is tagged in resource
   */
  public function addHashTagMap($action_id, $hashtag)
  {

    $hashtag = $this->_getHashTag($hashtag, true);

    if (!$hashtag) {
      return false;
    }

    // Check if resource already had hashtag
    if (null !== ($hashtagmap = $this->getHashTagMap($action_id, $hashtag))) {
      return false; // return $tagmap;
    }

    // Do the tagging
    $table = $this->getMapTable();
    $table->insert(array(
      'tag_id' => $hashtag->getIdentity(),
      'action_id' => $action_id,
      'creation_date' => date('Y-m-d H:i:s')
    ));


    $hashtag->tag_count = $hashtag->tag_count + 1;
    $hashtag->save();
  }

  /**
   * Add multiple tags
   *
   * @param $action_id
   * @param array $hashtags
   * @return array
   */
  public function addHashTagMaps($action_id, $hashtags)
  {
    $hashtagmaps = array();

    foreach ($hashtags[0] as $key => $hashtag) {
      // ignore empty tags
      if (empty($hashtag))
        continue;

      $hashtagmaps[] = $this->addHashTagMap($action_id, $hashtag);
    }
    return $hashtagmaps;
  }

  /**
   * Get a tag map on resource and existing tag (for checking if already tagged)
   *
   * @param string|Core_Model_Item_Abstract $hashtag
   * @return Engine_Db_Table|null
   */
  public function getHashTagMap($action_id, $hashtag)
  {

    $hashtag = $this->_getHashTag($hashtag);

    $table = $this->getMapTable();

    $select = $table->select()
      ->where('action_id = ?', $action_id)
      ->where('tag_id = ?', $hashtag->getIdentity())
      ->limit(1);

    $hashtagmap = $table->fetchRow($select);

    return $hashtagmap;
  }

  /**
   * Gets an existing string tag or returns the passed item
   *
   * @param string|Core_Model_Item_Abstract $hashtag
   * @return Core_Model_Item_Abstract
   * @throws Core_Model_Exception If argument is not a string or an item
   */
  protected function _getHashTag($hashtag, $createNew = false)
  {
    if (is_string($hashtag)) {
      $hashtag = $this->getHashTag($hashtag, $createNew);
    }

    return $hashtag;
  }

  public function deleteHashTagMaps($action_id, array $hashtags)
  {
    $hashtagmaps = array();

    foreach ($hashtags[0] as $key => $hashtag) {

      $this->deleteHashTagMap($action_id, $hashtag);
    }
  }

  public function deleteHashTagMap($action_id, $hashtag)
  {

    $hashtag = $this->_getHashTag($hashtag);
    if (!$hashtag) {
      return;
    }
    $tagMap = $this->getHashTagMap($action_id, $hashtag);
    if (!$tagMap) {
      return;
    }

    $this->getMapTable()->delete(array(
      'action_id = ?' => $action_id,
      'tag_id = ?' => $hashtag->tag_id,
    ));
    if ($hashtag->tag_count == 1) {
      return $hashtag->delete();
    }
    $hashtag->tag_count = $hashtag->tag_count - 1;
    $hashtag->save();
  }

  public function editHashTagMaps($action_id, array $hashtags)
  {

    $hashtagMapTable = $this->getMapTable();
    $hashtagMaps = $hashtagMapTable->getActionTagMaps($action_id);
    foreach ($hashtagMaps as $hashtagMap) {
      $tag = $this->getItem($hashtagMap->tag_id);
      if ($tag && !in_array($tag->text, $hashtags[0])) {
        $this->deleteHashTagMap($action_id, $tag);
      }
    }
    return $this->addHashTagMaps($action_id, $hashtags);
  }

  public function getTopTrends($limit, $duration)
  {
    $mapTableName = $this->getMapTable()->info('name');
    $select = $this->select()
        ->setIntegrityCheck(false)
        ->from($this->info('name'))
        ->join($mapTableName, $this->info('name') . '.tag_id=' . $mapTableName . '.tag_id', array('count(*) as tagmap_count'))
        ->where("DATE_ADD(creation_date, INTERVAL " . $duration . " DAY) > now()")
        ->order('tagmap_count desc')
        ->order('modified_date desc')
        ->group('tag_id')
        ->limit($limit);
    $hashtagMaps = $this->fetchAll($select);
    foreach ($hashtagMaps as $hashtag) {
      $hashtagNames[] = $hashtag->text;
    }

    return $hashtagNames;
  }

  public function getItems($text, $limit = 20, $order)
  {

    //MAKE QUERY
    $select = $this->select()
      ->from($this->info('name'), array('tag_id', 'text'))
      ->where('text  LIKE ? ', '%' . $text . '%')
      ->order($order . ' desc')
      ->limit($limit);

    //RETURN RESULTS
    return $this->fetchAll($select);
  }

}
