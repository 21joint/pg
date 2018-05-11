<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepageoffer
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: view.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php if (!Engine_Api::_()->sitemobile()->isApp()):?>
<?php
$breadcrumb = array(
    array("href"=>$this->sitepage->getHref(),"title"=>$this->sitepage->getTitle(),"icon"=>"arrow-r"),
    array("href"=>$this->sitepage->getHref(array('tab' => $this->tab_selected_id)),"title"=>"Offers","icon"=>"arrow-r"),
    array("title"=>$this->offer->getTitle(),"icon"=>"arrow-d","class" => "ui-btn-active ui-state-persist"));

echo $this->breadcrumb($breadcrumb);
?>
<?php endif;?>
<div class="ui-page-content sm-widget-block o_box">
  <div class="sm-ui-cont-head">
    <div class="off_img">
      <?php if (!empty($this->offer->photo_id)): ?>
        <?php echo $this->itemPhoto($this->offer, 'thumb.normal'); ?>
      <?php else: ?>
        <?php echo "<img src='" . $this->layout()->staticBaseUrl . "application/modules/Sitepageoffer/externals/images/nophoto_offer_thumb_normal.png' alt='' />" ?>
      <?php endif; ?>
    </div>	
    <div class="sm-ui-cont-cont-info">
      <div class="sm-ui-cont-author-name">
        <?php echo $this->offer->getTitle()?>
      </div>
      <?php if (Engine_Api::_()->sitemobile()->isApp()):?>
      <div class="sm-ui-cont-cont-date">
          <?php echo $this->translate('in '); ?><a href= '<?php echo $this->sitepage->getHref()?>' title = '<?php echo $this->sitepage->getTitle()?>'/><?php echo $this->sitepage->getTitle()?></a>
      </div>
      <?php endif; ?>
      <div class="sm-ui-cont-cont-date">
          <?php echo $this->translate('created on'); ?> <?php echo $this->timestamp($this->offer->creation_date) ?> - 
          <?php echo $this->translate(array('%s view', '%s views', $this->offer->view_count), $this->locale()->toNumber($this->offer->view_count)) ?>
      </div>
     <div class="sm-ui-cont-cont-date">
       <?php echo $this->translate('End date:'); ?>
        <?php if ($this->offer->end_settings == 1): ?>
         <?php echo $this->translate(gmdate('M d, Y', strtotime($this->offer->end_time))) ?>
        <?php else: ?>
        <strong><?php echo $this->translate('Never Expires') ?></strong>
        <?php endif; ?>
     </div> 
      <?php $viewer_id = $this->viewer->getIdentity(); ?>
      <?php if (!empty($viewer_id)): ?>
        <?php date_default_timezone_set($this->viewer->timezone); ?>
      <?php endif; ?>
      <?php $today = date("Y-m-d H:i:s"); ?>
      <?php $claim_value = Engine_Api::_()->getDbTable('claims', 'sitepageoffer')->getClaimValue($this->viewer_id, $this->offer->offer_id, $this->sitepage->page_id); ?>
      <div class="sm-ui-cont-cont-date">
        <?php if (!empty($this->offer->url)): ?><?php echo $this->translate('URL:'); ?>
          <a href = "<?php echo "http://" . $this->offer->url ?>" target="_blank"><?php echo "http://" . $this->offer->url; ?></a>
        <?php endif; ?>
      </div>
      <?php if (!empty($this->offer->coupon_code)): ?>
        <div class="sm-ui-cont-cont-date">
          <?php echo $this->translate('Coupon Code:'); ?>
          <strong><?php echo $this->offer->coupon_code; ?></strong>
        </div>
      <?php endif; ?>
  </div>
<div class="t_l clr">
        <?php echo nl2br($this->offer->description); ?>
      </div>
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepageoffer.getofferlink', 1)): ?>
      <div class="sm-ui-cont-cont-date <?php if (Engine_Api::_()->sitemobile()->isApp()): ?>t_center<?php endif ?>">
<?php if ($this->offer->claim_count == -1 && ($this->offer->end_time > $today || $this->offer->end_settings == 0)): ?>
        <?php $show_offer_claim = 1; ?>
      <?php elseif ($this->offer->claim_count > 0 && ($this->offer->end_time > $today || $this->offer->end_settings == 0)): ?>
        <?php $show_offer_claim = 1; ?>
      <?php else: ?>
        <?php $show_offer_claim = 0; ?>
      <?php endif; ?>


      <?php if (!empty($show_offer_claim) && empty($claim_value)): ?>
        <?php
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $urlO = $request->getRequestUri();
        $request_url = explode('/', $urlO);
        $param = 1;
        if (empty($request_url['2'])) {
          $param = 0;
        }
        $return_url = (!empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])) ? "https://" : "http://";
        $currentUrl = urlencode($urlO);
        ?>

            <span>
              <?php if (Engine_Api::_()->sitemobile()->isApp()): ?>
                <?php if (!empty($this->viewer_id)): ?>
                <?php echo ' ' . $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'getoffer', 'id' => $this->offer->offer_id), '<i class="ui-icon ui-icon-envelope get_off_icon"></i>' . $this->translate('Get Offer'), array('class' => 'smoothbox ui-btn ui-btn-b'));
                  ?>
                <?php else: ?>
                  <?php
                  $offer_tabinformation = $this->url(array('action' => 'getoffer', 'id' => $this->offer_id, 'param' => $param, 'request_url' => $request_url['1']), 'sitepageoffer_general') . "?" . "return_url=" . $return_url . $_SERVER['HTTP_HOST'] . $currentUrl;
                  $title = $this->translate('Get Offer');
                  echo "<a class='ui-btn ui-btn-b' href=$offer_tabinformation><i class='ui-icon ui-icon-envelope get_off_icon'></i> $title</a>";
                  ?>
                <?php endif; ?>
              <?php else :?>
                <?php if (!empty($this->viewer_id)): ?>
                <?php echo '<i class="ui-icon ui-icon-envelope get_off_icon"></i> ' . $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'getoffer', 'id' => $this->offer->offer_id), $this->translate('Get Offer'), array('class' => 'smoothbox'));
                  ?>
                <?php else: ?>
                  <?php
                  $offer_tabinformation = $this->url(array('action' => 'getoffer', 'id' => $this->offer_id, 'param' => $param, 'request_url' => $request_url['1']), 'sitepageoffer_general') . "?" . "return_url=" . $return_url . $_SERVER['HTTP_HOST'] . $currentUrl;
                  $title = $this->translate('Get Offer');
                  echo '<i class="ui-icon ui-icon-envelope get_off_icon"></i> ' . "<a href=$offer_tabinformation>$title</a>";
                  ?>
                <?php endif; ?>
              <?php endif; ?>
            </span>
          <?php elseif (!empty($claim_value) && !empty($show_offer_claim) || ($this->offer->claim_count == 0 && $this->offer->end_time > $today && !empty($claim_value))): ?>
            <span>
              <?php echo '<i class="ui-icon ui-icon-envelope get_off_icon"></i> ' . $this->htmlLink(array('route' => 'sitepageoffer_general', 'action' => 'resendoffer', 'id' => $this->offer->offer_id), Zend_Registry::get('Zend_Translate')->_('Resend Offer'), array('class' => 'smoothbox')); ?>
            </span>
          <?php else: ?>
            <span>
              <strong><?php echo $this->translate('Expired'); ?></strong>
            </span>
          <?php endif; ?>
            <?php if (!Engine_Api::_()->sitemobile()->isApp()): ?>
              -
            <?php endif?>
          <?php echo $this->offer->claimed . ' ' . $this->translate('claimed'); ?>
          <?php if ($this->offer->claim_count != -1): ?>
              -
              <?php echo $this->translate(array('%1$s claim left', '%1$s claims left', $this->offer->claim_count), $this->locale()->toNumber($this->offer->claim_count)) ?>
          <?php endif; ?>
      </div>
      <?php endif; ?>
</div>
</div>