<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manage.tpl 6590 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Feedback/externals/scripts/core.js'); ?>
<script type="text/javascript">
	var pageAction =function(page){
	    $('page').value = page;
	    $('filter_form').submit();
	}
	
	var statAction = function(stat){
	    $('page').value = 1;
	    $('stat').value = stat;
	    $('filter_form').submit();
	}
	
	var categoryAction = function(category){
	    $('page').value = 1;
	    $('category').value = category;
	    $('filter_form').submit();
	}

  var mostvotedAction = function(mostvoted){
      //$('page').value = 1;
      $('orderby_mostvoted').value = 'total_votes';
      $('filter_form_mostvoted').submit();
  } 
    
  var vote = function(viewer_id, feedback_id) {
  	if(viewer_id == 0) {
    	var browse_url = "<?php echo $this->browse_url ?>";
			window.location = browse_url;
  	}		
 	  if(viewer_id != 0) {
	   	// SENDING REQUEST TO AJAX   
	   	var request = en4.feedback.voting.createVote(viewer_id, feedback_id);    
	   
	   	// RESPONCE FROM AJAX
	   	request.addEvent('complete', function(responseJSON)	{
	    		$('feedback_voting_' + feedback_id).innerHTML = responseJSON.total;
	      	$('feedback_vote_' + feedback_id).innerHTML = responseJSON.abc;
	   	});
 		}
	}
 
  var  removevote = function(vote_id, feedback_id, viewer_Id) {
	  
  	// SENDING REQUEST TO AJAX   
  	var request = en4.feedback.voting.removeVote(vote_id, feedback_id, viewer_Id);    
   
   	// RESPONCE FROM AJAX
   	request.addEvent('complete', function(responseJSON)  { 
       $('feedback_voting_' + feedback_id).innerHTML = responseJSON.total;
        $('feedback_vote_' + feedback_id).innerHTML = responseJSON.abc;
   	});
	}
</script>

<!--<div class="headline">
  <h2> <?php //echo $this->translate("Feedback") ?> </h2>
  <div class='tabs'> <?php //echo $this->navigation($this->navigation)->render() ?> </div>
</div>-->

<!--<div class='layout_right'> 
	<?php //echo $this->form->render($this) ?>
</div>-->


<?php if( ($this->paginator->count() == 0) && ($this->search || $this->category || $this->stat)): ?>
  <div class="tip">
    <span> 
      <?php echo $this->translate('You do not have any feedback that match your search criteria.'); ?>
    </span>
  </div>
<?php elseif($this->paginator->count() == 0): ?>  
  <div class="tip">
    <span> 
      <?php echo $this->translate('You do not have any feedback.');?>
      <?php if ($this->can_create): ?> 
        <?php echo $this->translate('Get started by %1$screating%2$s a new feedback.', '<a href="'.$this->url(array(), 'feedback_create').'" class="smoothbox">', '</a>'); ?>
      <?php endif; ?>
    </span>
  </div>
<?php endif; ?>
<ul class="seaocore_browse_list">
  <?php foreach( $this->paginator as $feedback ): ?>
    <?php if($feedback->featured == 1): ?>
      <li class="feedback_featured"> 
    <?php else: ?>
      <li>
    <?php endif; ?>	
      <div class="seaocore_browse_list_options">
        <?php echo $this->htmlLink($feedback->getHref(), $this->translate('View Feedback'), array('class' => 'buttonlink icon_feedback')) ?>
        <?php if( $feedback->owner_id == $this->viewer_id) echo $this->htmlLink(array('route' => 'feedback_edit', 'feedback_id' => $feedback->feedback_id), $this->translate('Edit Feedback'), array('class' => 'buttonlink icon_feedback_edit')) ?>
        <?php if($this->allow_upload == 1): ?>
          <?php echo $this->htmlLink(array(
                    'route' => 'feedback_extended',
                    'controller' => 'image',
                    'action' => 'upload',
                    'owner_id' => $feedback->owner_id,
                    'subject' => $feedback->getGuid(),
                  ), $this->translate('Add Pictures'), array(
                    'class' => 'buttonlink icon_feedback_image_new'
                )) ?>
        <?php endif; ?>
        <?php if( $feedback->owner_id == $this->viewer_id) echo $this->htmlLink(array('route' => 'feedback_delete', 'feedback_id' => $feedback->feedback_id), $this->translate('Delete Feedback'), array(
            'class'=>'buttonlink icon_feedback_delete'
           )) ?>
      </div>
      <?php if(!empty($this->show_browse)): ?>
        <div class="feedbacks_list_vote_button">
          <div class="feedback_votes_counts">
            <p id="feedback_voting_<?php echo $feedback->feedback_id; ?>"> 
              <?php echo $feedback->total_votes ;?>
            </p>
            <?php echo $this->translate('votes');?>
          </div>
          <div id="feedback_vote_<?php echo $feedback->feedback_id; ?>" class="feedback_vote_button">
            <?php if($feedback->vote_id == NULL):?>
              <a href="javascript:void(0);" onClick="vote('<?php echo $this->viewer_id; ?>', '<?php echo $feedback->feedback_id; ?>');"><?php echo $this->translate('Vote');?></a>
            <?php elseif($this->viewer_id != 0): ?>
              <a href="javascript:void(0);" onClick="removevote('<?php echo $feedback->vote_id; ?>',  '<?php echo $feedback->feedback_id; ?>', '<?php echo $this->viewer_id; ?>');"><?php echo $this->translate('Remove');?></a>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>

      <div class='seaocore_browse_list_info'>
        <div class='seaocore_browse_list_info_title'> 
          <?php if($feedback->featured == 1): ?>
            <span>
              <?php echo $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/feedback_goldmedal1.gif', '', array('class' => 'icon', 'title' => 'Featured')) ?>
            </span>
          <?php endif;?>
          <?php 
            $truncation_enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.title.truncation', 1);
            $truncateText = $feedback->feedback_title;
            if($truncation_enable) {
              $truncateText = Engine_Api::_()->seaocore()->seaocoreTruncateText($feedback->feedback_title, 70);
            }
          ?>
          <div class="seaocore_title">
            <?php echo $this->htmlLink($feedback->getHref(), $truncateText) ?> 
          </div>
        </div>
        <div class='seaocore_browse_list_info_date'> 
          <?php echo $this->translate('Posted by %s about %s', $feedback->getOwner()->toString(), $this->timestamp($feedback->creation_date)) ?>, <?php echo $this->translate(array('%s comment', '%s comments', $feedback->comment_count), $this->locale()->toNumber($feedback->comment_count)) ?>, <?php echo $this->translate(array('%s view', '%s views', $feedback->views), $this->locale()->toNumber($feedback->views)) ?>, <?php echo$this->translate(array('%s picture', '%s pictures', $feedback->total_images), $this->locale()->toNumber($feedback->total_images))?> 
        </div>
        <div>
          <?php if($feedback->category_id && ($category = Engine_Api::_()->getItem('feedback_category', $feedback->category_id))):?>
            <?php echo $this->translate('Category:');?> <a href='javascript:void(0);' onclick='javascript:categoryAction(<?php echo $category->category_id?>);'><?php echo $category->category_name ?></a>
          <?php endif;?>
        </div>
        <div class='seaocore_browse_list_info_blurb'> 
          <?php echo $this->viewMore($feedback->getDescription()) ?> 
        </div>
        <?php if($feedback->stat_id || !empty($feedback->status_body)): ?>	   
          <?php $status = Engine_Api::_()->getItem('feedback_status', $feedback->stat_id); ?>
          <div class="feedback_status_box">
            <?php if ($status): ?>
              <?php echo $this->translate('<b>Status:</b>');?> <a href='javascript:void(0);' onclick='javascript:statAction(<?php echo $status->stat_id?>);' class="feedback_status"  style ="background-color:<?php echo $status->stat_color ?>;"><?php echo $status->stat_name ?></a>
            <?php endif;?>

            <?php if(!empty($feedback->status_body)): ?>
              <?php if(!$feedback->stat_id): ?><?php echo $this->translate('<b>Status:</b>');?><?php endif; ?>
              <div class='seaocore_browse_list_info_blurb'> 
                <?php echo Engine_Api::_()->seaocore()->seaocoreTruncateText($feedback->status_body, 180); ?> 
              </div>
            <?php endif;?>		
          </div>
        <?php endif; ?> 
      </div>
    </li>
  <?php endforeach; ?>
</ul>
<div class="seaocore_pagination"> 
  <?php echo $this->paginationControl($this->paginator, null, null, array('query' => $this->formValues,'pageAsQuery' => true,)); ?> 
</div>