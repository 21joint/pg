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

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Feedback/externals/scripts/core.js'); ?>
<script type="text/javascript">

	var categoryAction =function(category) {
	    if($('text')) {
          $('search').value = $('text').value;
      } 
      $('category').value = category;
	    $('filter_form').submit();
	}
  
	var tagAction = function(tag){
      
    if($('text')) {
        $('search').value = $('text').value;
    }   
    $('tag').value = tag;
    $('filter_form').submit();
  }
  
	var statAction =function(stat) {
      
	    if($('text')) {
          $('search').value = $('text').value;
      } 
	    $('stat').value = stat;
	    $('filter_form').submit();
	}
  
	var vote = function(viewer_id, feedback_id) { 

		if(viewer_id == 0) {
    	var view_url = "<?php echo $this->view_url ?>";
			window.location = view_url;
  	}		
 	  if(viewer_id != 0) {
	    // SENDING REQUEST TO AJAX   
	    var request = en4.feedback.voting.createVote(viewer_id, feedback_id);    
	   
	    // RESPONCE FROM AJAX
	    request.addEvent('complete', function(responseJSON) {
	    	$('feedback_voting').innerHTML = responseJSON.total;
	      $('feedback_vote').innerHTML = responseJSON.abc;
	    });
	 	}
 	}
 
  var  removevote = function(vote_id, feedback_id, viewer_Id) {
   	// SENDING REQUEST TO AJAX   
   	var request = en4.feedback.voting.removeVote(vote_id, feedback_id, viewer_Id);    
   
   	// RESPONCE FROM AJAX
   	request.addEvent('complete', function(responseJSON) {  
        $('feedback_voting').innerHTML = responseJSON.total;
        $('feedback_vote').innerHTML = responseJSON.abc;
   	});
 	}
</script>

<?php if(!empty($this->feedback->owner_id)):?>
  <form id='filter_form' method='post' style="display:none;" action='<?php echo $this->url(array('user_id' => $this->feedback->owner_id), 'feedback_view') ?>'>
    <input type="hidden" id="search" name="search" type='text' />
    <input type="hidden" id="category" name="category" value=""/>
    <input type="hidden" id="tag" name="tag" value=""/>
    <input type="hidden" id="stat" name="stat" value=""/>
    <input type="hidden" id="start_date" name="start_date" value="<?php if ($this->start_date) echo $this->start_date;?>"/>
    <input type="hidden" id="end_date" name="end_date" value="<?php if ($this->end_date) echo $this->end_date;?>"/>
  </form>
<?php endif; ?>


<h2>
  <?php if($this->feedback->owner_id != 0): ?>
    <?php echo $this->htmlLink($this->feedback->getOwner()->getHref(), $this->feedback->getOwner()->getTitle()) . $this->translate("'s Feedback")?>
  <?php else:?>
    <?php echo $this->translate('Anonymous Feedback')?>
  <?php endif;?>	
</h2>
<ul class="feedbacks_view">
  <li>
    <?php if(!empty($this->feedback->owner_id) && !empty($this->show_browse)): ?>
      <div class="feedback_view_votes">
        <div class="feedback_votes_counts">
          <p id="feedback_voting"> 
            <?php echo $this->feedback->total_votes ;?>
          </p>
          <?php echo $this->translate(' votes');?>
        </div>

        <div id="feedback_vote" class="feedback_vote_button">
          <?php if(empty($this->vote) || $this->vote == 0 ):?>
          <a href="javascript:void(0);" onclick="vote('<?php echo $this->viewer_id; ?>', '<?php echo $this->feedback->feedback_id; ?>');"><?php echo $this->translate('Vote');?></a>
          <?php elseif($this->viewer_id != 0): ?>
          <a href="javascript:void(0);" onclick="removevote('<?php echo $this->vote; ?>',  '<?php echo $this->feedback->feedback_id; ?>', '<?php echo $this->viewer_id; ?>');"><?php echo $this->translate('Remove');?></a>
          <?php endif; ?>
        </div>
      </div>

    <?php endif;?>

    <h3> <?php echo $this->feedback->getTitle();?> </h3>
    <?php if($this->feedback->owner_id != 0 || !empty($this->feedback->owner_id)): ?>
      <div class="feedback_view_detail_date"> <?php echo $this->translate('Posted by %s about %s,', $this->feedback->getOwner()->toString(), $this->timestamp($this->feedback->creation_date)) ?>
    <?php else: ?>
      <div class="feedback_view_detail_date"> <?php echo $this->translate('Posted by Anonymous user about %s,', $this->timestamp($this->feedback->creation_date)) ?>
    <?php endif;?>
    <?php if ($this->category && (!empty($this->feedback->owner_id))):?>
      <?php echo $this->translate('Category:');?> <a href='javascript:void(0);' onclick='javascript:categoryAction(<?php echo $this->category->category_id?>);'><?php echo $this->category->category_name ?>,</a>
    <?php elseif ($this->category && (empty($this->feedback->owner_id))): ?>  	
      <?php echo $this->translate('Category:');?> <?php echo $this->category->category_name ?>,
    <?php endif; ?>
    <?php echo $this->translate(array('%s view', '%s views', $this->feedback->views), $this->locale()->toNumber($this->feedback->views)) ?>,
    <?php echo  $this->translate(array('%s picture', '%s pictures', $this->feedback->total_images), $this->locale()->toNumber($this->feedback->total_images)) ?>
    <?php if(!empty($this->show_tag)):?>
      <?php if (count($this->feedbackTags )):?>
        <?php echo $this->translate("Tag:"); ?>
        <?php foreach ($this->feedbackTags as $tag): ?>
          <a href='javascript:void(0);' onclick='javascript:tagAction(<?php echo $tag->getTag()->tag_id; ?>);'>#<?php echo $tag->getTag()->text?></a>&nbsp;
        <?php endforeach; ?>
      <?php endif; ?>
    <?php endif; ?>
    </div>

    <div class="feedback_view_detail_stats"> 
      <?php echo $this->translate(array('%s comment', '%s comments', $this->feedback->comment_count), $this->locale()->toNumber($this->feedback->comment_count)) ?>,
      <?php echo $this->translate(array('%s participant', '%s participants', $this->participants_total), $this->locale()->toNumber($this->participants_total)) ?>
    </div>
    <div class="feedback_view_des"> 
      <?php echo $this->feedback->feedback_description; ?> 
    </div>

    <!--CUSTOM FIELD WORK -->
    <?php echo $this->fieldValueLoop($this->feedback, $this->fieldStructure) ?>
    <!--END CUSTOM FIELD WORK -->

    <?php if($this->feedback->total_images): ?>
      <div class="feedback_images">
        <h4><?php echo $this->translate('Feedback Pictures');?></h4>
        <?php foreach( $this->images as $image ): ?>
          <div class="feedback_img">
            <a href="<?php echo $this->url(array('owner_id' => $image->user_id, 'album_id' => $image->album_id, 'image_id' => $image->image_id), 'feedback_image_specific') ?>">
             <?php echo $this->itemPhoto($image, 'thumb.normal') ?>
            </a>
            <?php if(($this->viewer_id == $this->feedback->owner_id && $this->viewer_id != 0) || $this->user_level == 1): ?>
              <?php echo $this->htmlLink(array('route'=>'feedback_removeimage', 'feedback_id'=>$this->feedback->feedback_id, 'image_id' => $image->image_id, 'owner_id' => $image->user_id), $this->translate('Delete')) ?> 
            <?php endif; ?>
          </div> 
        <?php endforeach;?>
      </div>
    <?php endif; ?>

    <?php if(!empty($this->stat) || !empty($this->feedback->status_body)): ?>
    <div class="feedback_status_box">
       <?php if ($this->stat):?>
        <?php echo $this->translate('<b>Status:</b>');?> <a href='javascript:void(0);' onclick='javascript:statAction(<?php echo $this->stat->stat_id?>);' class="feedback_status"  style ="background-color:<?php echo $this->stat->stat_color ?>;" ><?php echo $this->stat->stat_name ?></a><br />
      <?php endif; ?>
      <?php if(!empty($this->feedback->status_body)): ?>
        <?php if(empty($this->stat)): ?><?php echo $this->translate('<b>Status:</b>');?><?php endif;?>
        <p class='feedback_view_des'> <?php echo $this->feedback->status_body ?> </p>
      <?php endif;?>
    </div>
    <?php endif; ?>
  </li>	
  <li>
    <?php if($this->can_comment && !empty($this->viewer_id) && !empty($this->feedback->owner_id)): ?>
      <div>
        <?php if(Engine_Api::_()->hasModuleBootstrap('nestedcomment')):?>
            <?php echo $this->content()->renderWidget("nestedcomment.comments") ?>  
        <?php else :  ?>
            <?php echo $this->content()->renderWidget("core.comments") ?>  
        <?php endif; ?> 
      </div>
    <?php endif; ?>
    <?php if(empty($this->viewer_id) && ($this->feedback->comment_count)): ?>
      <div>
        <?php echo $this->translate('Please'); ?><a href="<?php echo $this->paramalink_url ?>" ><?php echo $this->translate(' login'); ?></a><?php echo $this->translate(' to view comments.'); ?>
      </div>
    <?php endif; ?>
    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.report', 1) && !empty($this->viewer_id)): ?>
      <div class="feedback_report_link">
        <?php echo $this->htmlLink(Array('module'=> 'core', 'controller' => 'report', 'action' => 'create', 'route' => 'default', 'subject' => $this->feedback->getGuid(), 'format' => 'smoothbox'), $this->translate("Inappropriate Content"), array('class' => 'buttonlink smoothbox seaocore_icon_report')); ?>
      </div>	
    <?php endif; ?>
  </li>    
</ul>