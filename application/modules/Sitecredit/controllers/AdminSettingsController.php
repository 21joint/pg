<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_AdminSettingsController extends Core_Controller_Action_Admin {

    protected $_periods = array(
        Zend_Date::DAY, //dd
        Zend_Date::WEEK, //ww
        Zend_Date::MONTH, //MM
        Zend_Date::YEAR, //y
    );
    protected $_allPeriods = array(
        Zend_Date::SECOND,
        Zend_Date::MINUTE,
        Zend_Date::HOUR,
        Zend_Date::DAY,
        Zend_Date::WEEK,
        Zend_Date::MONTH,
        Zend_Date::YEAR,
    );
    protected $_periodMap = array(
        Zend_Date::DAY => array(
            Zend_Date::SECOND => 0,
            Zend_Date::MINUTE => 0,
            Zend_Date::HOUR => 0,
        ),
        Zend_Date::WEEK => array(
            Zend_Date::SECOND => 0,
            Zend_Date::MINUTE => 0,
            Zend_Date::HOUR => 0,
            Zend_Date::WEEKDAY_8601 => 1,
        ),
        Zend_Date::MONTH => array(
            Zend_Date::SECOND => 0,
            Zend_Date::MINUTE => 0,
            Zend_Date::HOUR => 0,
            Zend_Date::DAY => 1,
        ),
        Zend_Date::YEAR => array(
            Zend_Date::SECOND => 0,
            Zend_Date::MINUTE => 0,
            Zend_Date::HOUR => 0,
            Zend_Date::DAY => 1,
            Zend_Date::MONTH => 1,
        ),
    );

    public function indexAction() {
        include_once APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license1.php';
    }

    public function instructionAction() {
        // instruction and guidlines
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_global');

        $this->view->navigationSubMenu = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main_global', array(), 'sitecredit_admin_global_instruction');
        $this->view->form = $form = new Sitecredit_Form_Admin_Instruction();

        // Populate values
        $instruction = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.instruction', '<p>1. <strong>Performing Activities</strong></p>
<p>&nbsp;&nbsp;&nbsp;Earn credits by performing various activities: liking a post, by commenting on a post, content creation etc.</p>
<p><strong>&nbsp;</strong></p>
<p>2. <strong>Referral Signups</strong></p>
<p>&nbsp;&nbsp;&nbsp;Invite your friends via referral link to join your community and earn considerable credits.</p>
<p><strong>&nbsp;</strong></p>
<p>3. <strong>Send to Friends</strong></p>
<p>&nbsp;&nbsp;&nbsp;You can send credits to your community friends as a gift.</p>
<p><strong>&nbsp;</strong></p>
<p>4. <strong>Bonus</strong></p>
<p>&nbsp;&nbsp;&nbsp;Community Admin can provide you credits as bonus for your active participation or on some special occasion.</p>
<p><strong>&nbsp;</strong></p>
<p>5. <strong>Buy Credits</strong></p>
<p>&nbsp;&nbsp;&nbsp;You can buy credits with an ongoing offer or exact credit value you need to get more credits.</p>
<p><br><br></p>');
        $terms = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.terms', '<p>1. Do not delete the activities which you have performed to earn credits. If you delete those activities, you will loose your earned credits.</p>
<p>2. Credits are more for activity when it is performed for the first time and credit value is same when it is performed second time onwards.</p>
<p>3. You can only upgrade your member level or switch to member level with same credit value as of your current member level. You cannot switch back to your previous member level even if you have required credits.</p>
<p>4. You can redeem your credits on the checkout page while purchase Event tickets or Store products.</p>
<p>5. Credits redeemed on checkout page will not be refunded even if you have cancelled the Event ticket or returned the Store product.</p>
<p>6. Your credit value will set to &lsquo;0&rsquo; once your credit validity expires.</p>');

        $form->sitecredit_terms->setValue($terms);
        $form->sitecredit_instruction->setValue($instruction);

        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
            $values = $form->getValues();
            unset($values['ad_header2']);
            unset($values['ad_header1']);
            include_once APPLICATION_PATH . '/application/modules/Sitecredit/controllers/license/license2.php';
            $form->addNotice('Your changes have been saved.');
        }
    }

    public function faqAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_faq');
    }

    public function uploadPhotoAction() {
        // upload photo
        $viewer = Engine_Api::_()->user()->getViewer();

        $this->_helper->layout->disableLayout();

        if (!$this->getRequest()->isPost()) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
            return;
        }

        if (!isset($_FILES['userfile']) || !is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid Upload');
            return;
        }

        $db = Engine_Api::_()->getDbtable('photos', 'album')->getAdapter();
        $db->beginTransaction();

        try {
            $viewer = Engine_Api::_()->user()->getViewer();

            $photoTable = Engine_Api::_()->getDbtable('photos', 'album');
            $photo = $photoTable->createRow();
            $photo->setFromArray(array(
                'owner_type' => 'user',
                'owner_id' => $viewer->getIdentity()
            ));
            $photo->save();

            $photo->setPhoto($_FILES['userfile']);

            $this->view->status = true;
            $this->view->name = $_FILES['userfile']['name'];
            $this->view->photo_id = $photo->photo_id;
            $this->view->photo_url = $photo->getPhotoUrl();

            $table = Engine_Api::_()->getDbtable('albums', 'album');
            $album = $table->getSpecialAlbum($viewer, 'credit');

            $photo->album_id = $album->album_id;
            $photo->save();

            if (!$album->photo_id) {
                $album->photo_id = $photo->getIdentity();
                $album->save();
            }

            $auth = Engine_Api::_()->authorization()->context;
            $auth->setAllowed($photo, 'everyone', 'view', true);
            $auth->setAllowed($photo, 'everyone', 'comment', true);
            $auth->setAllowed($album, 'everyone', 'view', true);
            $auth->setAllowed($album, 'everyone', 'comment', true);


            $db->commit();
        } catch (Album_Model_Exception $e) {
            $db->rollBack();
            $this->view->status = false;
            $this->view->error = $this->view->translate($e->getMessage());
            throw $e;
            return;
        } catch (Exception $e) {
            $db->rollBack();
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
            throw $e;
            return;
        }
    }

    public function readmeAction() {
        
    }

    public function supportAction() {

        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_support');
    }

    public function statisticsAction() {

        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitecredit_admin_main', array(), 'sitecredit_admin_main_statistics');

        $this->view->searchAjax = $this->_getParam('searchAjax', false);
        $creditTable = Engine_Api::_()->getDbtable('credits', 'sitecredit');
        $creditTableName = $creditTable->info('name');

        $select = $creditTable->select();
        $chunk = Zend_Date::DAY;
        $period = Zend_Date::WEEK;
        $start = time();

        // Make start fit to period?
        $startObject = new Zend_Date($start);

        $partMaps = $this->_periodMap[$period];
        foreach ($partMaps as $partType => $partValue) {
            $startObject->set($partValue, $partType);
        }
        $startObject->add(1, $chunk);
        $this->view->is_ajax = $this->_getParam('is_ajax', 0);
        $this->view->formFilterGraph = $formFilterGraph = new Sitecredit_Form_Admin_Statistics_FilterGraph();
        // get period and chunk object here.
        $getFormElements = $formFilterGraph->getElements();
        $firstClass = true;
        foreach ($getFormElements as $formKey => $formElement) {
            $label = $formFilterGraph->$formKey->getLabel();
            $formFilterGraph->$formKey->setDecorators(array('ViewHelper', array(array('label' => 'HtmlTag'), array('class' => $formKey, 'tag' => 'label', 'placement' => 'prepend', 'for' => $formKey)), array(array('div' => 'HtmlTag'), array('tag' => 'div', 'class' => $firstClass ? 'custom-divs-first' : 'custom-divs'))));
            $firstClass = false;
            $formFilterGraph->$formKey->setAttrib('class', 'label-field');
            if ($formFilterGraph->$formKey->type == 'submit') {
                continue;
            }
            $labels[$formKey] = $label;
        }
        $this->view->getFormLabels = json_encode($labels);
        $this->view->periodOption = json_encode($formFilterGraph->period->options);
        $this->view->chunkOption = json_encode($formFilterGraph->chunk->options);
        $this->view->periodOptionKey = json_encode(array_keys($formFilterGraph->period->options));
        $this->view->chunkOptionKey = json_encode(array_keys($formFilterGraph->chunk->options));
        $date_select = $select->from($creditTable, array('MIN(creation_date) as earliest_creation_date'));
        $earliest_creation_date = $select->query()
                ->fetchColumn();
        $this->view->prev_link = 1;
        $this->view->startObject = $startObject = strtotime($startObject);
        $this->view->earliest_ad_date = $earliest_creation_date = strtotime($earliest_creation_date);
        if ($earliest_creation_date > $startObject) {
            $this->view->prev_link = 0;
        }
    }

    public function chartDataAction() {
        // fetch and assign data for statistics
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        // Get params
        $type = $this->_getParam('type');
        $start = $this->_getParam('start');
        $offset = $this->_getParam('offset', 0);
        $mode = $this->_getParam('mode');
        $chunk = $this->_getParam('chunk');
        $period = $this->_getParam('period');
        $periodCount = $this->_getParam('periodCount', 1);
        // Validate chunk/period
        if (!$chunk || !in_array($chunk, $this->_periods)) {
            $chunk = Zend_Date::DAY;
        }
        if (!$period || !in_array($period, $this->_periods)) {
            $period = Zend_Date::MONTH;
        }
        if (array_search($chunk, $this->_periods) >= array_search($period, $this->_periods)) {
            die('whoops.');
            return;
        }

        // Validate start
        if ($start && !is_numeric($start)) {
            $start = strtotime($start);
        }
        if (!$start) {
            $start = time();
        }

        // Fixes issues with month view
        Zend_Date::setOptions(array(
            'extend_month' => true,
        ));

        // Make start fit to period?
        $startObject = new Zend_Date($start);

        $startObject->setTimezone(Engine_Api::_()->getApi('settings', 'core')->getSetting('core_locale_timezone', 'GMT'));

        $partMaps = $this->_periodMap[$period];
        foreach ($partMaps as $partType => $partValue) {
            $startObject->set($partValue, $partType);
        }

        // Do offset
        if ($offset != 0) {
            $startObject->add($offset, $period);
        }

        // Get end time
        $endObject = new Zend_Date($startObject->getTimestamp());
        $endObject->setTimezone(Engine_Api::_()->getApi('settings', 'core')->getSetting('core_locale_timezone', 'GMT'));
        $endObject->add($periodCount, $period);

        $end_tmstmp_obj = new Zend_Date(time());
        $end_tmstmp_obj->setTimezone(Engine_Api::_()->getApi('settings', 'core')->getSetting('core_locale_timezone', 'GMT'));
        $end_tmstmp = $end_tmstmp_obj->getTimestamp();
        if ($endObject->getTimestamp() < $end_tmstmp) {
            $end_tmstmp = $endObject->getTimestamp();
        }
        $end_tmstmp_object = new Zend_Date($end_tmstmp);
        $end_tmstmp_object->setTimezone(Engine_Api::_()->getApi('settings', 'core')->getSetting('core_locale_timezone', 'GMT'));

        // Get data
        $statsTable = Engine_Api::_()->getDbtable('credits', 'sitecredit');
        $statsName = $statsTable->info('name');

        $statsSelect = $statsTable->select();
// check for selected data
        $statsSelect
                ->from($statsName, array('SUM(credit_point) as credits', 'creation_date as timestamp'))
                ->where($statsName . '.creation_date >= ?', gmdate('Y-m-d H:i:s', $startObject->getTimestamp()))
                ->where($statsName . '.creation_date < ?', gmdate('Y-m-d H:i:s', $endObject->getTimestamp()));
        switch ($mode) {
            case "all" : switch ($type) {
                    case "activity_type" : $statsSelect->where($statsName . '.type = "activity_type"');
                        break;
                    case "upgrade_request" : $statsSelect->where($statsName . '.type = "upgrade_request" ');
                        break;
                    case "buy" : $statsSelect->where($statsName . '.type = "buy"');
                        break;
                    case "deduction" : $statsSelect->where($statsName . '.type = "deduction"');
                        break;
                    case "affiliate" : $statsSelect->where($statsName . '.type = "affiliate"');
                        break;
                    case "bonus" : $statsSelect->where($statsName . '.type = "bonus"');
                        break;
                    case "store" : $statsSelect->where($statsName . '.type = "store"');
                        break;
                    case "event" : $statsSelect->where($statsName . '.type = "event"');
                        break;
                    case "subscription" : $statsSelect->where($statsName . '.type = "subscription"');
                        break;
                    case "siteeventpaid_package": $statsSelect->where($statsName . '.type = "siteeventpaid_package"');
                        break;
                    case "sitestore_package": $statsSelect->where($statsName . '.type = "sitestore_package"');
                        break;
                    case "sitepage_package": $statsSelect->where($statsName . '.type = "sitepage_package"');
                        break;
                    case "sitereviewpaidlisting_package": $statsSelect->where($statsName . '.type = "sitereviewpaidlisting_package"');
                        break;
                    case "communityad_package": $statsSelect->where($statsName . '.type = "communityad_package"');
                        break;
                    case "sitegroup_package": $statsSelect->where($statsName . '.type = "sitegroup_package"');
                        break;
                } break;
            case "earned" : $statsSelect->where($statsName . '.type in ("bonus","deduction","affiliate","activity_type")');
                break;
            case "purchased" : $statsSelect->where($statsName . '.type = "buy" ');
                break;
            case "redeemed" : $statsSelect->where($statsName . '.type in ("store","event","upgrade_request","subscription","siteeventpaid_package","sitestore_package","sitepage_package","sitereviewpaidlisting_package","sitegroup_package") ');
                break;
        }
        $statsSelect->group("DATE_FORMAT(" . $statsName . " .creation_date, '%Y-%m-%d')")
                ->order($statsName . '.creation_date ASC')
                ->distinct(true);

        $rawData = $statsTable->fetchAll($statsSelect);

        // Now create data structure
        $currentObject = clone $startObject;
        $nextObject = clone $startObject;

        $data_credits = array();

        $cumulative_sent = 0;

        $previous_sent = 0;

        $oldtimestamp = $currentObject->getTimestamp();
        do {
            $nextObject->add(1, $chunk);
            $currentObjectTimestamp = $currentObject->getTimestamp();
            $data_credits[$currentObjectTimestamp] = $cumulative_sent;


            // Get everything that matches
            $currentPeriodCount_sent = 0;

            foreach ($rawData as $key => $rawDatum) {
                $timestamp = explode(" ", $rawDatum->timestamp);
                $rawDatumDate = strtotime($timestamp[0] . '00:00:00');
                if ($rawDatumDate <= $currentObjectTimestamp && $rawDatumDate > $oldtimestamp) {
                    $currentPeriodCount_sent = abs($rawDatum->credits);
                    $oldtimestamp = $rawDatumDate;
                }
            }


            $data_credits[$currentObjectTimestamp] = $currentPeriodCount_sent;

            $currentObject->add(1, $chunk);
        } while ($currentObject->getTimestamp() < $end_tmstmp);

        $data_credits_count = count($data_credits);


        $data = array();

        $merged_data_array = $data_credits;
        $data_count_max = $data_credits_count;
        $data = $data_credits;

        // Reprocess label
        $labelStrings = array();
        $labelDate = new Zend_Date();
        foreach ($data as $key => $value) {
            if ($key <= $end_tmstmp) {
                $labelDate->set($key);
                $labelStrings[] = $this->view->locale()->toDate($labelDate, array('size' => 'short'));
            } else {
                $labelDate->set($end_tmstmp);
                $labelStrings[] = date('n/j/y', $end_tmstmp);
            }
        }

        // Let's expand them by 1.1 just for some nice spacing
        $maxVal = max($merged_data_array);

        $minVal = min($merged_data_array);

        $minVal = floor($minVal * ($minVal < 0 ? 1.1 : (1 / 1.1)) / 10) * 10;
        $maxVal = ceil($maxVal * ($maxVal > 0 ? 1.1 : (1 / 1.1)) / 10) * 10;

        if ($maxVal <= 0)
            $maxVal = 1;

        // Remove some labels if there are too many
        $xlabelsteps = 1;

        if ($data_count_max > 10) {
            $xlabelsteps = ceil($data_count_max / 10);
        }

        // Remove some grid lines if there are too many
        $xsteps = 1;
        if ($data_count_max > 100) {
            $xsteps = ceil($data_count_max / 100);
        }
        $steps = null;
        if (empty($maxVal)) {
            $steps = 1;
        }

        // Create base chart
        require_once 'OFC/OFC_Chart.php';


        // Make x axis labels
        $x_axis_labels = new OFC_Elements_Axis_X_Label_Set();
        $x_axis_labels->set_steps($xlabelsteps);
        $x_axis_labels->set_labels($labelStrings);

        // Make x axis
        $labels = new OFC_Elements_Axis_X();
        $labels->set_labels($x_axis_labels);
        $labels->set_colour("#416b86");
        $labels->set_grid_colour("#dddddd");
        $labels->set_steps($xsteps);

        // Make y axis
        $yaxis = new OFC_Elements_Axis_Y();
        $yaxis->set_range($minVal, $maxVal, $steps);
        $yaxis->set_colour("#416b86");
        $yaxis->set_grid_colour("#dddddd");

        // Make title
        $translate = Zend_Registry::get('Zend_Translate');
        $titleStr = $translate->_('Credits Statistics');
        $title = new OFC_Elements_Title($titleStr . ' - ' . $this->view->locale()->toDateTime($startObject) . ' to ' . $this->view->locale()->toDateTime($end_tmstmp_object));
        $title->set_style("{font-size: 14px;font-weight: bold;margin-bottom: 10px; color: #777777;}");

        // Make full chart
        $chart = new OFC_Chart();
        $chart->set_bg_colour('#ffffff');

        $chart->set_x_axis($labels);
        $chart->add_y_axis($yaxis);

        $sent_width = '3';

        //$ctr_width = Engine_Api::_()->getApi('settings', 'core')->getSetting('communityad.graphctr.width', '3');
        $sent_color = '#3299CC';

        //$ctr_color = Engine_Api::_()->getApi('settings', 'core')->getSetting('communityad.graphctr.color', '#CD6839');
        $community_temp_file = 1; //Engine_Api::_()->getApi('settings', 'core')->getSetting('communityad.temp.file', 1);
        if (empty($community_temp_file)) {
            return;
        }

        $sent_str = $translate->_('Credits');

        $graph1 = new OFC_Charts_Line();
        $graph1->set_values(array_values($data_credits));
        $graph1->set_key($sent_str, '12');
        $graph1->set_width($sent_width);
        $graph1->set_dot_size('20');
        $graph1->set_colour($sent_color);
        $chart->add_element($graph1);


        $chart->set_title($title);

        // Send
        $this->getResponse()->setBody($chart->toPrettyString());
    }

}
