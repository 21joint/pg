<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitequicksignup
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2017-03-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitequicksignup_Api_Core extends Core_Api_Abstract {

    /**
     * Get Widgetized PageId
     * @param $params
     */
    public function getWidgetizedPageId($params = array()) {
        //GET CORE CONTENT TABLE
        $tableNamePages = Engine_Api::_()->getDbtable('pages', 'core');
        $page_id = $tableNamePages->select()
                ->from($tableNamePages->info('name'), 'page_id')
                ->where('name =?', $params['name'])
                ->query()
                ->fetchColumn();
        return $page_id;
    }

}
