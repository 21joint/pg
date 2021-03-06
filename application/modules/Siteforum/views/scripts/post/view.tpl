<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteforum
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: view.tpl 6590 2015-12-23 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<a class="pabsolute" id="siteforum_profile_members_anchor"></a>

<script type="text/javascript">
    var siteforumMemberPage = Number('<?php echo $this->paginator->getCurrentPageNumber() ?>');
</script>

<a id="like_members_profile" style="position:absolute;"></a>

<div class="seaocore_members_popup siteforum_guest_popup">
    <div class="top">
        <div class="heading"><?php echo $this->translate('Members who thanked to this user.'); ?></div>
        <div class="seaocore_popup_options">

            <?php if ($this->totalMembers > 0): ?>
                <div class="seaocore_popup_options_left">
                    <?php //SHOW SEARCH BOX FOR SEARCHING MEMBERS  ?>
                    <form >
                        <input type="text" id='search_user' placeholder="<?php echo $this->translate('Search members'); ?>">
                        <input type="submit" hidden="true" onclick="return getUsers();">
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="seaocore_members_popup_content" id="lists_popup_content">
        <?php if ($this->totalMembers > 0): ?>
            <?php if ($this->paginator->getCurrentPageNumber() > 1): ?>
                <div class="seaocore_members_popup_paging">
                    <div id="user_like_members_previous" class="paginator_previous">
                        <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Previous'), array('onclick' => 'paginateEventMembers(siteforumMemberPage - 1)', 'style' => '')); ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($this->totalMembers > 0) { ?>
            <?php
            foreach ($this->paginator as $member):
                if (!empty($member->resource_id)) {
                    $memberInfo = $member;
                    $member = $this->item('user', $memberInfo->user_id);
                }
                ?>
                <div class="item_member_list siteforum_popup_member_list">
                    <div class="item_member_thumb">
                        <?php echo $this->htmlLink($member->getHref(), $this->itemPhoto($member, 'thumb.icon'), array()) ?>
                    </div>
                    <div class="siteforum_popup_member_list_options fright">

                        <?php
                        if ($this->viewer()->getIdentity()) {
                            echo $this->userFriendshipAjax($this->user($member->getIdentity()));
                        }
                        ?>

                        <?php
                        //SHOW MESSAGE LINK 
                        $item = Engine_Api::_()->getItem('user', $member->getIdentity());
                        if (Engine_Api::_()->seaocore()->canSendUserMessage($item)) :
                            ?>
                            <span class="siteforum_link_wrap f_small ">
                                <i class="siteforum_icon mright5" style="background-image: url(<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Messages/externals/images/send.png);"></i>
                                <a href="<?php echo Zend_Controller_Front::getInstance()->getBaseUrl(); ?>/messages/compose/to/<?php echo $member->getIdentity() ?>"  class="smoothbox"><?php echo $this->translate('Message'); ?> </a>
                            </span>
                        <?php endif; ?> 
                    </div>

                    <div class="item_member_details">
                        <div class="item_member_name" id="siteforum_profile_list_title_<?php echo $member->user_id ?>">
                            <?php echo $this->htmlLink($member->getHref(), $member->getTitle(), array('class' => 'item_photo seao_common_add_tooltip_link', 'title' => $member->getTitle(), 'target' => '_parent', 'rel' => 'user' . ' ' . $member->user_id)); ?>
                        </div> 
                    </div>
                </div>
            <?php endforeach; ?>
        <?php } else { ?>
            <div class='tip m10'>
                <span>
                    <?php echo $this->translate('No members were found.'); ?>
                </span>
            </div>
        <?php } ?>

        <?php if ($this->totalMembers > 1): ?>
            <?php if ($this->paginator->getCurrentPageNumber() < $this->paginator->count()): ?>
                <div class="seaocore_members_popup_paging">   
                    <div id="user_siteforum_members_next" class="paginator_next">
                        <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Next'), array('onclick' => 'paginateEventMembers(siteforumMemberPage + 1)')); ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<div class="seaocore_members_popup_bottom siteforum_guest_popup_bottom">
    <button onclick="SmoothboxSEAO.close();"><?php echo $this->translate("Close") ?></button>
</div>	

<script type="text/javascript">
    var getUsers = function () {
        (new Request.HTML({
            'format': 'html',
            'url': '<?php echo $this->url(array('module' => 'siteforum', 'controller' => 'post', 'action' => 'get-user'), 'default', true); ?>',
            'data': {
                'format': 'html',
                'username': $('search_user').value,
                'post_id': <?php echo $this->topic_post_id; ?>
            },
            'onSuccess': function (responseTree, responseElements, responseHTML, responseJavaScript)
            {
                $('lists_popup_content').innerHTML = responseHTML;
            }
        })).send();

        return false;
    }
</script>    