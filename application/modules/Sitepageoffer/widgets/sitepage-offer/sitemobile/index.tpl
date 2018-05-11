<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php $viewer_id = $this->viewer->getIdentity(); ?>
<?php if (!empty($viewer_id)): ?>
  <?php date_default_timezone_set($this->viewer->timezone); ?>
<?php endif; ?>

<?php if ($this->paginator->getTotalItemCount()): ?>
<?php if(!$this->autoContentLoad) : ?>
  <form id='filter_form_page' class='global_form_box' method='get' action='<?php echo $this->url(array(), 'sitepageoffer_browse', true) ?>' style='display: none;'>
    <input type="hidden" id="page" name="page"  value=""/>
  </form>
<div class="sm-content-list">	
  <ul data-role="listview" data-inset="false" id ="browsepageoffer_ul" >
    <?php endif;?>
    <?php foreach ($this->paginator as $sitepage): ?>
      <li <?php if (Engine_Api::_()->sitemobile()->isApp()): ?>data-icon="angle-right"<?php else : ?>data-icon="arrow-r"<?php endif;?>>
        <a href="<?php echo $sitepage->getHref(); ?>">
          <?php $sitepage_object = Engine_Api::_()->getItem('sitepage_page', $sitepage->page_id); ?>
          <?php $layout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepage.layoutcreate', 0);
          $tab_id = Engine_Api::_()->sitepage()->GetTabIdinfo('sitepageoffer.sitemobile-profile-sitepageoffers', $sitepage->page_id, $layout); ?>
          <?php if (!empty($sitepage->photo_id)): ?>
            <?php echo $this->itemPhoto($sitepage, 'thumb.icon'); ?>
          <?php else: ?>
            <?php echo "<img src='" . $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/nophoto_offer_thumb_icon.png' alt='' />"; ?>
          <?php endif; ?>
          <h3><?php echo $sitepage->title; ?></h3>
          <?php $item = Engine_Api::_()->getItem('sitepage_page', $sitepage->page_id); ?>
          <p><?php echo $this->translate("in "); ?>
            <b><?php echo $sitepage->sitepage_title ?></b>
          </p>
          <p>
            <?php echo $this->translate('End date:'); ?>
            <?php if ($sitepage->end_settings == 1): ?><?php echo $this->translate(gmdate('M d, Y', strtotime($sitepage->end_time))) ?></span><?php else: ?><?php echo $this->translate('Never Expires'); ?><?php endif; ?>
          </p>
          <?php $today = date("Y-m-d H:i:s"); ?>
          <?php $claim_value = Engine_Api::_()->getDbTable('claims', 'sitepageoffer')->getClaimValue($this->viewer_id, $sitepage->offer_id, $sitepage->page_id); ?>
          
           <?php if(false):?>
          <?php if ($sitepage->claim_count == -1 && ($sitepage->end_time > $today || $sitepage->end_settings == 0)): ?>
            <?php $show_offer_claim = 1; ?>
          <?php elseif ($sitepage->claim_count > 0 && ($sitepage->end_time > $today || $sitepage->end_settings == 0)): ?>
            <?php $show_offer_claim = 1; ?>
          <?php else: ?>
            <?php $show_offer_claim = 0; ?>
          <?php endif; ?>
          <p>
            <?php echo $sitepage->claimed . ' ' . $this->translate('claimed'); ?>
            <?php if ($sitepage->claim_count != -1): ?>
            -
            <?php echo $sitepage->claim_count . ' ' . $this->translate('claims left') ?>
            <?php endif; ?>
          </p>
          <?php endif; ?>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>
        <?php if(false):?>
        <p class="ui-li-aside"><strong>
            <?php if (!empty($show_offer_claim) && empty($claim_value)): ?>
              <?php
              $request = Zend_Controller_Front::getInstance()->getRequest();
              $urlO = $request->getRequestUri();
              $request_url = explode('/', $urlO);
              $param = 1;
              if (empty($request_url['2'])) {
                $param = 0;
              }
             // $return_url = (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) ? "https://" : "http://";
              $currentUrl = urlencode($urlO);
              ?>

              <?php if (!empty($this->viewer_id)): ?>
                <?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />' . $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'getoffer', 'id' => $sitepage->offer_id), $this->translate('Get Offer'), array('class' => 'smoothbox'));
                ?>
              <?php else: ?>
                <?php
                $offer_tabinformation = $this->url(array('action' => 'getoffer', 'id' => $sitepage->offer_id, 'param' => $param, 'request_url' => $request_url['1']), 'sitepageoffer_general') . "?" . "return_url=" . $return_url . $_SERVER['HTTP_HOST'] . $currentUrl;
                $title = $this->translate('Get Offer');
                echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />' . "<a href=$offer_tabinformation>$title</a>";
                ?>
              <?php endif; ?>
              <?php elseif (!empty($claim_value) && !empty($show_offer_claim) || ($sitepage->claim_count == 0 && $sitepage->end_time > $today && !empty($claim_value))): ?>
              <?php echo '<img src="'.$this->layout()->staticBaseUrl.'application/modules/Sitepageoffer/externals/images/invite.png" alt="" class="get_offer_icon" />' . $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'resendoffer', 'id' => $sitepage->offer_id), Zend_Registry::get('Zend_Translate')->_('Resend Offer'), array('class' => 'smoothbox')); ?>
    <?php else: ?>
                <b><?php echo $this->translate('Expired'); ?></b>
    <?php endif; ?>
          </strong></p>
          <?php endif;?>
          <?php endif; ?>
          </a>
      </li>
  <?php endforeach; ?>
<?php if(!$this->autoContentLoad) : ?>
  </ul>
</div>
<?php endif; ?>
<?php if( $this->paginator->count() > 1 && !Engine_Api::_()->sitemobile()->isApp()): ?>
		<?php echo $this->paginationControl($this->paginator, null, null, array(
			'query' => $this->formValues,
		)); ?>
	<?php endif; ?>

<?php else: ?>
  <div class="tip">
    <span>
 <?php echo $this->translate($this->message);?>
    </span>
  </div>
<?php endif; ?>
<script type='text/javascript'>        
<?php if (Engine_Api::_()->sitemobile()->isApp()) { ?>

  var browsePageOfferWidgetUrl = sm4.core.baseUrl + 'widget/index/mod/sitepageoffer/name/sitepage-offer';
         sm4.core.runonce.add(function() {    
              var activepage_id = sm4.activity.activityUpdateHandler.getIndexId();
              sm4.core.Module.core.activeParams[activepage_id] = {'currentPage' : '<?php echo sprintf('%d', $this->page) ?>', 'totalPages' : '<?php echo sprintf('%d', $this->totalPages) ?>', 'formValues' : null, 'contentUrl' : browsePageOfferWidgetUrl, 'activeRequest' : false, 'container' : 'browsepageoffer_ul' };
             
          });
          
  <?php } ?>           
</script>
