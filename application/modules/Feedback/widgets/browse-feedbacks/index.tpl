<?php 
 /**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Document
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2010-08-11 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Feedback/externals/scripts/core.js'); ?>
<script type="text/javascript">

	var pageAction = function(page) {
    	$('page').value = page;
    	$('filter_form').submit();
  	}
  	var categoryAction = function(category) {
	    $('page').value = 1;
	    $('category').value = category;
	    currentValues = $$('.field_search_criteria')[0].toQueryString();
	    var url = en4.core.baseUrl + 'feedback'+'?'+currentValues;
	    window.location.href = url;
	   // $('filter_form').submit();
  	}
  	var statAction = function(stat){
	    $('page').value = 1;
	    $('stat').value = stat;
	    currentValues = $$('.field_search_criteria')[0].toQueryString();
	    var url = en4.core.baseUrl + 'feedback'+'?'+currentValues;
	    window.location.href = url;
	   // $('filter_form').submit();
  	}

  	var dateAction = function(start_date, end_date) {
	    $('page').value = 1;
	    $('start_date').value = start_date;
	    $('end_date').value = end_date;
	    currentValues = $$('.field_search_criteria')[0].toQueryString();
	    var url = en4.core.baseUrl + 'feedback'+'?'+currentValues;
	    window.location.href = url;
	    //$('filter_form').submit();
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

<?php if($this->is_msg == 1): ?>
	<ul class="form-notices">
		<li style="font-size:12px;">
			<?php echo $this->translate("Feedback has been created successfully!"); ?>
		</li>
	</ul>
<?php endif; ?>

<?php if(empty($this->is_ajax)):?>
  <div class="m10"> 
    <?php echo $this->translate(array('%s feedback found.', '%s feedbacks found.', $this->paginator->getTotalItemCount()),$this->locale()->toNumber($this->paginator->getTotalItemCount())); ?>
  </div>
<?php endif;?>

<div class='layout_middle' id="list_view">
  <?php if( $this->paginator->count() > 0): ?>
	  <ul class="seaocore_browse_list">
	    <?php foreach( $this->paginator as $feedback ): ?> 
		    <?php if($feedback->featured == 1): ?>
		    	<li class="feedback_featured"> 
		    <?php else: ?>
		    	<li>
		    <?php endif; ?>	
		    	<div class="feedbacks_list_vote_button">
		    		<div class="feedback_votes_counts">
		    			<p id="feedback_voting_<?php echo $feedback->feedback_id; ?>"> 
		    				<?php echo $feedback->total_votes ;?>
		    			</p>
		    			<?php echo $this->translate('votes');?>
		    		</div>
		    		<div id="feedback_vote_<?php echo $feedback->feedback_id; ?>" class="feedback_vote_button">
		        	<?php if($feedback->vote_id == NULL):?>
		        		<a href="javascript:void(0);" onClick="vote('<?php echo $this->viewer_id; ?>', '<?php echo $feedback->feedback_id; ?>');"><?php echo $this->translate('Vote'); ?></a>
		        	<?php elseif($this->viewer_id != 0): ?>
		        		<a href="javascript:void(0);" onClick="removevote('<?php echo $feedback->vote_id; ?>',  '<?php echo $feedback->feedback_id; ?>', '<?php echo $this->viewer_id; ?>');"><?php echo $this->translate('Remove'); ?></a>
		        	<?php endif; ?>
		      	</div>
		    	</div>
		    	
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
		        	<?php echo $this->translate(array('%s view', '%s views', $feedback->views), $this->locale()->toNumber($feedback->views)) ?>,
		        	<?php echo $this->translate(array('%s comment', '%s comments', $feedback->comment_count), $this->locale()->toNumber($feedback->comment_count)) ?>, 
		        	<?php echo $this->translate(array('%s picture', '%s pictures', $feedback->total_images), $this->locale()->toNumber($feedback->total_images)); ?>,
		        	<?php if($feedback->owner_id != 0 || !empty($feedback->owner_id)): ?>
		        		<?php echo $this->translate('Posted by %s about %s', $feedback->getOwner()->toString(), $this->timestamp($feedback->creation_date)) ?>
		        	<?php else: ?>	 
		        		<?php echo $this->translate('Posted by Anonymous user about %s',	$this->timestamp($feedback->creation_date)) ?>
		        	<?php endif;?>	 
		        </div>
		        <div>
		          <?php if ($feedback->category_id && ($category = Engine_Api::_()->getItem('feedback_category', $feedback->category_id))): ?>
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
  <div class="clr" id="scroll_bar_height"></div>
  <?php if (empty($this->is_ajax)) : ?>
    <div class = "seaocore_view_more mtop10" id="seaocore_view_more" style="display: none;">
      <?php
      echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array(
          'id' => '',
          'class' => 'buttonlink icon_viewmore'
      ))
      ?>
    </div>
    <div class="seaocore_view_more" id="loding_image" style="display: none;">
      <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' style='margin-right: 5px;' />
      <?php echo $this->translate("Loading ...") ?>
    </div>
    <div id="hideResponse_div"> </div>
  <?php endif; ?>
  <?php elseif( $this->category || $this->stat || $this->search ):?>
    <div class="tip">
      <span>
        <?php echo $this->translate('Nobody has posted a feedback  with that criteria.');?>
        <?php if($this->can_create): ?>
          <?php echo $this->translate('Be the first to %1$swrite%2$s one!', '<a href="'.$this->url(array(), 'feedback_create').'" onclick="owner(this);return false">', '</a>'); ?>
        <?php endif; ?>
      </span>
    </div>
  <?php else:?>
    <div class="tip">
      <span>
        <?php echo $this->translate('Nobody has posted a feedback yet.'); ?>
        <?php if($this->can_create): ?>
          <?php echo $this->translate('Be the first to %1$swrite%2$s one!', '<a href="'.$this->url(array(), 'feedback_create').'" onclick="owner(this);return false">', '</a>'); ?>
        <?php endif; ?>
      </span>
    </div>
  <?php endif; ?>
</div>

<script type="text/javascript" >

function owner(thisobj) {
	var Obj_Url = thisobj.href  ;

	Smoothbox.open(Obj_Url);
}
</script>

<?php if (empty($this->is_ajax)) : ?>
  <script type="text/javascript">
    function viewMoreFeedback()
    {
      $('seaocore_view_more').style.display = 'none';
      $('loding_image').style.display = '';
      var params = {
        requestParams:<?php echo json_encode($this->params) ?>
      };
      setTimeout(function() {
        en4.core.request.send(new Request.HTML({
          method: 'get',
          'url': en4.core.baseUrl + 'widget/index/mod/feedback/name/browse-feedbacks',
          data: $merge(params.requestParams, {
            format: 'html',
            subject: en4.core.subject.guid,
            page: getNextPage(),
            isajax: 1,
            show_content: '<?php echo $this->showContent;?>',
            loaded_by_ajax: true
          }),
          evalScripts: true,
          onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript) {
            $('hideResponse_div').innerHTML = responseHTML;
            $('list_view').getElement('.seaocore_browse_list').innerHTML = $('list_view').getElement('.seaocore_browse_list').innerHTML + $('hideResponse_div').getElement('.seaocore_browse_list').innerHTML;
            $('loding_image').style.display = 'none';
            $('hideResponse_div').innerHTML = '';
          }
        }));
      }, 800);

      return false;
    }
  </script>
<?php endif; ?>

<?php if ($this->showContent == 3): ?>
  <script type="text/javascript">
    en4.core.runonce.add(function() {
      $('seaocore_view_more').style.display = 'block';
      hideViewMoreLink('<?php echo $this->showContent; ?>');
    });</script>
<?php elseif ($this->showContent == 2): ?>
  <script type="text/javascript">
    en4.core.runonce.add(function() {
      $('seaocore_view_more').style.display = 'block';
      hideViewMoreLink('<?php echo $this->showContent; ?>');
    });</script>
<?php else: ?>
  <script type="text/javascript">
    en4.core.runonce.add(function() {
      $('seaocore_view_more').style.display = 'none';
    });
  </script>
  	<?php echo $this->paginationControl($this->paginator, null, null, array('query' => $this->formValues,'pageAsQuery' => true,)); ?>
<?php endif; ?>

<script type="text/javascript">

  function getNextPage() {
    return <?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>
  }

  function hideViewMoreLink(showContent) {

    if (showContent == 3) {
      $('seaocore_view_more').style.display = 'none';
      var totalCount = '<?php echo $this->paginator->count(); ?>';
      var currentPageNumber = '<?php echo $this->paginator->getCurrentPageNumber(); ?>';

      function doOnScrollLoadPage()
      {
        if (typeof($('scroll_bar_height').offsetParent) != 'undefined') {
          var elementPostionY = $('scroll_bar_height').offsetTop;
        } else {
          var elementPostionY = $('scroll_bar_height').y;
        }
        if (elementPostionY <= window.getScrollTop() + (window.getSize().y - 40)) {
          if ((totalCount != currentPageNumber) && (totalCount != 0))
            viewMoreFeedback();
        }
      }
      
      window.onscroll = doOnScrollLoadPage;

    }
    else if (showContent == 2)
    {
      var view_more_content = $('seaocore_view_more');
      view_more_content.setStyle('display', '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->totalCount == 0 ? 'none' : '' ) ?>');
      view_more_content.removeEvents('click');
      view_more_content.addEvent('click', function() {
        viewMoreFeedback();
      });
    }
  }
</script>
