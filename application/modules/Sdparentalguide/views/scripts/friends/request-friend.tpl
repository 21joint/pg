<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: request-friend.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>

<script type="text/javascript">
  var userWidgetRequestSend = function(action, data)
  {
    var url;
    if( action == 'confirm' ) {
      url = '<?= $this->url(array('controller' => 'friends', 'action' => 'confirm'), 'user_extended', true) ?>';
    } else if( action == 'reject' ) {
      url = '<?= $this->url(array('controller' => 'friends', 'action' => 'reject'), 'user_extended', true) ?>';
    } else {
      return false;
    }

    (new Request.JSON({
      'url' : url,
      'data' : data,
      'onSuccess' : function(responseJSON) {
        if( !responseJSON.status ) {
          $('user-widget-request-' + data.notification_id).innerHTML = responseJSON.error;
        } else {
          $('user-widget-request-' + data.notification_id).innerHTML = responseJSON.message;
        }
      }
    })).send();
  }
</script>

<?php $params = $this->jsonInline(array(
  'user_id' => $this->notification->getSubject()->getIdentity(),
  'notification_id' => $this->notification->notification_id,
   $this->tokenName =>  $this->tokenValue,
  'format' => 'json'
));?>

<li id="user-widget-request-<?= $this->notification->notification_id ?>">
  <?= $this->itemPhoto($this->notification->getSubject(), 'thumb.icon') ?>
  <div>
    <div>
      <?= $this->translate('%1$s has sent you a friend request.', $this->htmlLink($this->notification->getSubject()->getHref(), $this->notification->getSubject()->getTitle())); ?>
    </div>
    <div>
      <button type="submit" onclick='userWidgetRequestSend("confirm", <?= $params ?>)'>
        <?= $this->translate('Add Friend');?>
      </button>
      <?= $this->translate('or');?>
      <a href="javascript:void(0);" onclick='userWidgetRequestSend("reject", <?= $params ?>)'>
        <?= $this->translate('ignore request');?>
      </a>
    </div>
  </div>
</li>
