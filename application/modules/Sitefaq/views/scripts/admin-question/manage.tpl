<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manage.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2><?php echo $this->translate('FAQs, Knowledgebase, Tutorials & Help Center Plugin')?></h2>

<?php if( count($this->navigation) ): ?>
  <div class='seaocore_admin_tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>

<h3><?php echo $this->translate("User Questions") ?></h3>

<p>
	<?php echo $this->translate("This page lists all the questions asked by members and visitors of your site. From Member Level Settings, you can choose who all should be able to ask questions. Here, you can monitor questions, delete them, answer them and you can also easily create new FAQ from them by clicking on 'Make FAQ' link alongwith the question.");?>
</p>

<script type="text/javascript">
  var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function(order, default_direction){

    if( order == currentOrder ) {
      $('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
    } else {
      $('order').value = order;
      $('order_direction').value = default_direction;
    }
    $('filter_form').submit();
  }

	function multiDelete()
	{
		return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete selected questions ?")) ?>');
	}

	function selectAll()
	{
	  var i;
	  var multidelete_form = $('multidelete_form');
	  var inputs = multidelete_form.elements;
	  for (i = 1; i < inputs.length - 1; i++) {
	    if (!inputs[i].disabled) {
	      inputs[i].checked = inputs[0].checked;
    	}
  	}
	}
</script>

<div class='admin_search'>
  <?php echo $this->formFilter->render($this) ?>
</div>

<br />

<?php if($this->paginator->getTotalItemCount()): ?>

	<div class='admin_members_results'>
		<div>
			<?php echo $this->translate(array('%s question found', '%s questions found', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
		</div>
		<?php echo $this->paginationControl($this->paginator); ?>
	</div>

	<br />

	<form id='multidelete_form' method="post" action="<?php echo $this->url(array('action'=>'multi-delete'));?>" onSubmit="return multiDelete()">
		<table class='admin_table seaocore_admin_table'>
			<thead>
				<tr>
					<th style='width: 1%;' align="left"><input onclick="selectAll()" type='checkbox' class='checkbox'></th>

					<?php $class = ( $this->order == 'question_id' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 1%;' align="left" class="admin_table_centered <?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('question_id', 'DESC');"><?php echo $this->translate('ID'); ?></a></th>

					<?php $class = ( $this->order == 'user_id' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 1%;' align="center" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('user_id', 'DESC');"><?php echo $this->translate('User ID'); ?></a></th>

					<?php $class = ( $this->order == 'anonymous_name' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 3%;' align="left" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('anonymous_name', 'ASC');"><?php echo $this->translate('Name'); ?></a></th>

					<?php $class = ( $this->order == 'anonymous_email' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 3%;' align="left" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('anonymous_email', 'ASC');"><?php echo $this->translate('Email');?></a></th>

					<?php $class = ( $this->order == 'title' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 3%;' align="left" class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate('Question');?></a></th>

					<?php $class = ( $this->order == 'admin_id' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 2%;' class='admin_table_centered' class="admin_table_centered <?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('admin_id', 'ASC');"><?php echo $this->translate('Answered'); ?></a></th>

					<?php $class = ( $this->order == 'creation_date' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 2%;' class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'DESC');"><?php echo $this->translate('Creation Date'); ?></a></th>
					<th style='width: 3%;' align="left"><?php echo $this->translate('Options'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if( count($this->paginator) ): ?>
					<?php foreach( $this->paginator as $question ): ?>
						<tr>
							
							<td><input name='delete_<?php echo $question->question_id;?>' type='checkbox' class='checkbox' value="<?php echo $question->question_id ?>"/></td>

							<td class="admin_table_centered"><?php echo $question->question_id ?></td>
							
							<?php if(empty($question->user_id)):?>
								<td class="admin_table_centered"><?php echo $this->translate('Anonymous'); ?></td>
							<?php else:?>
								<td class="admin_table_centered"><?php echo $question->user_id ?></td>
							<?php endif; ?>

							<td>
								<?php if(empty($question->user_id)):?>
									<span title="<?php echo $question->anonymous_name ?>"><?php echo $question->anonymous_name ?></span>
								<?php else:?>
									<?php echo $this->htmlLink($this->item('user', $question->user_id)->getHref()	, $question->username, array('title' => $question->username, 'target' => '_blank')) ?>
								<?php endif; ?>
							</td>
							
							<td>
								<?php if(empty($question->user_id)):?>
									<span title="<?php echo $question->anonymous_email ?>"><?php echo $question->anonymous_email ?></span>
								<?php else:?>
									<span title="<?php echo $question->email ?>"><?php echo $question->email ?></span>
								<?php endif; ?>
							</td>
		
							<?php $question_title = Engine_Api::_()->sitefaq()->truncateText($question->title, 20);?>
							<td><span title="<?php echo $question->title ?>"><?php echo $question_title; ?></span></td>

							<?php if(!empty($question->admin_id)):?>
								<?php $answerer = Engine_Api::_()->getItem('user', $question->admin_id)->getTitle();?>
								<td align="center" class="admin_table_centered">
									<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitefaq/externals/images/approved.gif', '', array('title'=> $this->translate("Answered by $answerer"))) ?> 
								</td>       
							<?php else: ?>  
								<td align="center" class="admin_table_centered"> 
									<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitefaq/externals/images/disapproved.gif', '', array('title'=> $this->translate('Not Answered Yet !'))) ?>
								</td>
							<?php endif; ?>
							
							<td align="center"><?php echo $question->creation_date ?></td>
							
							<td>
								<?php echo $this->htmlLink(array('route' => 'sitefaq_general','action' => 'create', 'question' => urlencode($question->title)), $this->translate('Make FAQ'), array('target' => '_blank')) ?> 
								|
								<?php if(empty($question->admin_id)):?>
									<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitefaq', 'controller' => 'admin-question', 'action' => 'answer', 'question_id' => $question->question_id, 'user_id' => $question->user_id, 'question' => urlencode($question->title)), $this->translate('Answer'), array('class' => 'smoothbox')) ?>
								<?php else: ?>
									<?php echo $this->translate('Answer'); ?>
								<?php endif; ?>
								|
								<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitefaq', 'controller' => 'admin-question', 'action' => 'delete', 'question_id' => $question->question_id), $this->translate('delete'), array('class' => 'smoothbox')) ?> 
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
		<br />
		<div class='buttons'>
			<button type='submit'><?php echo $this->translate('Delete Selected'); ?></button>
		</div>
	</form>
<?php else:?>
	<div class="tip">
		<span>
			<?php echo $this->translate('No results were found.');?>
		</span>
	</div>
<?php endif; ?>
