<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageintegration
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.php 2012-31-12 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
$Params = Engine_Api::_()->sitepageintegration()->integrationParams($this->resource_type, $this->listingtype_id, $this->page_id);
if (isset($Params['singular']))
    $singular = $Params['singular'];
if (isset($Params['plular']))
    $plular = $Params['plular'];
if (isset($Params['singular_small']))
    $singular_small = $Params['singular_small'];
if (isset($Params['URL']))
    $URL = $Params['URL'];
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sitepage/externals/styles/style_sitepage_dashboard.css'); ?>

<script type="text/javascript">

    function check_submit() {
        if (document.getElementById('resource_id').value == 0) {
            return false;
        }
        else {
            return true;
        }
    }

    var managecontent = function (content_id, url, resource_type) {
        var childnode = $(content_id + '_page_main');
        childnode.destroy();
        en4.core.request.send(new Request.JSON({
            url: url,
            data: {
                content_id: content_id,
                resource_type: resource_type
            },
            onSuccess: function (responseJSON) {
            }
        }))
    };

    var viewer_id = '<?php echo $this->viewer_id; ?>';
    //var url = '<?php //echo $this->url(array(), 'sitepage_general', true)   ?>';
</script>

<?php //if (empty($this->is_ajax)) :  ?>
<?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/payment_navigation_views.tpl'; ?>
<div class="layout_middle">
    <?php include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/edit_tabs.tpl'; ?>
    <div class="sitepage_edit_content">
        <div class="sitepage_edit_header">
            <a href='<?php echo $this->url(array('page_url' => Engine_Api::_()->sitepage()->getPageUrl($this->page_id)), 'sitepage_entry_view', true) ?>'>
                <?php echo $this->translate('View Page'); ?>
            </a>
            <h3><?php echo $this->translate('Dashboard: ') . $this->sitepage->title; ?></h3>
        </div>
        <div id="show_tab_content">
            <?php //endif;  ?>
            <div class="sitepage_form">
                <div>
                    <div>
                        <div class="sitepage_manageadmins">
                            <?php $addableCheck = Engine_Api::_()->getApi('settings', 'core')->getSetting('addable.integration'); ?>
                            <?php if ($this->resource_type == 'sitereview_listing'): ?>
                                <?php
                                $listingType = Engine_Api::_()->getItem('sitereview_listingtype', $this->listingtype_id);
                                $titleSinUc = ucfirst($listingType->title_singular);
                                ?>
                                <h3> <?php echo $this->translate("Add $titleSinUc Listings"); ?> </h3>
                                <?php if ($addableCheck == 1): ?>
                                    <p class="form-description"><?php echo $this->translate("Here, you can add various %s Listings to be associated with and displayed on your Page by using the auto-suggest below. From the auto-suggest, you can choose to add the %s Listings created by you. Listings added by you here will display in a tab on your Page Profile.<br />Below, you can also remove listings added by you by using the link for each.", $titleSinUc, $titleSinUc) ?></p>
                                <?php elseif ($addableCheck == 2): ?>
                                    <p class="form-description"><?php echo $this->translate("Here, you can add various %s Listings to be associated with and displayed on your Page by using the auto-suggest below. From the auto-suggest, you can choose to add the %s Listings created by this Page’s Admins. Listings added by you here will display in a tab on your Page Profile.<br />Below, you can also remove listings added by you by using the link for each.", $titleSinUc, $titleSinUc) ?></p>
                                <?php else: ?>
                                    <p class="form-description"><?php echo $this->translate("Here, you can add various %s Listings to be associated with and displayed on your Page by using the auto-suggest below. From the auto-suggest, you can choose to add the %s Listings from various %s Listings created on this site. Listings added by you here will display in a tab on your Page Profile.<br />Below, you can also remove listings added by you by using the link for each.", $titleSinUc, $titleSinUc, $titleSinUc) ?></p>
                                <?php endif; ?>
                            <?php else : ?>
                                <h3> <?php echo $this->translate('Add %s', $singular); ?> </h3>
                                <?php if ($addableCheck == 1): ?>
                                    <p class="form-description"><?php echo $this->translate('Here, you can add various %s to be associated with and displayed on your page by using the auto-suggest below. From the auto-suggest, you can choose to add the %s created by you. %s added by you here will display in a tab on your Page Profile.<br />Below, you can also remove %s added by you by using the link for each.', $singular, $singular, $singular, $singular_small) ?></p>
                                <?php elseif ($addableCheck == 2): ?>
                                    <p class="form-description"><?php echo $this->translate('Here, you can add various %s to be associated with and displayed on your page by using the auto-suggest below. From the auto-suggest, you can choose to add the %s created by this Page’s Admins. %s added by you here will display in a tab on your Page Profile.<br />Below, you can also remove %s added by you by using the link for each.', $singular, $singular, $singular, $singular_small) ?></p>
                                <?php else: ?>
                                    <p class="form-description"><?php echo $this->translate('Here, you can add various %s to be associated with and displayed on your page by using the auto-suggest below. From the auto-suggest, you can choose to add the %s from various %s created on this site. %s added by you here will display in a tab on your Page Profile.<br />Below, you can also remove %s added by you by using the link for each.', $singular, $singular, $singular, $singular, $singular_small) ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                            <br />
                            <?php foreach ($this->contentResults as $item): ?>
                                <div id='<?php echo $item->content_id ?>_page_main'  class="sitepageint_cont_list">
                                    <div class="sitepage_manageadmins_thumb" id='<?php echo $item->content_id ?>_pagethumb'>
                                        <?php if ($this->resource_type == 'document') : ?>
                                            <?php if (!empty($item->photo_id)): ?>
                                                <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal'), array('title' => $item->document_title)) ?>
                                            <?php elseif (!empty($item->thumbnail)): ?>
                                                <?php echo $this->htmlLink($item->getHref(), '<img src="' . $item->thumbnail . '" class="thumb_normal" />', array('title' => $item->document_title)) ?>
                                            <?php else: ?>
                                                <?php echo $this->htmlLink($item->getHref(), '<img src="' . $this->layout()->staticBaseUrl . 'application/modules/Document/externals/images/document_thumb.png" class="thumb_normal" />', array('title' => $item->document_title)) ?>
                                            <?php endif; ?>
                                        <?php elseif ($this->resource_type == 'sitetutorial_tutorial' || $this->resource_type == 'sitefaq_faq') : ?>
                                            <?php echo $this->htmlLink($item->getHref(), '<img src="' . $this->layout()->staticBaseUrl . 'application/modules/Sitepageintegration/externals/images/faq_tutorial.png" class="thumb_normal" />', array('title' => $item->getTitle())) ?>
                                        <?php else : ?>
                                            <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal'), array('title' => $item->getTitle(), 'target' => '_blank'));
                                            ?>
                                        <?php endif; ?>

                                    </div> 
                                    <div id='<?php echo $item->content_id ?>_page' class="sitepage_manageadmins_detail">
                                        <div class="sitepage_manageadmins_cancel">
                                            <?php
                                            $url = $this->url(array('action' => 'delete', 'page_id' => $this->page_id,
                                                'resource_type' => $this->resource_type), 'sitepageintegration_create', true);
                                            ?>
                                            <a href="javascript:void(0);" onclick="managecontent('<?php
                                            echo
                                            $item->content_id
                                            ?>', '<?php echo $url; ?>',
                                                            '<?php echo $this->resource_type ?>')"; ><?php echo $this->translate('Remove'); ?></a>
                                        </div>
                                        <span><?php echo $this->htmlLink($item->getHref(), $item->getTitle(), array('title' => $item->getTitle(), 'target' => '_blank'));
                                            ?></span>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                        </div>
<?php $item = $this->contentResults->getTotalItemCount(); ?>
                        <?php if (!empty($this->results)) : ?>
                            <input type="hidden" id='count_div' value='<?php echo $item ?>' />
                            <form id='video_selected' method='post' class="mtop10" action='<?php
                            echo
                            $this->url(array('action' => 'index', 'page_id' => $this->page_id, 'resource_type' =>
                                $this->resource_type), 'sitepageintegration_create')
                            ?>' onsubmit="return check_submit();">
                                <div class="fleft">
                                    <div>
                                                <?php if (!empty($this->message)): ?>
                                            <div class="tip">
                                                <span>
                                            <?php echo $this->message; ?>
                                                </span>
                                            </div>
                                            <?php endif; ?> 
                                        <div class="sitepageint_add_cont_input">
    <?php echo $this->translate("Start typing the name of listing and select from the auto-suggest drop-down below:") ?> <br />
                                            <input type="text" id="searchtext" name="searchtext" value="" />
                                            <input type="hidden" id="resource_id" name="resource_id" />
                                            <input type="hidden" id="owner_id" name="owner_id" />
                                            <input type="hidden" id="listingtype_id" name="listingtype_id" />
                                        </div>
                                        <div class="sitepageint_add_button">
                                            <button type="submit"  name="submit" ><?php echo $this->translate("Add"); ?></button>
                                        </div>	
                                    </div>
                                </div>
                            </form>
                            <?php else: ?>
                            <div class="tip">
                                <?php
                                if (!empty($URL)) :
                                    $click = '<a href="' . $URL . '">post one</a>';
                                endif;
                                ?>
                                <span>
                                    <?php if (!empty($this->createPrivacy) && !empty($click)): ?>
                                        <?php echo $this->translate('No %s which can be added to this Page have been posted yet. Be the first to %s!', $singular, $click); ?>
                                    <?php else: ?>
        <?php echo $this->translate('No %s which can be added to this Page have been posted yet.', $singular); ?>	
                            <?php endif; ?>
                                </span>
                            </div>
<?php endif; ?>
                    </div>
                </div>
            </div>
            <br />	
            <div id="show_tab_content_child">
            </div>
<?php //if (empty($this->is_ajax)) :  ?>
        </div>
    </div>
</div>
<?php //endif; ?> 	
<?php
$this->headScript()
        ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Observer.js')
        ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.js')
        ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Local.js')
        ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Request.js');
?>
<script type="text/javascript">
    en4.core.runonce.add(function () {
        var contentAutocomplete = new Autocompleter.Request.JSON('searchtext', '<?php echo $this->url(array('action' => 'manage-auto-suggest', 'page_id' => $this->page_id, 'resource_type' => $this->resource_type, 'listingtype_id' => $this->listingtype_id), 'sitepageintegration_create', true) ?>', {
            'postVar': 'text',
            'minLength': 1,
            'selectMode': 'pick',
            'autocompleteType': 'tag',
            'className': 'searchbox_autosuggest',
            'customChoices': true,
            'filterSubset': true,
            'multiple': false,
            'injectChoice': function (token) {
                var choice = new Element('li', {'class': 'autocompleter-choices1', 'html': token.photo, 'id': token.label, 'owner_id': token.owner_id, 'listingtype_id': token.listingtype_id});
                new Element('div', {'html': this.markQueryValue(token.label), 'class': 'autocompleter-choice1'}).inject(choice);
                this.addChoiceEvents(choice).inject(this.choices);
                choice.store('autocompleteChoice', token);
            }
        });

        contentAutocomplete.addEvent('onSelection', function (element, selected, value, input) {
            document.getElementById('resource_id').value = selected.retrieve('autocompleteChoice').id;
            document.getElementById('owner_id').value = selected.retrieve('autocompleteChoice').owner_id;
            document.getElementById('listingtype_id').value = selected.retrieve('autocompleteChoice').listingtype_id;
        });
    });
</script>