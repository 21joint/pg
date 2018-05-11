<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2017-02-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Plugin_Core extends Zend_Controller_Plugin_Abstract{

    public function onRenderLayoutDefault($event) { 
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $view->headTranslate(array("Redeem ".ucfirst($GLOBALS['credits']), "Want to redeem ".$GLOBALS['credits']." ? Enter ".$GLOBALS['credit']." value to avail redeemption.", "Use ".ucfirst($GLOBALS['credits']), "Redeem ".ucfirst($GLOBALS['credits'])));
        $front = Zend_Controller_Front::getInstance();
        $module = $front->getRequest()->getModuleName(); 
        $controller = $front->getRequest()->getControllerName();
        $action = $front->getRequest()->getActionName();
        $contentModuleEn=Array ( 0 => 'payment_package');

            if($module === 'ynpayment')
                $module = 'payment';      

            if (in_array($module.'_package', $contentModuleEn) || ($module == 'communityad' && in_array('package', $contentModuleEn))) {
                if (($controller == 'subscription' || $controller == 'payment')  && $action == 'gateway') {
        
                  $CreditModuleTable = Engine_Api::_()->getDbtable('modules', 'sitecredit');
                  $select=$CreditModuleTable->select()->where('name = ?','payment');
                  $creditModuleAllow=$CreditModuleTable->fetchRow($select);

                  if(!empty($creditModuleAllow->integrated)){
                      $creditSession = new Zend_Session_Namespace('payment_subscription_credit');

                      if (!empty($creditSession->paymentSubscriptionCreditDetail)) {
                        $creditSession->paymentSubscriptionCreditDetail = null;
                      } 
                     $baseUrl =  $view->layout()->staticBaseUrl;
                  //here we  include the core.js file, when payment page is comming. 
                    $view->headScript()->appendFile($baseUrl.'application/modules/Sitecredit/externals/scripts/core.js');
                    $script = <<<EOF
                    window.addEvent('domready', function()
                    {
                      if($('sitecredit_package_type')) {
                          $('sitecredit_package_type').value = '$module';
                    }
                    });
EOF;
                    $view->headScript()->appendScript($script);


                    if (Engine_Api::_()->getDbtable( "modules" , "core" )->isModuleEnabled("sitecoupon")){

                    $script = <<<EOF
                    window.addEvent('domready', function()
                    {
                        $('credit_code_button_id').addEvent('click',function(event) {
    $('code_boxid').style.display = '';
    $('code_boxid').style.visibility = 'visible';
    $('code_boxid').value='';
  $('sitecoupontest-translation').style.display = 'block';
  $('code_button_id').style.display = '';
  $('code_button_id').style.visibility = 'visible';
  $('addTextBox').style.display = 'block';
  $('coupon_loding_image').style.display ='none';
    
    if($('meassge_show')){
      $('meassge_show').innerHTML = '';
    }

  });
                    });
EOF;
                      $view->headScript()->appendScript($script);
                    }
                
                  }

                }
            }
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        $GLOBALS['credit']=Engine_Api::_()->getApi('settings', 'core')->getSetting('credit.alternate', "credit");
        $GLOBALS['credits']=$GLOBALS['credit'].'s';
        $credittypeArray=array(
            'activity_type' => 'By Performing Activities',
            'upgrade_request' => 'Upgraded Member Level',
            'deduction' => 'On Activity Deletion',
            'affiliate' => 'By Referral Signups',
            'bonus' => 'Bonus');
        $viewer = Engine_Api::_()->user()->getViewer();
        if (Engine_Api::_()->authorization()->isAllowed('sitecredit_credit', $viewer, 'buy'))
            $credittypeArray['buy'] = 'Purchased '.ucfirst($GLOBALS['credits']);
        if (Engine_Api::_()->authorization()->isAllowed('sitecredit_credit', $viewer, 'send')){
            $credittypeArray['sent_to_friend'] = 'Send to Friends';
            $credittypeArray['received_from_friend'] = 'Received From Friends';
        }
        $modules = Engine_Api::_()->getDbtable('modules', 'sitecredit')->getManageModulesList();
        foreach ($modules as $module) {
            if($module->flag=="product" && $module->name="sitestore" && !empty($module->integrated ))
                $credittypeArray['store']=ucfirst($GLOBALS['credits']).' redeemed to purchase store products';
            
            if($module->flag=="product" && $module->name="siteeventticket" && !empty($module->integrated))
                $credittypeArray['event'] = ucfirst($GLOBALS['credits']).' redeemed to purchase event tickets';
            
            if($module->flag=="package" && $module->name="siteeventpaid" && !empty($module->integrated))
                $credittypeArray['siteeventpaid_package']=ucfirst($GLOBALS['credits']).' redeemed for package purchase in Events';
            
            if($module->flag=="package" && $module->name="sitepage" && !empty($module->integrated))
                $credittypeArray['sitepage_package']=ucfirst($GLOBALS['credits']).' redeemed for package purchase in Directory / Pages';   
            
            if($module->flag=="package" && $module->name="sitereviewpaidlisting" && !empty($module->integrated))
                $credittypeArray['sitereviewpaidlisting_package']=ucfirst($GLOBALS['credits']).' redeemed for package purchase in Review & Ratings';  
            
            if($module->flag=="package" && $module->name="sitegroup" && !empty($module->integrated))
                $credittypeArray['sitegroup_package']=ucfirst($GLOBALS['credits']).' redeemed for package purchase in Groups / Communities';
            
            if($module->flag=="package" && $module->name="payment" && !empty($module->integrated))
                $credittypeArray['subscription']=ucfirst($GLOBALS['credits']).' redeemed for Subscription';
            
            if($module->flag=="package" && $module->name="sitestore" && !empty($module->integrated))
                $credittypeArray['sitestore_package']=ucfirst($GLOBALS['credits']).' redeemed for package purchase in Stores';
            
            if($module->flag=="package" && $module->name="communityad" && !empty($module->integrated))
                $credittypeArray['communityad_package'] = ucfirst($GLOBALS['credits']).' redeemed for package purchase in Community Ads Plugin';
                      
        }
        $GLOBALS['sitecredit_creditType'] = $credittypeArray;
    }
    
    public function onItemDeleteBefore($event) { 

    $item = $event->getPayload();
    $viewer = Engine_Api::_()->user()->getViewer()->getIdentity();
    if (empty($viewer))
        return;

    if (!empty($item->action_id)) {
        $activity= Engine_Api::_()->getItem('activity_action', $item->action_id);
        $param= array();
        $param['user_id']=$activity->subject_id;
        
        $tableName = Engine_Api::_()->getDbTable('users', 'user');
        $select = $tableName->select()->where("user_id=?",$param['user_id']);
        $userObjects = $tableName->fetchRow($select);
        $member_level=$userObjects->level_id;
        if (count($activity)!=0) {

            $table=Engine_Api::_()->getDbtable('activitycredits','sitecredit');
            $select = $table->select()->where('activity_type= ?',$activity->type)->where('member_level=?',$member_level)->where('status=?','enabled');
            $activitycredit= $table->fetchRow($select);

            if ($viewer == $param['user_id']) {
                $param['reason']='activity deleted';
            }else {
                $param['reason']='activity deleted by admin';
            }
            if (!empty($activitycredit->deduction)) {
                $param['credit_point']= -$activitycredit->deduction;
                $param['type_id']=$activitycredit->activitycredit_id;
                $param['type']='deduction';
                Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($param);
            }
        }
    } else {

      $creditTable=Engine_Api::_()->getDbtable('credits','sitecredit');  
      if($item->getType()=="core_comment") {

        $param['user_id']=$item->poster_id;
        $select=$creditTable->select()->where('type = ?','activity_type')->where('user_id=?',$param['user_id'])->where('resource_id =?',$item->resource_id)->where('resource_type = ?',$item->resource_type)->where('activity_type = ?',$item->comment_id);
      } else {
        if(isset($item['owner_id'])){
          $param['user_id']=$item->owner_id;
          $select=$creditTable->select()->where('type = ?','activity_type')->where('user_id=?',$param['user_id'])->where('resource_id =?',$item->getIdentity())->where('resource_type = ?',$item->getType());
        } else return;
        
      }

      $creditRow=$creditTable->fetchRow($select);

      if(!empty($creditRow)) {
        if($viewer == $param['user_id']) {
          $param['reason']='activity deleted';
        } else {
         $param['reason']='activity deleted by admin';
       }
       $activitycreditTable=Engine_Api::_()->getDbtable('activitycredits','sitecredit');
       $select = $activitycreditTable->select()->where('activitycredit_id= ?',$creditRow->type_id)->where('status=?','enabled');

       $activityCreditRow=$activitycreditTable->fetchRow($select);

       if(!empty($activityCreditRow->deduction)) {
        $param['credit_point']= -$activityCreditRow->deduction;
        $param['type_id']=$activityCreditRow->activitycredit_id;
        $param['type']='deduction';
        Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($param);
      }
    }
  }
}

public function onActivityActionCreateAfter($event) {

  $item =$event->getPayload();
  $owner_id = $item->getOwner()->getIdentity();
    if(empty($owner_id))
    return;
  $member_level=$item->getOwner()->level_id; 

// get current date
  $raw = date('Y-m-d');

//credit limit set by admin for each member level
  $permissiontable=Engine_Api::_()->getDbtable('permissions', 'authorization');
  $select=$permissiontable->select()->where('level_id=?',$member_level)
  ->where('type=?','sitecredit_credit')->where('name=?','max_perday');

  $creditpointMaxPerDAy=$permissiontable->fetchRow($select)->params;
  if(empty($creditpointMaxPerDAy)) {
    $creditpointMaxPerDAy=0;
  }

  $tableactivitycredit=Engine_Api::_()->getDbtable('activitycredits','sitecredit');
  $select = $tableactivitycredit->select()->where('activity_type= ?',$item->type)
  ->where('member_level=?',$member_level)->where('status=?','enabled');

  $activitycredit= $tableactivitycredit->fetchRow($select);
  $first=0; $other=0; $limit=0; $id;

  if(!empty($activitycredit)) {
    $checkValidity=Engine_Api::_()->getDbtable('credits','sitecredit')->validityCheck();
    $validityTable=Engine_Api::_()->getDbTable('validities','sitecredit');
    $select=$validityTable->select()->where('user_id=?',$owner_id);
    $validityuser= $validityTable->fetchRow($select);

    if(empty($validityuser))
    {  
      Engine_Api::_()->getDbtable('validities','sitecredit')->insertvalidity();
    } else {
      $validityDate = date('Y-m-d', strtotime("+$checkValidity months", strtotime($validityuser->start_date)));
      while($validityDate < date('Y-m-d h:m:s')) {
        Engine_Api::_()->getDbtable('validities','sitecredit')->updateValidity($validityDate);
        $validityuser= $validityTable->fetchRow($select);
        $validityDate = date('Y-m-d', strtotime("+$checkValidity months", strtotime($validityuser->start_date)));
      }
    }

    $id=$activitycredit->activitycredit_id;
    $first =$activitycredit->credit_point_first;
    $other =$activitycredit->credit_point_other;
    $limit =$activitycredit->limit_per_day;
    if(empty($first)) {
      return;
    }
    $activity;

      $item_type=explode('_', $item->type);
      $item_owner=explode('_', $item['params']['owner']);
      if($item_type[0]=="comment") {
        if($item_owner[0] == 'user' && $item_owner[1] == $owner_id){
        return;
      }

      $commentTable=Engine_Api::_()->getDbtable('comments','core');
      $selectComment=$commentTable->select()->order("creation_date DESC");
      $activity=$commentTable->fetchRow($selectComment)->comment_id;
    }

    $param=array(
      'reason' => 'activity created',
      'user_id'=>$owner_id,
      'type_id'=>$id,
      'type'=>'activity_type',
      'resource_type'=>$item->getObject()->getType(),
      'resource_id'=>$item->getObject()->getIdentity(),
      'activity_type'=>$activity
      );

    $tablecredits=Engine_Api::_()->getDbtable('credits','sitecredit');
    //entry for particular activity_type
    $select = $tablecredits->select()->where('type = ?','activity_type')->where('type_id =?',$id)->where('user_id=?',$owner_id)->where("DATE_FORMAT(`creation_date`, '%Y-%m-%d')=?",$raw);

    $creditentry= $tablecredits->fetchAll($select);  
    $creditentryarray= $creditentry->toArray();    
    
    //entry for particular day
    $selecta = $tablecredits->select()->where('type = ?','activity_type')->where('user_id=?',$owner_id)->where("DATE_FORMAT(`creation_date`, '%Y-%m-%d')=?",$raw);

    $creditlimitperday=$tablecredits->fetchAll($selecta);
    $creditlimitperdayarray=$creditlimitperday->toArray();


    if(!empty($creditentryarray)) {
      if(empty($other)) return;
      if(empty($limit) && empty($creditpointMaxPerDAy)) {
        $param['credit_point']= $other;
        Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($param);
      } else {

        $expectedCreditLimit=0;
        if(!empty($creditlimitperdayarray)) {
          $creditlimitTotal=0;
          foreach ($creditlimitperday as $key => $value) {
            $creditlimitTotal=$creditlimitTotal+$value->credit_point;
          }
          $expectedCreditLimit=$creditlimitTotal+$other;  
        }
        $credittotal=0;
        foreach ($creditentry as $key => $value) {
          $credittotal=$credittotal+$value->credit_point;
        }

        $expectedcreditpoint=$credittotal+$other;
        if(empty($creditpointMaxPerDAy) && !empty($limit)) {
          if($expectedcreditpoint<=$limit) {
            $param['credit_point']= $other;
            Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($param);
          }

        } else if(empty($limit) && !empty($creditpointMaxPerDAy)) {
          if($expectedCreditLimit<=$creditpointMaxPerDAy){
            $param['credit_point']= $other;
            Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($param);
          }
        } else {
          if(($expectedcreditpoint<= $limit) && ($expectedCreditLimit<=$creditpointMaxPerDAy)) {
            $param['credit_point']= $other;
            Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($param); 
          }
        }
      }
    } else {
      $param['credit_point']= $first;
      Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($param); 
    }  

  }
}

public function onUserCreateAfter($event) {

  $payload = $event->getPayload();

  $this->Affiliate_session = new Zend_Session_Namespace('Affiliate_Link_Sitecredit');

  $this->Affiliate_session->user_id;
  $this->Affiliate_session->hash;
  $AffiliateLinkPermission=Engine_Api::_()->getApi('settings', 'core')->getSetting('sitecredit.allow.affiliate.link',1);
  if(!empty($this->Affiliate_session->user_id) && !empty($AffiliateLinkPermission)) {
    $userTable=Engine_Api::_()->getDbtable('users','user');
    $select=$userTable->select()->where('user_id = ?',$this->Affiliate_session->user_id);
    $user=$userTable->fetchRow($select);

    $permissiontable=Engine_Api::_()->getDbtable('permissions', 'authorization');
    $select=$permissiontable->select()->where('level_id=?',$user->level_id)
    ->where('type=?','sitecredit_credit')->where('name=?','link_credit');

    $credits=$permissiontable->fetchRow($select)->params;
    if(!empty($credits)) {
      $creditTable=Engine_Api::_()->getDbtable('credits','sitecredit');
      $param=array();
      $param['type_id']=$payload->user_id;
      $param['credit_point']=$credits;
      $param['type']='affiliate';
      $param['user_id']=$this->Affiliate_session->user_id;
      $param['reason']='afiliate link';
      $param['creation_date']= new Zend_Db_Expr('NOW()');
      $row = $creditTable->createRow();
      $row->setFromArray($param);
      $row->save();
    }

  } 

}

  public function onUserDeleteBefore($event) {
    $payload = $event->getPayload();

    if ($payload instanceof User_Model_User) {

      $user_id = $payload->getIdentity();

      //GET CREDIT TABLE
      $creditTable = Engine_Api::_()->getDbtable('credits', 'sitecredit');
       
      $creditTable->delete(array('user_id =?' => $user_id)); 

      Engine_Api::_()->getDbtable('orders', 'sitecredit')->delete(array('user_id =?' => $user_id));

      Engine_Api::_()->getDbtable('bonuses', 'sitecredit')->delete(array('user_id = ?' => $user_id));

      Engine_Api::_()->getDbtable('validities', 'sitecredit')->delete(array('user_id = ?' => $user_id));

      Engine_Api::_()->getDbtable('upgraderequests', 'sitecredit')->delete(array('user_id = ?' => $user_id));

      Engine_Api::_()->getDbtable('transactions', 'sitecredit')->delete(array('user_id = ?' => $user_id));

      $creditTable->update(array('type_id' => 0), array('activity_type = ?' => 'received_from_friend', 'type_id =?' => $user_id));
      $creditTable->update(array('type_id' => 0), array('activity_type = ?' => 'sent_to_friend', 'type_id =?' => $user_id));
      $creditTable->update(array('type_id' => 0), array('activity_type = ?' => 'affiliate', 'type_id =?' => $user_id));
    }
  }


public function onUserLoginAfter($event) {
    $item =$event->getPayload();
  
    $param=array();
    $level_id=$item->level_id;
    $raw = date('Y-m-d');

    $permissiontable=Engine_Api::_()->getDbtable('permissions', 'authorization');
    $select=$permissiontable->select()->where('level_id=?',$level_id)
    ->where('type=?','sitecredit_credit')->where('name=?','max_perday');

    $creditpointMaxPerDAy=$permissiontable->fetchRow($select)->params;
    if (empty($creditpointMaxPerDAy)) {
      $creditpointMaxPerDAy=0;
    }
  
    $table=Engine_Api::_()->getDbtable('activitycredits','sitecredit');
    $select = $table->select()->where('activity_type= ?','login')
    ->where('member_level=?',$level_id)
    ->where('status=?','enabled');
    $activitycredit= $table->fetchRow($select);
    $first=0; $other=0; $limit=0; $id;
    if (!empty($activitycredit)) {
        $id=$activitycredit->activitycredit_id;
        $first =$activitycredit->credit_point_first;
        $other =$activitycredit->credit_point_other;
        $limit =$activitycredit->limit_per_day;
        if (empty($first)) {
            return;
        }
        $param['user_id']=$item->user_id;
        $param['reason']='activity created';
        $param['type_id']=$id;
        $param['type']='activity_type';
        $table=Engine_Api::_()->getDbtable('credits','sitecredit');
        $select = $table->select()->where('type = ?','activity_type')
                   ->where('type_id =?',$id)
                   ->where('user_id =?',$item->user_id)
                   ->where("DATE_FORMAT(`creation_date`, '%Y-%m-%d')=?",$raw);
        $creditentry= $table->fetchAll($select);  
        $creditentryarray=$creditentry->toArray(); 
    
        //entry for particular day
        $selecta = $table->select()->where('type = ?','activity_type')->where('user_id=?',$item->user_id)->where("DATE_FORMAT(`creation_date`, '%Y-%m-%d')=?",$raw);

        $creditlimitperday=$table->fetchAll($selecta);
        $creditlimitperdayarray=$creditlimitperday->toArray();


        if (!empty($creditentryarray)) {
          if(empty($other)) return;
            if(empty($limit) && empty($creditpointMaxPerDAy)) {
                $param['credit_point']= $other;
                Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($param);
            }else {

                $expectedCreditLimit=0;
                if (!empty($creditlimitperdayarray)) {
                    $creditlimitTotal=0;
                    foreach ($creditlimitperday as $key => $value) {
                      $creditlimitTotal=$creditlimitTotal+$value->credit_point;
                    }
                    $expectedCreditLimit=$creditlimitTotal+$other;  
                }
                $credittotal=0;
                foreach ($creditentry as $key => $value) {
                    $credittotal=$credittotal+$value->credit_point;
                }

                $expectedcreditpoint=$credittotal+$other;
                if (empty($creditpointMaxPerDAy) && !empty($limit)) {
                    if($expectedcreditpoint<=$limit) {
                      $param['credit_point']= $other;
                      Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($param);
                    }
                }else if(empty($limit) && !empty($creditpointMaxPerDAy)) {
                    if ($expectedCreditLimit<=$creditpointMaxPerDAy){
                        $param['credit_point']= $other;
                        Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($param);
                    }
                } else {
                    if (($expectedcreditpoint<= $limit) && ($expectedCreditLimit<=$creditpointMaxPerDAy)) {
                        $param['credit_point']= $other;
                      Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($param); 
                    }
                }
            } 
        } else {
            $param['credit_point']= $first;
            Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($param); 
        }  

    }
}

}