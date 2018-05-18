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
class Sitecredit_Widget_SendToFriendController extends Engine_Content_Widget_Abstract
{
    public function indexAction() {   
        
        $viewer = Engine_Api::_()->user()->getViewer();

        $subject_id= Engine_Api::_()->core()->getSubject()->getIdentity();

        if (!$viewer)
            return $this->setNoRender();
        if (!Engine_Api::_()->authorization()->isAllowed('sitecredit_credit', $viewer, 'send'))
            return $this->setNoRender();
        $this->view->sendCredits = $this->_getParam('sendCredits', 0);
        $this->view->form=$form = new Sitecredit_Form_Sendtofriend();

        if ($subject_id!=$viewer->getIdentity()) {
            $form->setDescription('You can send '.$GLOBALS['credits'].' to :');
            $form->removeElement('friend_name');
            $form->addElement('Dummy', 'ad_header2', array(
            'label' => ' '.Engine_Api::_()->user()->getUser($subject_id)->getTitle(),
            'order' => 0,
            ));
            $form->ad_header2->getDecorator('Label')->setOption('style', 'font-weight:bolder;width:100%;font-size: larger;');
            $form->friend_id->setValue($subject_id);

        }
        
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $params = $request->getParams() ;
        
        $sitecreditSendToFriend = Zend_Registry::isRegistered('sitecreditSendToFriend') ? Zend_Registry::get('sitecreditSendToFriend') : null;
        if(empty($sitecreditSendToFriend))
            return $this->setNoRender();

        if( isset($params['sendcredit']) && $form->isValid($request->getParams()) ) {

            $value = $form->getValues(); 
            $param=array();

            if (empty($_POST['friend_id'])) {
                $error = $this->view->translate('Please enter friend\'s name  - it is required.');
                $error = Zend_Registry::get('Zend_Translate')->_($error);
                $form->getDecorator('errors')->setOption('escape', false);
                $form->addError($error);
                return;
            }
            if (empty($_POST['credit_point']) || (!empty($_POST['credit_point']) && $_POST['credit_point'] < 1 )) {
                $error = $this->view->translate('Please enter valid '.$GLOBALS['credit'].' values');
                $error = Zend_Registry::get('Zend_Translate')->_($error);
                $form->getDecorator('errors')->setOption('escape', false);
                $form->addError($error);
                return;
            }

            $param['user_id']=$viewer->getIdentity();
            $param['basedon']= 0;
          
            $currentCredits = Engine_Api::_()->getDbtable('credits', 'sitecredit')->Credits($param)->credit;
            $this->view->status=false;
            if ($value['credit_point']<= $currentCredits) {
                $values = array_merge($value, array('user_id' => $viewer->getIdentity(),));
                $values['type_id']=$value['friend_id'];
                $db = Zend_Db_Table::getDefaultAdapter();
                $db->beginTransaction();
                try {
                    $values['type']='sent_to_friend';
                    Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($values);

                    $values['type_id']= $viewer->getIdentity();
                    $values['user_id']= $value['friend_id']; 
                    $values['type']='received_from_friend';
                    Engine_Api::_()->getDbtable('credits','sitecredit')->insertCredit($values);
                    $user=Engine_Api::_()->user()->getUser($values['user_id']); 
                    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
                    $routeStartP = Engine_Api::_()->getApi('settings', 'core')->getSetting('credit.manifestUrlP', "credits");
                    $URL = $view->baseUrl()."/" . $routeStartP;
                    $link = '<a href="' . $URL . '" target="_parent">'.$value['credit_point'].'</a>';
                    Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($user,$viewer,$viewer, "Sitecredit_received_from_friend", array(
                            'credit_value' => $link,
                        ));
                    Engine_Api::_()->getApi('core', 'sitecredit')->sendEmailToUser($values,"credits_sent_to_friend");
                    $db->commit();
                }catch (Exception $e) {
                    $db->rollBack();
                    throw $e;
                }
                header("Refresh:0");
            }else {
                $form->addError('You dont have enough '.$GLOBALS['credits'].'.');
            }

        }

    }

}
