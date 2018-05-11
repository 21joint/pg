<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagereview
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: view.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php $photo_review = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitepagereview.photo', 1);?>
<?php if(!Engine_Api::_()->sitemobile()->isApp()):?>
<?php 
$breadcrumb = array(
    array("href"=>$this->sitepage->getHref(),"title"=>$this->sitepage->getTitle(),"icon"=>"arrow-r"),
    array("href"=>$this->sitepage->getHref(array('tab' => $this->tab_selected_id)),"title"=>"Reviews","icon"=>"arrow-r"),
    array("title"=>$this->sitepagereview->getTitle(),"icon"=>"arrow-d","class" => "ui-btn-active ui-state-persist"));

echo $this->breadcrumb($breadcrumb);
?>
<?php endif; ?>

<div class="ui-page-content">
  <div class="sm-ui-cont-head">
    <div class="sm-ui-cont-author-photo">
      <?php if(!empty($photo_review)):?>
        <?php echo $this->htmlLink($this->owner->getHref(), $this->itemPhoto($this->owner)) ?>
      <?php else:?>
        <?php echo $this->htmlLink(Engine_Api::_()->sitepage()->getHref($this->sitepage->page_id, $this->sitepage->owner_id, $this->sitepage->getSlug()), $this->itemPhoto($this->sitepage, 'thumb.normal')) ?>
      <?php endif;?>
    </div>
    <div class="sm-ui-cont-cont-info">
      <div class="sm-ui-cont-author-name">
      	<?php echo $this->sitepagereview->title; ?> 
      </div>
      
    <?php if(!Engine_Api::_()->sitemobile()->isApp()):?>
      <div class="sm-ui-cont-cont-date">
      	<?php echo $this->translate('Posted by %s ', $this->sitepagereview->getOwner()->toString()); ?>
        -
        <?php echo $this->timestamp($this->sitepagereview->creation_date); ?>
      </div>
    <?php else:?>
      <div class="sm-ui-cont-cont-date">
        <?php echo $this->translate('For');?>
        <?php echo $this->htmlLink($this->sitepage->getHref(array('profile_link' => 1)), $this->sitepage->getTitle()) ?>
      </div>
      <div class="sm-ui-cont-cont-date">
        <?php echo $this->timestamp($this->sitepagereview->creation_date); ?>
        -
        <?php echo $this->translate('by %s ', $this->sitepagereview->getOwner()->toString()); ?>
      </div>
      <?php endif;?>
      
      <div class="sm-ui-cont-cont-date"> 
      	<?php echo $this->translate(array('%s view', '%s views', $this->sitepagereview->view_count), $this->locale()->toNumber($this->sitepagereview->view_count)) ?>
      </div>
    </div>
    </div>
  <?php echo $this->content()->renderWidget("sitepagereview.sitepage-review-detail"); ?>
			
    <?php if(false):?>  
		  <div class="tip">
		  	<span>
		  	<?php echo $this->translate("Like this review if you find it useful."); ?>
		  	</span>
		  </div>	
     <?php endif;?>
  </div>
<?php $baseUrl_full = 'http://' . $_SERVER['HTTP_HOST'] . $this->baseUrl(); ?>