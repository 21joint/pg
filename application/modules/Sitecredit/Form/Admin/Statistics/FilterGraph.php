<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: FilterGraph.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Form_Admin_Statistics_FilterGraph extends Engine_Form {

  public function init() {
    $this
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

    $this
            ->setAttribs(array(
                'id' => 'filter_form',
                'class' => 'global_form_box',
            ));

    // Init mode
    $this->addElement('Select', 'mode', array(
        'label' => 'See',
        'multiOptions' => array(
            'all' => 'All',
            'earned' => 'Credit Earned',
            'purchased' => 'Credit Purchased',
            'redeemed' => 'Credit Redeemed'
        ),
        'onchange'=>'onModeChange()',
        'value' => 'all',
    ));

    $this->mode->clearDecorators()
            ->addDecorator('ViewHelper')
            ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
            ->addDecorator('HtmlTag', array('tag' => 'div'));

    $this->addElement('Select', 'type', array(
        'label' => 'Credit Type',
        'multiOptions' => array(
            'activity_type' => 'By Performing Activities',
            'upgrade_request' => 'Upgraded Member Level',
            'buy' => 'Purchased Credits',
            'deduction' => 'On Activity Deletion',
            'affiliate' => 'By Referral Signups',
            'bonus' => 'Bonus',
            'store' => 'Credits redeemed to purchase store products',
            'event' => 'Credits redeemed to purchase event tickets',
            'subscription' => 'Credits redeemed for Subscription',
            'siteeventpaid_package'=>'Credits redeemed for package purchase in Events',
            'sitestore_package'=>'Credits redeemed for package purchase in Stores',
            'sitepage_package'=>'Credits redeemed for package purchase in Directory / Pages',
            'sitereviewpaidlisting_package'=>'Credits redeemed for package purchase in Review & Ratings',
            'sitegroup_package'=>'Credits redeemed for package purchase in Groups / Communities',
            'communityad_package'=>'Credits redeemed for package purchase in Community Ad Plugin',
        ),
        'value' => 'all',
    ));

    $this->type->clearDecorators()
            ->addDecorator('ViewHelper')
            ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
            ->addDecorator('HtmlTag', array('tag' => 'div'));

    // Init period
    $this->addElement('Select', 'period', array(
        'label' => 'Duration',
        'multiOptions' => array(         
            Zend_Date::WEEK => 'This Week',
            Zend_Date::MONTH => 'This Month',
            Zend_Date::YEAR => 'This Year',
        ),
        'value' => 'month',
    ));
    $this->period->clearDecorators()
            ->addDecorator('ViewHelper')
            ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
            ->addDecorator('HtmlTag', array('tag' => 'div'));
    // Init chunk
    $this->addElement('Select', 'chunk', array(
        'label' => 'Time Summary',
        'multiOptions' => array(
            Zend_Date::DAY => 'By Day',
            Zend_Date::WEEK => 'By Week',
            Zend_Date::MONTH => 'By Month'
        ),
        'value' => 'day',
    ));
    $this->chunk->clearDecorators()
            ->addDecorator('ViewHelper')
            ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
            ->addDecorator('HtmlTag', array('tag' => 'div'));
    // Init submit
    $this->addElement('Button', 'submit', array(
        'label' => 'Filter',
        'type' => 'submit',
        'onclick' => 'return processStatisticsFilter($(this).getParent("form"))',
    ));
  }

}