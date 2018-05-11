<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: CreateIcons.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitereaction_IconsActivation {

    
     
    
    
    public function activate() {		 
        ini_set('max_execution_time', 0);		
         $this->createSearchIcon();		     
         $this->createReactionIcon();		     
         $this->createCollection();		     
    }
    
    /*
     * We are calling this method from "controllers/license/widgetSettings.php" file.
     */
    private function createSearchIcon() {
        // get the table
        $searchTable = Engine_Api::_()->getDbtable('stickersearch', 'sitereaction');
        // get all reaction icons
        $searchOptions = $searchTable->getList();
        $path = APPLICATION_PATH . '/application/modules/Sitereaction/externals/search_icons';
        foreach ($searchOptions as $searchOption) {
            if ($searchOption->file_id) {
                continue;
            }
            $fileName = $searchOption->keyword . '.png';
            $Filedata = array(
                'tmp_name' => $path . '/' . $fileName,
                'name' => $fileName,
            );
            $searchOption->setIcon($Filedata);
        }
    }

    /*
     * We are calling this method from "controllers/license/widgetSettings.php" file.
     */
    private function createReactionIcon() {
        // get the table
        $reactionIconsTable = Engine_Api::_()->getDbTable('reactionicons', 'sitereaction');

        // get all reaction icons
        $reactions = $reactionIconsTable->getReactions();
        $path = APPLICATION_PATH . '/application/modules/Sitereaction/externals/reactions';
        foreach ($reactions as $reaction) {
            if ($reaction->photo_id) {
                continue;
            }
            $fileName = $reaction->type . '.png';
            $Filedata = array(
                'tmp_name' => $path . '/' . $fileName,
                'name' => $fileName,
            );
            $reaction->setPhoto($Filedata);
        }
    }

    /*
     * We are calling this method from "controllers/license/widgetSettings.php" file.
     */
    private function createCollection() {
        $table = Engine_Api::_()->getDbtable('collections', 'sitereaction');
        // GET PAGE LIST.
        $select = $table->select();
        $hasCollection = $table->fetchRow($select);
        if ($hasCollection) {
            return;
        }
        foreach ($this->getCollections() as $values) {
            $collectionTable = Engine_Api::_()->getDbtable('collections', 'sitereaction');
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {
                $collection = $collectionTable->createRow();
                $collection->setFromArray($values);
                $collection->save();
                $collection->createStickers($values['stickers']);
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
        }
    }

    private function getCollections() {
        $stickersPath = APPLICATION_PATH . '/application/modules/Sitereaction/externals/stickers';
        $stickersInfo = include APPLICATION_PATH . '/application/modules/Sitereaction/externals/stickers/stickerInfo.php';
        $collections = array();

        $date = (new Zend_Date());
        $tempDate = $date->toArray();
        $needToSubMinute = $tempDate['minute'] % 10;
        $date->subMinute($needToSubMinute);
        $startTime = date('Y-m-d H:i:s', $date->getTimestamp());
        if (is_dir($stickersPath)) {
            foreach (scandir($stickersPath) as $dirName) {
                $dirPath = $stickersPath . '/' . $dirName;
                if (in_array($dirName, array('.', '..')) || !is_dir($dirPath)) {
                    continue;
                }
                $title = trim(str_replace(array('-', '_'), ' ', $dirName));
                $collection = array('title' => $title, 'body' => $title, 'start_time' => $startTime,
                    'end_time' => '2050-12-31 23:59:59', 'stickers' => $this->getStickers($dirPath));
                if (isset($stickersInfo[$dirName]) && is_array($stickersInfo[$dirName])) {
                    $collection = array_merge($collection, $stickersInfo[$dirName]);
                }
                $collections[] = $collection;
            }
        }
        return $collections;
    }

    private function getStickers($path) {
        $stickers = array();
        if (is_dir($path)) {
            foreach (scandir($path) as $fileName) {
                $extension = ltrim(strrchr(basename($fileName), '.'), '.');
                if (!in_array($extension, array('jpg', 'png', 'gif', 'jpeg'))) {
                    continue;
                }
                $Filedata = array(
                    'tmp_name' => $path . '/' . $fileName,
                    'name' => $fileName,
                );
                $stickers[] = array('Filedata' => $Filedata);
            }
        }
        return $stickers;
    }

}
