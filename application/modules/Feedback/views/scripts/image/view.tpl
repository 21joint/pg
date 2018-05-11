<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: view.tpl 6590 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2>
	<?php echo $this->translate('%1$s\'s Pictures', $this->album->getOwner()->__toString()) ?>
</h2>

<div class='feedback_viewmedia'>
  <div class="feedback_viewmedia_nav">
    <div>
      <?php echo $this->translate('Picture %1$s of %2$s',
                          $this->locale()->toNumber($this->image->getCollectionIndex() + 1),
                          $this->locale()->toNumber($this->album->count())) ?>
    </div>
    <?php if ($this->album->count() > 1): ?>
    <div>
      <?php echo $this->htmlLink($this->image->getPrevCollectible()->getHref(), $this->translate('Prev')) ?>
      <?php echo $this->htmlLink($this->image->getNextCollectible()->getHref(), $this->translate('Next')) ?>
    </div>
    <?php endif; ?>
  </div>
  <div class='feedback_viewmedia_info'>
    <div class='feedback_viewmedia_container' id='media_image_div'>
      <a id='media_image_next'  href='<?php echo $this->escape($this->image->getNextCollectible()->getHref()) ?>'>
        <?php echo $this->htmlImage($this->image->getPhotoUrl(), $this->image->getTitle(), array(
          'id' => 'media_image'
        )); ?>
      </a>
    </div>
    <br />
    <a></a>
  </div>
</div>