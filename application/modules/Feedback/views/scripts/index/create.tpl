<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Feedback
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: create.tpl 6590 2010-07-08 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
 
<?php if(Engine_Api::_()->seaocore()->isMobile()): ?>
<?php if( count($this->navigation) ): ?>
	<div class="headline">
		<div class='tabs'> 
			<?php echo $this->navigation()->setContainer($this->navigation)->menu()->render() ?> 
		</div>
	</div>
<?php endif; ?>

<?php echo $this->form->render($this) ?>
<?php else: ?>
<script type="text/javascript"> 
	en4.core.setBaseUrl('<?php echo $this->url(array(), 'default', true) ?>');
</script>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Feedback/externals/scripts/core.js'); ?>

<script type="text/javascript">
	
	var vote = function(viewer_id, feedback_id) {
   	// SENDING REQUEST TO AJAX   
   	var request = en4.feedback.voting.createVote(viewer_id, feedback_id);    
   
   	// RESPONCE FROM AJAX
   	request.addEvent('complete', function(responseJSON) 
   	{ 
    	$('feedback_voting_' + feedback_id).innerHTML = responseJSON.total;
      $('feedback_vote_' + feedback_id).innerHTML = responseJSON.abc;
   	});
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

<?php if(!empty($this->feedback_post) && empty($this->viewer_id) && empty($this->feedback_public)): ?> 
	<div class="feedback_lightbox" style="width:470px;">
		<div class="tip" style="margin:10px 0 0 100px;">
			<span>
				<?php echo $this->translate("Please ") ?>
				<?php echo $this->htmlLink(Array('module'=> 'user', 'controller' => 'auth', 'action' => 'login', 'route' => 'default'), $this->translate("login"), array('target' => '_parent')); ?>
				<?php echo $this->translate(" to view and create feedback") ?>
			</span>
		</div>	
	</div>
<?php else: ?>

	<?php
		$this->headScript()
			->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/Observer.js')
			->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/Autocompleter.js')
			->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/Autocompleter.Local.js')
			->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/Autocompleter.Request.js');
	?>
<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.tag', 0) && !(empty($this->can_create) && !empty($this->viewer_id))):?>
	<script type="text/javascript">
		en4.core.runonce.add(function()
		{
			new Autocompleter.Request.JSON('tags', '<?php echo $this->url(array('controller' => 'tag', 'action' => 'suggest'), 'default', true) ?>', {
				'postVar' : 'text',

				'minLength': 1,
				'selectMode': 'pick',
				'autocompleteType': 'tag',
				'className': 'tag-autosuggest',
				'customChoices' : true,
				'filterSubset' : true,
				'multiple' : true,
				'injectChoice': function(token){
					var choice = new Element('li', {'class': 'autocompleter-choices', 'value':token.label, 'id':token.id});
					new Element('div', {'html': this.markQueryValue(token.label),'class': 'autocompleter-choice'}).inject(choice);
					choice.inputValue = token;
					this.addChoiceEvents(choice).inject(this.choices);
					choice.store('autocompleteChoice', token);
				}
			});
		});  
	</script>
  <?php endif;?>
	<?php if(empty($this->show_browse)): ?>
		<div class="feedback_lightbox" style="width:470px;">
		<div class="feedback_popup_form" style="width:480px;">
	<?php else: ?>
		<div class="feedback_lightbox">
		<div class="feedback_popup_form">
	<?php endif; ?>
		<?php if(empty($this->can_create) && !empty($this->viewer_id)): ?>
			<div class="feedback_lightbox_messages">
				<div class="tip" style="margin:150px 0 0 15px;">
					<span>
						<?php echo $this->translate('You have been blocked by the site admin from creating feedback.'); ?>
					</span>
				</div>
			</div>
		<?php elseif($this->feedback_post == 1 && empty($this->viewer_id)):?>
			<div class="feedback_lightbox_messages">
				<div class="tip" style="margin:150px 0 0 60px;">
					<span>
						<?php echo $this->translate("Please ") ?>
						<?php echo $this->htmlLink(Array('module'=> 'user', 'controller' => 'auth', 'action' => 'login', 'route' => 'default'), $this->translate("login"), array('target' => '_parent')); ?>
						<?php echo $this->translate(" to create feedback") ?>
					</span>	
				</div>
			</div>	
		<?php else:?>
			<?php echo $this->form->render($this) ?> 	
		<?php endif; ?>

		<?php if(empty($this->show_browse) && !empty($this->viewer_id)): ?>
			<?php echo $this->htmlLink(Array('route' => 'feedback_manage'), $this->translate("Go to My Feedbacks &raquo;"), array('target' => '_parent', 'class' => 'lightbox_more', 'style' => 'width:470px;text-align:right;margin-top:10px;')); ?>
		<?php endif; ?>
		</div>
		<?php if(!empty($this->show_browse)): ?>
			<div class="feedback_popup_right">
				<?php if(empty($this->viewer_id) && empty($this->feedback_public)): ?>
					<div class="tip" style="margin:180px 0 0 30px;">
						<span>
							<?php echo $this->translate("Please ") ?>
							<?php echo $this->htmlLink(Array('module'=> 'user', 'controller' => 'auth', 'action' => 'login', 'route' => 'default'), $this->translate("login"), array('target' => '_parent')); ?>
							<?php echo $this->translate(" to view feedback") ?>
						</span>
					</div>	
				<?php else: ?>
					<form action="<?php echo $this->url(array(), 'feedback_browse');?>" id='filter_form' class='global_form_box feedback_lightbox_search' method='POST' target="_parent">
						<input type='text' id="search" name="search" class="search_box" />
						<button name="submit" id="submit" type="submit"><?php echo $this->translate('Search');?></button>
					</form>
					<?php if($this->vote): ?>
						<h4>
							<?php echo $this->translate('Popular Feedback from the community');?>
						</h4>
					<?php endif; ?>
					<?php foreach( $this->voteFeedback as $voteFeedback ): ?>
						<?php if($voteFeedback->featured == 1): ?>
							<div class="lightbox_feedback_list feedback_featured">
						<?php else: ?>
							<div class="lightbox_feedback_list">
						<?php endif; ?>	
							<div class="feedbacks_lightbox_votes">
								<div class="feedback_votes_counts">
									<p id="feedback_voting_<?php echo $voteFeedback->feedback_id; ?>"> 
										<?php echo $voteFeedback->total_votes ;?>
									</p>
									<?php echo $this->translate('votes');?>
								</div>
							</div>
							<div class='feedbacks_lightbox_info_title'> 
								<?php  
									$truncation_enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('feedback.title.truncation', 1);
									$truncateText = $voteFeedback->feedback_title;
									if($truncation_enable) {
										$truncateText = Engine_Api::_()->seaocore()->seaocoreTruncateText($voteFeedback->feedback_title, 20);
									}
								?>
								<?php if($voteFeedback->featured):?>
									<span class="fright">
										<?php echo $this->htmlImage($this->layout()->staticBaseUrl.'application/modules/Feedback/externals/images/feedback_goldmedal1.gif', '', array('class' => 'icon', 'title' => 'Featured')) ?>
									</span> 
								<?php endif; ?>	
								<?php echo $this->htmlLink($voteFeedback->getHref(), $truncateText,  array('target'=>'_parent', 'title' => $voteFeedback->getTitle())); ?>
							</div>
							<div id="feedback_vote_<?php echo $voteFeedback->feedback_id; ?>" class="feedback_vote_button feedback_lightbox_vote_button">
								<?php if($this->viewer_id == 0):?>
									<?php echo $this->htmlLink(Array('route' => 'feedback_browse', 'check_anonymous_vote' => 1), $this->translate("vote"), array('target' => '_parent')); ?>
								<?php elseif($voteFeedback->vote_id == NULL && $this->viewer_id != 0):?>
									<a href="javascript:void(0);" onClick="vote('<?php echo $this->viewer_id; ?>', '<?php echo $voteFeedback->feedback_id; ?>');"><?php echo $this->translate('Vote')?></a>
								<?php else: ?>
									<a href="javascript:void(0);" onClick="removevote('<?php echo $voteFeedback->vote_id; ?>',  '<?php echo $voteFeedback->feedback_id; ?>', '<?php echo $this->viewer_id; ?>');"><?php echo $this->translate('Remove')?></a>
								<?php endif; ?>
							</div>
							<div class="feedbacks_lightbox_info">
								<p class='feedbacks_lightbox_info_date'>
									<?php echo $this->translate(array('%s comment', '%s comments', $voteFeedback->comment_count), $this->locale()->toNumber($voteFeedback->comment_count)) ?> | 
									<?php echo $this->translate(array('%s vote', '%s votes', $voteFeedback->total_votes), $this->locale()->toNumber($voteFeedback->total_votes)) ?> 
								</p>
								<p class='feedbacks_lightbox_info_date'>
									<?php foreach ($this->show_status as $status): ?>
										<?php if($voteFeedback->stat_id == $status->stat_id):?>
										<?php echo $this->translate('<b>Status: </b>');?><font color="<?php echo $status->stat_color;?>" ><?php echo $status->stat_name ?></font>
										<?php break; ?>
										<?php endif;?>
									<?php endforeach;?>	
								</p>
							</div>  
						</div> 
					<?php endforeach; ?>
					<?php echo $this->htmlLink(Array('route' => 'feedback_browse'), $this->translate("Go to Feedback Forum &raquo;"), array('target' => '_parent', 'class' => 'lightbox_more')); ?>
			
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>
<?php endif; ?>