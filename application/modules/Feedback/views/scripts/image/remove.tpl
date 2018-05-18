<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: remove.tpl 6590 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('Feedback Pictures'); ?></h2>

<?php if( count($this->navigation) ): ?>
	<div class="headline">
	  <div class='tabs'> <?php echo $this->navigation()->setContainer($this->navigation)->menu()->render() ?> </div>
	</div>
<?php endif; ?>

<div class='global_form'>
  <form method="post" class="global_form">
    <div>
      <div>
        <h3><?php echo $this->translate('Delete Picture?');?></h3>
        <p> <?php echo $this->translate('Are you sure that you want to delete the picture ?'); ?> </p>
        <br />
        <p>
          <input type="hidden" name="confirm" value="true"/>
          <button type='submit' target="_parent"><?php echo $this->translate('Delete');?></button>
          <?php echo $this->translate('or');?> <?php echo $this->htmlLink(array('route'=>'feedback_detail_view', 'feedback_id'=>$this->feedback->feedback_id, 'slug' => $this->feedback->getSlug(), 'user_id' => $this->feedback->owner_id), $this->translate('cancel'))?>
      </div>
    </div>
  </form>
</div>