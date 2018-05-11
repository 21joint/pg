<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: sent.tpl 10180 2014-04-28 21:02:01Z lucas $
 * @author     Steve
 */
?>

<h2>
  <?php echo $this->translate(array("Invitation Sent","Invitations Sent",$this->emails_sent)) ?>
</h2>

<?php if (!empty($this->form->invalid_emails)): ?>
  <p><?php echo $this->translate('Invites were not sent to these email addresses because they do not appear to be valid:') ?></p>
  <ul>
    <?php foreach ($this->form->invalid_emails as $email): ?>
    <li><?php echo $email ?></li>
    <?php endforeach ?>
  </ul>
<?php endif ?>


<?php if (!empty($this->form->already_members)): ?>
  <p>
    <?php echo $this->translate('Some of the email addresses you provided belong to existing members:') ?>
    <?php foreach ($this->form->already_members as $user): ?>
      <?php echo $user->toString() ?>
    <?php endforeach ?>
  </p>
<?php endif ?>

<br />

<a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()' class = "buttonlink icon_back ">
        <?php echo $this->translate("OK, thanks!") ?></a>
