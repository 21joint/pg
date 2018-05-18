<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Controller.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_Widget_NextTargetController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {
    
       $viewer = Engine_Api::_()->user()->getViewer();
        if (!$viewer) {
            return  $this->setNoRender();
        }

        $this->view->viewerid=$viewer->getIdentity();

        $sitecreditNextTarget = Zend_Registry::isRegistered('sitecreditNextTarget') ? Zend_Registry::get('sitecreditNextTarget') : null;
        $settings=Engine_Api::_()->getApi('settings', 'core');
        if (empty($sitecreditNextTarget))
            return $this->setNoRender();

        if ($this->_getParam('targetType', 'badge')=='badge') {
            if(!$settings->getSetting('sitecredit.badge.enable', 0))
            return $this->setNoRender();
        
            $badgeRow=Engine_Api::_()->getDbtable('Badges','sitecredit')->fetchRow();
            if (empty($badgeRow)) {

                return $this->setNoRender();
            }      

            $this->view->target="badge";
            $param['user_id']=$viewer->getIdentity();

            $param['basedon']= $settings->getSetting('credit.ranking', 0);
            $param['count']= 1; 

            $credits = Engine_Api::_()->getDbtable('credits','sitecredit')->Credits($param);
            if (empty($credits->credit)) {
                return $this->setNoRender(); 
            }
            $this->view->credits= $credits->credit;
            $this->view->creditNeeded;
            $this->view->result=$result = Engine_Api::_()->getDbtable('Badges','sitecredit')->getBadge($param);
            $this->view->nextRank=$nextBadge=Engine_Api::_()->getDbtable('Badges','sitecredit')->getNextBadge($param);

          }elseif ($this->_getParam('targetType', 'badge')=='link') {
            if( $settings->getSetting('user.signup.inviteonly') == 1 ) {
                if(!$viewer->isAdmin()) {
                   return $this->setNoRender();             
                }                           
            } 
            $this->view->showReffralLink=true;
            if( $settings->getSetting('user.signup.inviteonly') != 0 ) {
               $this->view->showReffralLink=false;                          
            }
              $this->view->target="link";
              $this->view->AffiliateLinkPermission=$AffiliateLinkPermission=$settings->getSetting('sitecredit.allow.affiliate.link',1);
              if (!empty($AffiliateLinkPermission)) {

                $permissiontable=Engine_Api::_()->getDbtable('permissions', 'authorization');
                $select=$permissiontable->select()->where('level_id=?',$viewer->level_id)->where('type=?','sitecredit_credit')->where('name=?','link_credit');

                $this->view->link_credit=$credits=$permissiontable->fetchRow($select)->params;
                $userTable=Engine_Api::_()->getDbtable('users','user');
                $linkTable=Engine_Api::_()->getDbtable('validities','sitecredit');

                $select = $userTable->select()->from($userTable->info('name'), array('salt','email'))->where('user_id = ?', $viewer->getIdentity());
                $data=$userTable->fetchRow($select);

                $select = $linkTable->select()->from($linkTable->info('name'), array('Affiliate_link'))->where('user_id = ?', $viewer->getIdentity());
                $referral_link=$linkTable->fetchRow($select);

                $schema = 'http://';
                if (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) {
                    $schema = 'https://';
                }
                $host = $_SERVER['HTTP_HOST'];
                if (empty($referral_link->Affiliate_link)) {
                    $hash;
                    do {
                        $hash = substr(md5(rand(0, 999) . $data->salt . $data->email), 10, 7);
              
                        $select = $linkTable->select()->from($linkTable->info('name'), array('Affiliate_link'))->where('Affiliate_link = ?', $hash);
                        $unique_hash=$linkTable->fetchRow($select);
                    }while(!empty($unique_hash));

                    $linkTable->update(array('Affiliate_link'=>$hash), array('user_id = ?' => $viewer->getIdentity()));
                    $link = $schema . $host . $this->view->url(array('action' => 'signup', 'controller' => 'index', 'module' => 'sitecredit'), 'credit_general') . '?affiliate=' . $hash;

                    $this->view->link=$link;   
                }else {

                    $link = $schema . $host . $this->view->url(array('action' => 'signup', 'controller' => 'index', 'module' => 'sitecredit'), 'credit_general') . '?affiliate=' . $referral_link->Affiliate_link;

                    $this->view->link=$link;                  
                } 
            }else {
                return $this->setNoRender();
            }

        }else { 
            return $this->setNoRender();
        }
    }
}
