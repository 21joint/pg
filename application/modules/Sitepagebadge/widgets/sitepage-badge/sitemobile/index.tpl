<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagebadge
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: showbadges.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
$this->headLink()
        ->appendStylesheet($this->layout()->staticBaseUrl
                . 'application/modules/Sitepagebadge/externals/styles/style_sitepagebadge.css')
?>
<?php
include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/common_style_css.tpl';
?>
<script type="text/javascript">
  var pageAction = function(page){
    $('#page').value = page;
    $('#filter_form_badge').submit();
  }
  
  var badgeAction = function(badge_id){
    //$('page').value = 1;
    $('#badge_id').value = badge_id;
    $('#filter_form_badge').submit();
  } 
</script>

<?php //include_once APPLICATION_PATH . '/application/modules/Sitepage/views/scripts/payment_navigation_views.tpl'; ?>

<?php echo $this->form->render($this) ?>

<!--<h3><?php echo $this->translate('Badges'); ?></h3>-->
<p><?php echo $this->translate('Below is the list of badges available on this site. Page owners can send request for badges to get them assigned to their pages for advertising, publicity and branding purposes. Clicking on a badge below will redirect you to the list of pages which have been asssigned that badge.'); ?></p>
<br />	

    <?php if (Count($this->badgeData)): ?>
<div class="sm-content-list">
            <ul data-role="listview" data-inset="false" data-icon="false">
              <?php foreach ($this->badgeData as $item): ?>
                <li>
                  <a href="javascript://" onclick="badgeAction('<?php echo $item->badge_id ?>');">
                  <?php if ($this->sitepagebadges_value == 1 || $this->sitepagebadges_value == 2): ?>
                    <?php
                    if (!empty($item->badge_main_id)) {
                      $thumb_path = Engine_Api::_()->storage()->get($item->badge_main_id, '')->getPhotoUrl();
                      if (!empty($thumb_path)) {
                        echo '<img src="' . $thumb_path . '" />';
                      }
                    }
                    ?>
                  <?php endif; ?>

                    <?php if (empty($this->sitepagebadges_value) || $this->sitepagebadges_value == 2): ?>             
                        <h3><?php echo  $this->translate($item->title); ?></h3>
          
                    <?php endif; ?>

                    <?php if (!empty($item->description)): ?>
                      <p><?php echo $this->translate($item->description); ?></p>
                    <?php endif; ?>

                </a>
                </li>
              <?php endforeach; ?>
            </ul>
      </div>		
    <?php else: ?>
      <div class="tip">
        <span>
          <?php echo $this->translate('No Badges has been added by admin at yet.'); ?>
        </span>
      </div>	
    <?php endif; ?>
	



<script type="text/javascript">
  var badgeAction = function(badge_id){
    var url = '<?php echo $this->url(array('action' => 'index' ), 'sitepage_general', true);?>';
    window.location.href = url + '?badge_id=' + badge_id;
  } 
</script>