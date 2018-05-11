<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: InviteController.php 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitecredit_InviteController extends Core_Controller_Action_Standard {

    public function indexAction() {

        // Check for users only
        if (!$this->_helper->requireUser()->isValid()) {
            return;
        }

        // Make form
        $this->view->form = $form = new Invite_Form_Invite();
        $form->setDescription('Invite your friends to join your social network with the attached Referral Link! You will earn '.$GLOBALS['credits'].' if your friends decide to sign up using the referral link.');
        $form->removeElement('friendship');
        $linkTable = Engine_Api::_()->getDbtable('validities', 'sitecredit');
        $inviteTable = Engine_Api::_()->getDbtable('invites', 'invite');
        $user = Engine_Api::_()->user()->getViewer();
        $db = $linkTable->getAdapter();
        $db->beginTransaction();
        $settings = Engine_Api::_()->getApi('settings', 'core');
        try {

            $select = $linkTable->select()->from($linkTable->info('name'), array('Affiliate_link'))->where('user_id = ?', $user->getIdentity());
            $referral_link = $linkTable->fetchRow($select);


            if (empty($referral_link->Affiliate_link)) {
                return;
            } else {

                $inviteUrl = $this->view->url(array('action' => 'signup', 'controller' => 'index', 'module' => 'sitecredit'), 'credit_general') . '?affiliate=' . $referral_link->Affiliate_link;
            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            if (APPLICATION_ENV == 'development') {
                throw $e;
            }
        }

        $form->message->setValue('You are being invited to join our social network with the attached referral link.');

        if (!$this->getRequest()->isPost()) {
            return;
        }

        if (!$form->isValid($this->getRequest()->getPost())) {
            return;
        }

        // Process
        $values = $form->getValues();
        try {

            $recipients = $values['recipients'];
            $message = @$values['message'];

            // Check recipients
            if (is_string($recipients)) {
                $recipients = preg_split("/[\s,]+/", $recipients);
            }
            if (is_array($recipients)) {
                $recipients = array_map('strtolower', array_unique(array_filter(array_map('trim', $recipients))));
            }
            if (!is_array($recipients) || empty($recipients)) {
                return 0;
            }

            // Only allow a certain number for now
            $max = $settings->getSetting('invite.max', 10);
            if (count($recipients) > $max) {
                $recipients = array_slice($recipients, 0, $max);
            }
            // Check message
            $message = trim($message);

            // Get tables
            $userTable = Engine_Api::_()->getItemTable('user');
            // Get ones that are already members
            $alreadyMembers = $userTable->fetchAll(array('email IN(?)' => $recipients));
            $alreadyMemberEmails = array();
            foreach ($alreadyMembers as $alreadyMember) {
                if (in_array(strtolower($alreadyMember->email), $recipients)) {
                    $alreadyMemberEmails[] = strtolower($alreadyMember->email);
                }
            }

            // Remove the ones that are already members
            $recipients = array_diff($recipients, $alreadyMemberEmails);
            $emailsSent = 0;

            // Send them invites
            foreach ($recipients as $recipient) {

                try {
                    if ($settings->getSetting('user.signup.inviteonly') == 1) {
                        if ($this->_helper->requireAdmin()->isValid()) {
                            do {
                                $inviteCode = substr(md5(rand(0, 999) . $recipient), 10, 7);
                            } while (null !== $inviteTable->fetchRow(array('code = ?' => $inviteCode)));

                            $row = $inviteTable->createRow();
                            $row->user_id = $user->getIdentity();
                            $row->recipient = $recipient;
                            $row->code = $inviteCode;
                            $row->timestamp = new Zend_Db_Expr('NOW()');
                            $row->message = $message;
                            $row->save();
                            $inviteUrl = $inviteUrl . '&code=' . $inviteCode . '&email=' . $recipient;
                        }
                    } else if ($settings->getSetting('user.signup.inviteonly') == 2) {
                        do {
                            $inviteCode = substr(md5(rand(0, 999) . $recipient), 10, 7);
                        } while (null !== $inviteTable->fetchRow(array('code = ?' => $inviteCode)));

                        $row = $inviteTable->createRow();
                        $row->user_id = $user->getIdentity();
                        $row->recipient = $recipient;
                        $row->code = $inviteCode;
                        $row->timestamp = new Zend_Db_Expr('NOW()');
                        $row->message = $message;
                        $row->save();
                        $inviteUrl = $inviteUrl . '&code=' . $inviteCode . '&email=' . $recipient;
                    }

                    $message = str_replace('%invite_url%', $inviteUrl, $message);

                    // Send mail
                    $mailType = 'invite';
                    $mailParams = array(
                        'host' => $_SERVER['HTTP_HOST'],
                        'email' => $recipient,
                        'date' => time(),
                        'sender_email' => $user->email,
                        'sender_title' => $user->getTitle(),
                        'sender_link' => $user->getHref(),
                        'sender_photo' => $user->getPhotoUrl('thumb.icon'),
                        'message' => $message,
                        'object_link' => $inviteUrl,
                    );

                    Engine_Api::_()->getApi('mail', 'core')->sendSystem(
                            $recipient, $mailType, $mailParams
                    );
                } catch (Exception $e) {
                    // Silence
                    if (APPLICATION_ENV == 'development') {
                        throw $e;
                    }
                    continue;
                }

                $emailsSent++;
            }

            $user->invites_used += $emailsSent;
            $user->save();
        } catch (Exception $e) {
            if (APPLICATION_ENV == 'development') {
                throw $e;
            }
        }

        //$this->view->alreadyMembers = $alreadyMembers;
        $this->view->emails_sent = $emailsSent;

        return $this->render('sent');
    }

}
