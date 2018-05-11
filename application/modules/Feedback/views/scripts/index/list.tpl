<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: list.tpl 6590 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Feedback/externals/scripts/core.js'); ?>
<script type="text/javascript">
	var pageAction = function(page) { 
	    if($('text')) {
          $('search').value = $('text').value;
      } 	    
      $('page').value = page;
	    $('filter_form').submit();
	}
  
	var categoryAction = function(category) {
	    if($('text')) {
          $('search').value = $('text').value;
      } 	    
      $('page').value = 1;
	    $('category').value = category;
	    $('filter_form').submit();
	}
  
	var statAction = function(stat){
	    if($('text')) {
          $('search').value = $('text').value;
      } 	    
      $('page').value = 1;
	    $('stat').value = stat;
	    $('filter_form').submit();
  	}
	
	var vote = function(viewer_id, feedback_id) {  
		if(viewer_id == 0) {
	  	  	var list_url = "<?php echo $this->list_url ?>";
					window.location = list_url;
	  }		
	 	if(viewer_id != 0) {
		 	// SENDING REQUEST TO AJAX   
	   	var request = en4.feedback.voting.createVote(viewer_id, feedback_id);    
	   
	   	// RESPONCE FROM AJAX
	   	request.addEvent('complete', function(responseJSON) 
	   	{
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

<form id='filter_form' class='' method='POST' style="display:none;">
  <input type='hidden' id="search" name="search" value="<?php if( $this->search ) echo $this->search; ?>"/>
  <input type="hidden" id="category" name="category" value="<?php if( $this->category ) echo $this->category; ?>"/>
  <input type="hidden" id="stat" name="stat" value="<?php if( $this->stat ) echo $this->stat; ?>"/>
  <input type="hidden" id="page" name="page" value="<?php if( $this->page ) echo $this->page; ?>"/>
</form>

<div class="fright">
  <ul>
    <li> 
      <a href='<?php echo $this->url(array('user_id' => $this->owner->getIdentity()), 'feedback_view') ?>' class='buttonlink icon_feedback'>
        <?php echo $this->translate('View All Feedback');?>
      </a> 
    </li>
  </ul>
</div>

<h2><?php echo $this->htmlLink($this->owner->getHref(), $this->owner->getTitle()) . $this->translate("'s Feedbacks")?></h2>

<?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
  <ul class='seaocore_browse_list'>
    <?php foreach ($this->paginator as $feedback): ?>
      <?php if($feedback->featured == 1): ?>
        <li style=" border-top-width: 1px;padding-top:15px;" class="feedback_featured">
      <?php else: ?>	
        <li style=" border-top-width: 1px;padding-top:15px;"> 
      <?php endif; ?>	
      <?php if(!empty($this->show_browse)): ?>
        <div class="feedbacks_list_vote_button">
          <div class="feedback_votes_counts">
            <p id="feedback_voting_<?php echo $feedback->feedback_id; ?>"> 
              <?php echo $feedback->total_votes ;?>
            </p>
            <?php echo $this->translate('votes')?>
          </div>	
          <div id="feedback_vote_<?php echo $feedback->feedback_id; ?>" class="feedback_vote_button"> 
            <?php if($feedback->vote_id == NULL):?>
              <a href="javascript:void(0);" onClick="vote('<?php echo $this->viewer_id; ?>', '<?php echo $feedback->feedback_id; ?>');"><?php echo $this->translate('Vote')?></a>
            <?php elseif($this->viewer_id != 0): ?>
              <a href="javascript:void(0);" onClick="removevote('<?php echo $feedback->vote_id; ?>',  '<?php echo $feedback->feedback_id; ?>', '<?php echo $this->viewer_id; ?>');"><?php echo $this->translate('Remove')?></a>
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
          <?php echo $this->translate(array('%s view', '%s views', $feedback->views), $this->locale()->toNumber($feedback->views)) ?>, <?php echo $this->translate(array('%s comment', '%s comments', $feedback->comment_count), $this->locale()->toNumber($feedback->comment_count))?>,  <?php echo $this->translate(array('%s picture', '%s pictures', $feedback->total_images), $this->locale()->toNumber($feedback->total_images)) ?>, <?php echo $this->translate(' Posted by');?> <?php echo $this->htmlLink($feedback->getParent(), $feedback->getParent()->getTitle()) ?> <?php echo $this->timestamp($feedback->creation_date) ?> 
        </div>
        <div>
            <?php if ($feedback->category_id && ($category = Engine_Api::_()->getItem('feedback_category', $feedback->category_id))): ?>
              <?php echo $this->translate('Category:');?> <a href='javascript:void(0);' onclick='javascript:categoryAction(<?php echo $category->category_id?>);'><?php echo $category->category_name ?></a>
            <?php endif;?>
        </div>
        <div class="seaocore_browse_list_info_blurb"> 
          <?php echo substr(strip_tags($feedback->feedback_description), 0, 350); if (strlen($feedback->feedback_description)>349) echo "..."; ?>
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
<?php elseif( $this->category || $this->text || $this->stat): ?>
  <div class="tip"> <span> <?php echo $this->translate('%1$s has not posted any feedback with this criteria.', $this->owner->getTitle()); ?> </span> </div>
<?php else: ?>
  <div class="tip"> <span> <?php echo $this->translate('%1$s has not posted a feedback entry yet.', $this->owner->getTitle()); ?> </span> </div>
<?php endif; ?>
<div class='seaocore_pagination'> 
  <?php echo $this->paginationControl($this->paginator, null, null, array('query' => $this->formValues,'pageAsQuery' => true,)); ?> 
</div>
