<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitepagemusic
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http:// www.socialengineaddons.com/license/
 * @version    $Id: view.tpl 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
  
<?php 
$breadcrumb = array(
    array("href"=>$this->sitepage->getHref(),"title"=>$this->sitepage->getTitle(),"icon"=>"arrow-r"),
    array("href"=>$this->sitepage->getHref(array('tab' => $this->tab_selected_id)),"title"=>"Music","icon"=>"arrow-r"),
    array("title"=>$this->playlist->getTitle(),"icon"=>"arrow-d","class" => "ui-btn-active ui-state-persist"));

echo $this->breadcrumb($breadcrumb);
?>

<?php
// this is done to make these links more uniform with other viewscripts
$playlist = $this->playlist;
$songs    = $playlist->getSongs();
$can_edit = $this->can_edit;
?>
<div class="sm-ui-cont-head">
		<div class="sm-ui-cont-cont-info">
			<div class="sm-ui-cont-author-name">
					<?php echo $playlist->getTitle();?>
			</div>
			<div class="sm-ui-cont-cont-date">
        <?php echo $this->translate('Created by ') ?>
	      <?php echo $this->htmlLink($playlist->getOwner(), $playlist->getOwner()->getTitle()) ?>
			-
	    <?php echo  $this->timestamp($playlist->creation_date) ?>
	    </div>
			<div class="sm-ui-cont-cont-date">
      <?php echo $this->translate(array('%s play', '%s plays', $playlist->play_count), $this->locale()->toNumber($playlist->play_count)) ?>
      -
      <?php echo $this->translate(array('%s view', '%s views', $playlist->view_count), $this->locale()->toNumber($playlist->view_count)) ?>
			</div>
		</div>
	</div>
  <p class="description">
    <?php echo $playlist->getDescription() ?>
  </p>
<div class="sm-ui-video-view">
  	 		<?php echo $this->partial('_sitemobilePlayer.tpl', array('playlist'=>$playlist, 'page_id' => $this->sitepage->page_id, 'can_edit' => $can_edit)) ?>

    <?php //echo $playlist->description ?>
</div>

