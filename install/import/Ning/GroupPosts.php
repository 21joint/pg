<?php

class Install_Import_Ning_GroupPosts extends Install_Import_Ning_Abstract
{
  protected $_fromFile = 'ning-discussions-local.json';

  protected $_fromFileAlternate = 'ning-discussions.json';

  protected $_toTable = 'engine4_group_posts';

  protected $_resourceType = 'group_post';

  protected function  _translateRow(array $data, $key = null)
  {
    if( empty($data['groupId']) ) {
      return false;
    }

    $groupIdentity = $this->getGroupMap($data['groupId']);
    $userIdentity = $this->getUserMap($data['contributorName']);
    $topicIdentity = $key + 1;


    // push primary post
    $posts = (array) @$data['comments'];
    array_push($posts, array(
      'id' => $data['id'],
      'contributorName' => $data['contributorName'],
      'description' => $data['description'],
      'createdDate' => $data['createdDate'],
    ));

    $lastPostId = null;
    $lastPosterId = null;
    foreach( array_reverse($posts) as $postData ) {
      $postUserIdentity = $this->getUserMap($postData['contributorName']);
      $this->getToDb()->insert($this->getToTable(), array(
        'group_id' => $groupIdentity,
        'topic_id' => $topicIdentity,
        'user_id' => $postUserIdentity,
        'body' => $postData['description'],
        'creation_date' => $this->_translateTime($postData['createdDate']),
        'modified_date' => $this->_translateTime($postData['createdDate']),
      ));
      $lastPostId = $this->getToDb()->lastInsertId();
      // search
      $this->_insertSearch($this->_resourceType, $lastPosterId, array(
        'description' => $postData['description'],
      ));
      $lastPosterId = $postUserIdentity;
    }

    // Update last post?
    if( count($posts) > 0 && $lastPostId && $lastPosterId ) {
      $this->getToDb()->update('engine4_group_topics', array(
        'lastpost_id' => $lastPostId,
        'lastposter_id' => $lastPosterId,
      ), array(
        'group_id = ?' => $groupIdentity,
        'topic_id = ?' => $topicIdentity,
      ));
    }

    return false;
  }
}
