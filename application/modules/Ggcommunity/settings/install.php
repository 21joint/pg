<?php
/**
 * EXTFOX
 *
 * @package    Ggcommunity
 * @author     EXTFOX
 */


class Ggcommunity_Installer extends Engine_Package_Installer_Module
{
    public function onInstall()
    {

      $this->_addGgcommunityListPage();
      $this->_addGgcommunityViewPage();
      $this->_addGgcommunityBrowsePage();
  
      $this->_addGgcommunityCreatePage();
      $this->_addGgcommunityLeaderboardPage();
  
      parent::onInstall();
    }

    protected function _addGgcommunityLeaderboardPage()
    {

        $db = $this->getDb();

        // profile page
        $pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'ggcommunity_question-index_leaderboard')
        ->limit(1)
        ->query()
        ->fetchColumn();

        if( !$pageId ) {

            // Insert page
            $db->insert('engine4_core_pages', array(
                'name' => 'ggcommunity_question-index_leaderboard',
                'displayname' => 'Ggcommunity Question Index Leaderboard',
                'title' => 'View Leaderboard Page',
                'description' => 'This is Leaderboard Page.',
                'custom' => 1,
            ));
            $pageId = $db->lastInsertId();

            // Insert main
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $pageId,
                'order' => 2,
            ));
            $mainId = $db->lastInsertId();

            // Insert main-middle
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $pageId,
                'parent_content_id' => $mainId,
                'order' => 6,
            ));
            $mainMiddleId = $db->lastInsertId();

            // Insert right
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'right',
                'page_id' => $pageId,
                'parent_content_id' => $mainId,
                'order' => 5,
            ));
            $rightId = $db->lastInsertId();

            // Insert unanswered struggle
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.unanswered-struggles',
                'page_id' => $pageId,
                'parent_content_id' => $rightId,
                'order' => 6,
                'params' => Zend_Json::encode(array(
                    'title' => 'Unanswered Struggles',
                    'nomobile' => '0',
                    'name' => 'ggcommunity.unanswered-struggles'
                )),
            ));

            // Insert content
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'core.content',
                'page_id' => $pageId,
                'parent_content_id' => $mainMiddleId,
                'order' => 3,
            ));

        }
    }


    protected function _addGgcommunityCreatePage()
    {

        $db = $this->getDb();

        // profile page
        $pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'ggcommunity_question-index_create')
        ->limit(1)
        ->query()
        ->fetchColumn();

        if( !$pageId ) {

            // Insert page
            $db->insert('engine4_core_pages', array(
                'name' => 'ggcommunity_question-index_create',
                'displayname' => 'Ggcommunity Question Index Create',
                'title' => 'Write New Struggle',
                'description' => 'This is Create Struggle Page.',
                'custom' => 1,
            ));
            $pageId = $db->lastInsertId();

            // Insert main
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $pageId,
                'order' => 2,
            ));
            $mainId = $db->lastInsertId();

            // Insert main-middle
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $pageId,
                'parent_content_id' => $mainId,
                'order' => 6,
            ));
            $mainMiddleId = $db->lastInsertId();

            // Insert right
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'right',
                'page_id' => $pageId,
                'parent_content_id' => $mainId,
                'order' => 5,
            ));
            $rightId = $db->lastInsertId();

            // Insert how to ask widget
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.how-to-ask',
                'page_id' => $pageId,
                'parent_content_id' => $rightId,
                'order' => 5,
                'params' => Zend_Json::encode(array(
                    'title' => 'How to Ask',
                    'description' => 'We prefer question that can be answered, not just discussed.',
                    'first_rule' => 'Awarded a Best Answer',
                    'second_rule' => 'Check for grammar or spelling errors',
                    'third_rule' => 'Phrase it like a question.',
                    'nomobile' => '0',
                    'name' => 'ggcommunity.how-to-ask'
                )),
            ));

            // Insert how to earn points widget
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.earn-points',
                'page_id' => $pageId,
                'parent_content_id' => $rightId,
                'order' => 6,
                'params' => Zend_Json::encode(array(
                    'title' => 'How Can I earn points?',
                    'rule_one' => 'Awarded a best Answer',
                    'points_rule_one' => '10',
                    'rule_two' => 'Answer Question',
                    'points_rule_two' => '2',
                    'rule_three' => 'Choose a Best Answer',
                    'points_rule_one' => '3',
                    'nomobile' => '0',
                    'name' => 'ggcommunity.earn-points'
                )),
            ));

            // Insert content
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'core.content',
                'page_id' => $pageId,
                'parent_content_id' => $mainMiddleId,
                'order' => 3,
            ));

        }
    }
    

    protected function _addGgcommunityBrowsePage()
    {

        $db = $this->getDb();

        // profile page
        $pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'ggcommunity_question-index_browse')
        ->limit(1)
        ->query()
        ->fetchColumn();

        if( !$pageId ) {

            // Insert page
            $db->insert('engine4_core_pages', array(
                'name' => 'ggcommunity_question-index_browse',
                'displayname' => 'Ggcommunity Question Index  Browse',
                'title' => 'Browse Struggle Page',
                'description' => 'This is Browse Struggle Page.',
                'custom' => 1,
            ));
            $pageId = $db->lastInsertId();

            // Insert main
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $pageId,
                'order' => 2,
            ));
            $mainId = $db->lastInsertId();

            // Insert main-middle
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $pageId,
                'parent_content_id' => $mainId,
                'order' => 6,
            ));
            $mainMiddleId = $db->lastInsertId();

            // Insert right
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'right',
                'page_id' => $pageId,
                'parent_content_id' => $mainId,
                'order' => 5,
            ));
            $rightId = $db->lastInsertId();

            // Insert new struggle widget
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.new-struggle',
                'page_id' => $pageId,
                'parent_content_id' => $rightId,
                'order' => 5,
                'params' => Zend_Json::encode(array(
                    'title' => '',
                    'new_struggle' => '0',
                    'nomobile' => '0',
                    'name' => 'ggcommunity.new-struggle'
                )),
            ));

            // Insert content
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'core.content',
                'page_id' => $pageId,
                'parent_content_id' => $mainMiddleId,
                'order' => 3,
            ));

        }
    }


    protected function _addGgcommunityViewPage()
    {

        $db = $this->getDb();

        // profile page
        $pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'ggcommunity_question-index_browse')
        ->limit(1)
        ->query()
        ->fetchColumn();

        if( !$pageId ) {

            // Insert page
            $db->insert('engine4_core_pages', array(
                'name' => 'ggcommunity_question-profile_view',
                'displayname' => 'Ggcommunity Question Profile View',
                'title' => 'Profile Struggle Page',
                'description' => 'This is Profile Struggle Page.',
                'custom' => 1,
            ));
            $pageId = $db->lastInsertId();

            // Insert main
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $pageId,
                'order' => 2,
            ));
            $mainId = $db->lastInsertId();

            // Insert main-middle
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $pageId,
                'parent_content_id' => $mainId,
                'order' => 6,
            ));
            $mainMiddleId = $db->lastInsertId();

            // Insert topic question profile widget
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.topic-question-profile',
                'page_id' => $pageId,
                'parent_content_id' => $mainMiddleId,
                'order' => 3,
            ));

            // Insert answer comment widgets
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.answer-content',
                'page_id' => $pageId,
                'parent_content_id' => $mainMiddleId,
                'order' => 4,
            ));

            // Insert question comment content widgets
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.question-comment-content',
                'page_id' => $pageId,
                'parent_content_id' => $mainMiddleId,
                'order' => 5,
            ));

            // Insert right
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'right',
                'page_id' => $pageId,
                'parent_content_id' => $mainId,
                'order' => 5,
            ));
            $rightId = $db->lastInsertId();

            // Insert ggcommunity answer widget
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.ggcommunity-answer',
                'page_id' => $pageId,
                'parent_content_id' => $rightId,
                'order' => 7,
            ));

            // Insert ggcommunity related widgets
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.ggcommunity-related',
                'page_id' => $pageId,
                'parent_content_id' => $rightId,
                'order' => 8,
                'params' => Zend_Json::encode(array(
                    'title' => 'Related Struggles',
                    'nomobile' => '0',
                    'name' => 'ggcommunity.ggcommunity-related'
                )),
            ));

            // Insert featured topic widgets
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.featured-topic',
                'page_id' => $pageId,
                'parent_content_id' => $rightId,
                'order' => 9,
                'params' => Zend_Json::encode(array(
                    'title' => 'Featured Topics',
                    'nomobile' => '0',
                    'name' => 'ggcommunity.featured-topic'
                )),
            ));

            // Insert review product widgets
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.review-product',
                'page_id' => $pageId,
                'parent_content_id' => $rightId,
                'order' => 10,
            ));

            // Insert draft question widgets
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.draft-question',
                'page_id' => $pageId,
                'parent_content_id' => $rightId,
                'order' => 11,
                'params' => Zend_Json::encode(array(
                    'title' => 'Draft',
                    'content' => 'You are currently in <b>draft</b> mode. Click on the button bellow to   publish',
                    'nomobile' => '0',
                    'name' => 'ggcommunity.draft-question'
                )),
            ));

        }
    }

    protected function _addGgcommunityListPage()
    {

        $db = $this->getDb();

        // profile page
        $pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'ggcommunity_question-index_list')
        ->limit(1)
        ->query()
        ->fetchColumn();

        if( !$pageId ) {

            // Insert page
            $db->insert('engine4_core_pages', array(
                'name' => 'ggcommunity_question-index_list',
                'displayname' => 'Ggcommunity Question Index List',
                'title' => 'Listing Struggle  Page',
                'description' => 'This is Listing Struggle Page.',
                'custom' => 1,
            ));
            $pageId = $db->lastInsertId();

            // Insert top
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'top',
                'page_id' => $pageId,
                'order' => 1,
            ));
            $topId = $db->lastInsertId();

            // Insert main
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'main',
                'page_id' => $pageId,
                'order' => 2,
            ));
            $mainId = $db->lastInsertId();

            // Insert top-middle
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $pageId,
                'parent_content_id' => $topId,
                'order' => 6,
            ));
            $topMiddleId = $db->lastInsertId();

            // Insert main-middle
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'middle',
                'page_id' => $pageId,
                'parent_content_id' => $mainId,
                'order' => 6,
            ));
            $mainMiddleId = $db->lastInsertId();

            // Insert main-right
            $db->insert('engine4_core_content', array(
                'type' => 'container',
                'name' => 'right',
                'page_id' => $pageId,
                'parent_content_id' => $mainId,
                'order' => 5,
            ));
            $mainRightId = $db->lastInsertId();

            // Insert search struggle widget
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.search-struggle',
                'page_id' => $pageId,
                'parent_content_id' => $topMiddleId,
                'order' => 3,
                'params' => Zend_Json::encode(array(
                    'title' => 'PARENTAL GUIDANCE',
                    'description' => 'Share Your Struggle, Provide Your Theories, and Gain Advace',
                    'select' => 'public\/admin\/star_pg.svg',
                    'nomobile' => '0',
                    'name' => 'ggcommunity.search-struggle'
                )),
            ));

            // Insert featured topics widget
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.featured-topics',
                'page_id' => $pageId,
                'parent_content_id' => $topMiddleId,
                'order' => 4,
                'params' => Zend_Json::encode(array(
                    'title' => 'Featured Topics',
                    'nomobile' => '0',
                    'name' => 'ggcommunity.featured-topics'
                )),
            ));

            // Insert trending struggle widget
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.trending-struggles',
                'page_id' => $pageId,
                'parent_content_id' => $mainMiddleId,
                'order' => 7,
                'params' => Zend_Json::encode(array(
                    'title' => 'Trending Struggles',
                    'name' => 'ggcommunity.trending-struggles'
                )),
            ));

            // Insert latest struggle widgets
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.latest-struggles',
                'page_id' => $pageId,
                'parent_content_id' => $mainMiddleId,
                'order' => 8,
                'params' => Zend_Json::encode(array(
                    'title' => 'Latest Struggles',
                    'name' => 'ggcommunity.latest-struggles'
                )),
            ));

            // Insert new struggle widgets
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.new-struggle',
                'page_id' => $pageId,
                'parent_content_id' => $mainMiddleId,
                'order' => 9,
                'params' => Zend_Json::encode(array(
                    'title' => '',
                    'new_struggle' => '1',
                    'nomobile' => '0',
                    'name' => 'ggcommunity.new-struggle'
                )),
            ));

            // Insert unanswered struggle widgets
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.unanswered-struggles',
                'page_id' => $pageId,
                'parent_content_id' => $mainRightId,
                'order' => 11,
                'params' => Zend_Json::encode(array(
                    'title' => 'Unanswered Struggles',
                    'name' => 'ggcommunity.unanswered-struggles'
                )),
            ));

            // Insert top theorist widgets
            $db->insert('engine4_core_content', array(
                'type' => 'widget',
                'name' => 'ggcommunity.top-theorist',
                'page_id' => $pageId,
                'parent_content_id' => $mainRightId,
                'order' => 12,
                'params' => Zend_Json::encode(array(
                    'title' => 'Top Theorist',
                    'more' => '0',
                    'nomobile' => '0',
                    'name' => 'ggcommunity.top-theorist'
                )),
            ));

        }
    }

}
?>
