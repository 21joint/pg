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
		return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete selected FAQs ?")) ?>');
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

<h2><?php echo $this->translate('FAQs, Knowledgebase, Tutorials & Help Center Plugin')?></h2>

<?php if( count($this->navigation) ):?>
	<div class='seaocore_admin_tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
	</div>
<?php endif; ?>

<h3><?php echo $this->translate("Manage FAQs") ?></h3>

<?php echo $this->translate("This page lists all the FAQs created in your community. Here, you can monitor the FAQs, make FAQs featured / un-featured and also approve / disapprove them. To create a new FAQ, click on the link below.");?>

<br /><br />

<?php echo $this->htmlLink(array('route' => 'sitefaq_general', 'action' => 'create'), $this->translate('Create New FAQ'), array('class'=> 'buttonlink seaocore_icon_add', 'target' => '_blank')) ?>

<div class='admin_search'>
  <?php echo $this->formFilter->render($this) ?>
</div>

<br />

<div class="admin_search sead_admin_search">
  <div class="search">
    <form method="post" class="global_form_box" action="">

      <div>
	      <label>
	      	<?php echo  $this->translate("Title") ?>
	      </label>
	      <?php if( empty($this->title)):?>
	      	<input type="text" name="title" /> 
	      <?php else: ?>
	      	<input type="text" name="title" value="<?php echo $this->translate($this->title)?>"/>
	      <?php endif;?>
      </div>

      <div>
      	<label>
      		<?php echo  $this->translate("Owner") ?>
      	</label>	
      	<?php if( empty($this->owner)):?>
      		<input type="text" name="owner" /> 
      	<?php else: ?> 
      		<input type="text" name="owner" value="<?php echo $this->translate($this->owner)?>" />
      	<?php endif;?>
      </div>

      <div>
	    	<label>
	      	<?php echo  $this->translate("Featured") ?>	
	      </label>
        <select id="" name="featured">
          <option value="0" ></option>
          <option value="2" <?php if( $this->featured == 2) echo "selected";?> ><?php echo $this->translate("Yes") ?></option>
          <option value="1" <?php if( $this->featured == 1) echo "selected";?> ><?php echo $this->translate("No") ?></option>
         </select>
      </div>

      <div>
	    	<label>
	      	<?php echo  $this->translate("Approved") ?>
	      </label>
        <select name="approved">
          <option value="0" ></option>
          <option value="2" <?php if( $this->approved == 2) echo "selected";?> ><?php echo $this->translate("Yes") ?></option>
          <option value="1" <?php if( $this->approved == 1) echo "selected";?> ><?php echo $this->translate("No") ?></option>
        </select>
      </div>

      <div>
	    	<label>
	      	<?php echo  $this->translate("Status") ?>
	      </label>
        <select name="draft">
          <option value="0" ></option>
          <option value="2" <?php if( $this->draft == 2) echo "selected";?> ><?php echo $this->translate("Draft") ?></option>
          <option value="1" <?php if( $this->draft == 1) echo "selected";?> ><?php echo $this->translate("Published") ?></option>
        </select>
      </div>

      <?php  $categories = Engine_Api::_()->getDbTable('categories', 'sitefaq')->getCategories(null); ?>
      <?php if(count($categories) > 0) :?>
        <div>
          <label>
            <?php echo  $this->translate("Category") ?>
          </label>
          <select class="sitefaq_cat_select" id="" name="category_id" onchange="subcategories(this.value, '', '', '');">
            <option value=""></option>
            <?php if (count($categories) != 0) : ?>
							<?php $categories_prepared[0] = "";
                  foreach ($categories as $category) {
                    $categories_prepared[$category->category_id] = $category->category_name; ?>
                    <option value="<?php echo $category->category_id;?>" <?php if( $this->category_id == $category->category_id) echo "selected";?>><?php echo $this->translate($category->category_name);?></option>
              <?php } ?>
						<?php endif ; ?>
          </select>
        </div>

				<div id="subcategory_backgroundimage" class="cat_loader"><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitefaq/externals/images/loading.gif" /></div>
				<div id="subcategory_id-label">
					<label>
							<?php echo  $this->translate("Subcategory") ?>	
					</label>
					
					<select class="sitefaq_cat_select" name="subcategory_id" id="subcategory_id" onchange="changesubcategory(this.value, '')"></select>
				</div>

				<div id="subsubcategory_backgroundimage" class="cat_loader"><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitefaq/externals/images/loading.gif" /></div>
				<div id="subsubcategory_id-label">
					<label>
							<?php echo  $this->translate('3%s Level Category', "<sup>rd</sup>") ?>
					</label>
					<select class="sitefaq_cat_select" name="subsubcategory_id" id="subsubcategory_id"></select>
				</div>
      <?php endif;?>
 
      <div>
      	<div class="buttons clr fleft mtop10">
        	<button type="submit" name="search" ><?php echo $this->translate("Search") ?></button>
        </div>	
      </div>
    </form>
  </div>
</div>
<br />

<?php if($this->paginator->getTotalItemCount()): ?>

	<div class='admin_members_results'>
		<div style="margin-bottom:5px;">
			<?php echo $this->translate(array('%s FAQ found.', '%s FAQs found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
		</div>
		<?php echo $this->paginationControl($this->paginator); ?>
	</div>

	<br />

	<form id='multidelete_form' method="post" action="<?php echo $this->url(array('action'=>'multi-delete'));?>" onSubmit="return multiDelete()">
		<table class='admin_table seaocore_admin_table'>
			<thead>
				<tr>
					<th style='width: 1%;' align="left"><input onclick="selectAll()" type='checkbox' class='checkbox'></th>

           <?php $class = ( $this->order == 'faq_id' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th class="<?php echo $class ?>" style='width: 1%;' align="center"><a href="javascript:void(0);" onclick="javascript:changeOrder('faq_id', 'DESC');"><?php echo $this->translate('ID'); ?></a></th>

           <?php $class = ( $this->order == 'username' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th class="<?php echo $class ?>" style='width: 2%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('username', 'DESC');"><?php echo $this->translate('Owner'); ?></a></th>

           <?php $class = ( $this->order == 'title' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th class="<?php echo $class ?>" style='width: 1%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate('Question');?></a></th>

					<?php $class = ( $this->order == 'featured' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 1%;' class="admin_table_centered <?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('featured', 'ASC');"><?php echo $this->translate('Featured'); ?></a></th>

					<?php $class = ( $this->order == 'approved' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 1%;' class="admin_table_centered <?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('approved', 'ASC');"><?php echo $this->translate('Approved'); ?></a></th>

					<?php $class = ( $this->order == 'draft' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 1%;' class="admin_table_centered <?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('draft', 'ASC');"><?php echo $this->translate('Status'); ?></a></th>

					<?php $class = ( $this->order == 'view_count' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 1%;' class="admin_table_centered <?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('view_count', 'ASC');"><?php echo $this->translate('Views'); ?></a></th>

					<?php $class = ( $this->order == 'comment_count' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 1%;' class="admin_table_centered <?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('comment_count', 'ASC');"><?php echo $this->translate('Comments'); ?></a></th>

					<?php $class = ( $this->order == 'like_count' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 1%;' class="admin_table_centered <?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('like_count', 'ASC');"><?php echo $this->translate('Likes'); ?></a></th>

					<?php $class = ( $this->order == 'helpful' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 1%;' class="admin_table_centered <?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('helpful', 'ASC');"><?php echo $this->translate('Helpful (%)'); ?></a></th>

					<?php $class = ( $this->order == 'rating' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 3%;' class="admin_table_centered <?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('rating', 'ASC');"><?php echo $this->translate('Rating'); ?></a></th>

					<?php $class = ( $this->order == 'weight' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th style='width: 3%;' class="<?php echo $class ?>"><a href="javascript:void(0);" onclick="javascript:changeOrder('weight', 'ASC');"><?php echo $this->translate('Weight'); ?></a></th>

					<?php $class = ( $this->order == 'creation_date' ? 'admin_table_ordering admin_table_direction_' . strtolower($this->order_direction) : '' ) ?>
					<th class="<?php echo $class ?>" style='width: 3%;' align="left"><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'DESC');"><?php echo $this->translate('Creation Date'); ?></a></th>

					<th style='width: 3%;' align="left"><?php echo $this->translate('Options'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if( count($this->paginator) ): ?>
					<?php foreach( $this->paginator as $sitefaq ): ?>
						<tr>
							
							<td><input name='delete_<?php echo $sitefaq->faq_id;?>' type='checkbox' class='checkbox' value="<?php echo $sitefaq->faq_id ?>"/></td>

							<td class="admin_table_centered"><?php echo $sitefaq->faq_id ?></td>
							
							<?php 
								$owner_name = $this->user($sitefaq->owner_id)->username;
								$truncate_owner_name = Engine_Api::_()->sitefaq()->truncateText($owner_name, 10);
							?>		
							<td><?php echo $this->htmlLink($this->item('user', $sitefaq->owner_id)->getHref()	, $truncate_owner_name, array('target' => '_blank')) ?></td>

							<?php $sitefaq_title = Engine_Api::_()->sitefaq()->truncateText($sitefaq->getTitle(), 20);?>

							<td><?php echo $this->htmlLink($sitefaq->getHref(), $sitefaq_title, array('title' => $sitefaq->getTitle(), 'target' => '_blank')) ?></td>

							<?php if($sitefaq->featured == 1):?>
								<td align="center" class="admin_table_centered">
									<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitefaq', 'controller' => 'admin-site-faq', 'action' => 'featured', 'faq_id' => $sitefaq->faq_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitefaq/externals/images/featured.png', '', array('title'=> $this->translate('Un-featured FAQ')))) ?> 
								</td>       
							<?php else: ?>  
								<td align="center" class="admin_table_centered"> 
									<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitefaq', 'controller' => 'admin-site-faq', 'action' => 'featured', 'faq_id' => $sitefaq->faq_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitefaq/externals/images/unfeatured.png', '', array('title'=> $this->translate('Featured FAQ')))) ?>
								</td>
							<?php endif; ?>

							<?php if($sitefaq->approved == 1):?>
								<td align="center" class="admin_table_centered">
									<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitefaq', 'controller' => 'admin-site-faq', 'action' => 'approved', 'faq_id' => $sitefaq->faq_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitefaq/externals/images/approved.gif', '', array('title'=> $this->translate('Dis-approve FAQ')))) ?> 
								</td>       
							<?php else: ?>  
								<td align="center" class="admin_table_centered"> 
									<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitefaq', 'controller' => 'admin-site-faq', 'action' => 'approved', 'faq_id' => $sitefaq->faq_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitefaq/externals/images/disapproved.gif', '', array('title'=> $this->translate('Approve FAQ')))) ?>
								</td>
							<?php endif; ?>

							<?php if($sitefaq->draft == 1):?>
								<td align="center" class="admin_table_centered">
									<?php echo $this->htmlLink(array('route' => 'sitefaq_specific', 'action' => 'publish', 'faq_id' => $sitefaq->faq_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitefaq/externals/images/disapproved.gif', '', array('title'=> $this->translate('Publish FAQ'))), array('class' => 'smoothbox')) ?> 
								</td>       
							<?php else: ?>  
								<td align="center" class="admin_table_centered"> 
									<?php echo $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitefaq/externals/images/approved.gif', '', array('title'=> $this->translate('Published FAQ'))) ?>
								</td>
							<?php endif; ?>

							<td align="center" class="admin_table_centered"><?php echo $sitefaq->view_count ?></td>
							
							<td align="center" class="admin_table_centered"><?php echo $sitefaq->comment_count ?></td>

							<td align="center" class="admin_table_centered"><?php echo $sitefaq->like_count ?></td>

							<?php if($sitefaq->helpful > -1): ?>
								<?php $totalHelpsData = $this->helpTable->countHelpful($sitefaq->faq_id, 0);?>
								<td align="center" class="admin_table_centered"><span title="<?php echo $this->translate("%1s out of %2s marked as helpful.", $totalHelpsData['total_yes'], $totalHelpsData['total_marks'])?>"><?php echo $sitefaq->helpful ?></span></td>
							<?php else: ?>
								<td align="center" class="admin_table_centered"><span title="<?php echo $this->translate('No member marked this FAQ as helpful !');?>">---</span></td>
							<?php endif;?>

							<td align="center" class="admin_table_centered">
								<?php if(($sitefaq->rating > 0)):?>
									<?php 
										$currentRatingValue = $sitefaq->rating;
										$difference = $currentRatingValue- (int)$currentRatingValue;
										if($difference < .5) {
											$finalRatingValue = (int)$currentRatingValue;
										}
										else {
											$finalRatingValue = (int)$currentRatingValue + .5;
										}	
									?>
									<span class="list_rating_star">
										<?php for($x = 1; $x <= $sitefaq->rating; $x++): ?>
											<span class="rating_star_generic rating_star" title="<?php echo $this->translate('%1.1f rating', $finalRatingValue); ?>">
											</span>
										<?php endfor; ?>
										<?php if((round($sitefaq->rating) - $sitefaq->rating) > 0):?>
											<span class="rating_star_generic rating_star_half" title="<?php echo $this->translate('%1.1f rating', $finalRatingValue); ?>">
											</span>
										<?php endif; ?>
									</span>	
									<?php else: ?>
										<span title="<?php echo $this->translate('No one rated this FAQ at yet !');?>">---</span>
								<?php endif; ?>
							</td>

							<td>
								<?php if($sitefaq->weight): ?>
									<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitefaq', 'controller' => 'admin-site-faq', 'action' => 'weight', 'faq_id' => $sitefaq->faq_id, 'weight' => $sitefaq->weight), $sitefaq->weight, array('class' => 'smoothbox', 'title' => $this->translate('Click to Edit Weight'))) ?>
								<?php else: ?>
									<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitefaq', 'controller' => 'admin-site-faq', 'action' => 'weight', 'faq_id' => $sitefaq->faq_id, 'weight' => $sitefaq->weight), $this->translate('Assign'), array('class' => 'smoothbox', 'title' => $this->translate('Click to Add Weight'))) ?>
								<?php endif; ?>
							</td>
							
							<td><?php echo $sitefaq->creation_date ?></td>
							
							<td>
								<?php echo $this->htmlLink($sitefaq->getHref(), $this->translate('view'), array('target' => '_blank')) ?>
								|
								<?php echo $this->htmlLink(array('route' => 'sitefaq_specific', 'action' => 'edit', 'faq_id' => $sitefaq->getIdentity()), $this->translate('edit'), array('target' => '_blank')) ?>
								|
								<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sitefaq', 'controller' => 'admin-site-faq', 'action' => 'delete', 'faq_id' => $sitefaq->faq_id), $this->translate('delete'), array('class' => 'smoothbox')) ?>
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

<script type="text/javascript">
	var subcategories = function(category_id, sub, subcatname, subsubcate)
	{ 
    if($('subcategory_backgroundimage'))
		$('subcategory_backgroundimage').style.display = 'block';
    if($('subcategory_id'))
		$('subcategory_id').style.display = 'none';
    if($('subcategory_id-label'))
		$('subcategory_id-label').style.display = 'none';
    if($('subsubcategory_id'))
		$('subsubcategory_id').style.display = 'none';
    if($('subsubcategory_id-label'))
		$('subsubcategory_id-label').style.display = 'none';
    changesubcategory(sub,subsubcate)
	  var url = '<?php echo $this->url(array('action' => 'sub-category'), 'sitefaq_category', true);?>';
		en4.core.request.send(new Request.JSON({      	
			 url : url,
			data : {
				format : 'json',
				category_id_temp : category_id
				
			},
			onSuccess : function(responseJSON) {
        if($('subcategory_backgroundimage'))
				$('subcategory_backgroundimage').style.display = 'none';				
				clear('subcategory_id');				
	    	var  subcatss = responseJSON.subcats;
	      addOption($('subcategory_id')," ", '0');
        for (i=0; i< subcatss.length; i++) {
         addOption($('subcategory_id'), subcatss[i]['category_name'], subcatss[i]['category_id']);
           $('subcategory_id').value = sub;
        }				
				if(category_id == 0) {
					clear('subcategory_id');
          if($('subcategory_id'))
          $('subcategory_id').style.display = 'none';
          if($('subcategory_id-label'))
          $('subcategory_id-label').style.display = 'none';
          if($('subsubcategory_id'))
          $('subsubcategory_id').style.display = 'none';
          if($('subsubcategory_id-label'))
          $('subsubcategory_id-label').style.display = 'none';
				}
			}
		}));
	};
  
	function clear(ddName)
	{ 
		for (var i = (document.getElementById(ddName).options.length-1); i >= 0; i--) 
		{ 
			document.getElementById(ddName).options[ i ]=null; 
		} 
	}

	function addOption(selectbox,text,value )
	{
		var optn = document.createElement("OPTION");
		optn.text = text;
		optn.value = value;
		if(optn.text != '' && optn.value != '') {
			$('subcategory_id').style.display = 'block';
			$('subcategory_id-label').style.display = 'block';
			selectbox.options.add(optn);
		}
    else {
      $('subcategory_id').style.display = 'none';
      $('subcategory_id-label').style.display = 'none';
      selectbox.options.add(optn);
		}
	}

	var cat = '<?php echo $this->category_id ?>';
	if(cat != '') {
		var sub = '<?php echo $this->subcategory_id; ?>';
		var subcatname = "<?php echo $this->subcategory_name; ?>";
    var subsubcate = '<?php echo $this->subsubcategory_id; ?>';
		subcategories(cat, sub, subcatname,subsubcate);
	}

  function addSubOption(selectbox,text,value )
    {
      var optn = document.createElement("OPTION");
      optn.text = text;
      optn.value = value;
      if(optn.text != '' && optn.value != '') {
        $('subsubcategory_id').style.display = 'block';
         if($('subsubcategory_id-wrapper'))
          $('subsubcategory_id-wrapper').style.display = 'block';
         if($('subsubcategory_id-label'))
          $('subsubcategory_id-label').style.display = 'block';
        selectbox.options.add(optn);
      } else {
        $('subsubcategory_id').style.display = 'none';
         if($('subsubcategory_id-wrapper'))
          $('subsubcategory_id-wrapper').style.display = 'none';
         if($('subsubcategory_id-label'))
          $('subsubcategory_id-label').style.display = 'none';
        selectbox.options.add(optn);
      }

    }
    function changesubcategory(subcatid,subsubcate) {
      if($('buttons-wrapper')) {
		  	$('buttons-wrapper').style.display = 'none';
			}
      if(subcatid != 0)
      $('subsubcategory_backgroundimage').style.display = 'block';
      var url = '<?php echo $this->url(array('action' => 'subsub-category'), 'sitefaq_category', true);?>';
      var request = new Request.JSON({
        url : url,
        data : {
          format : 'json',
          subcategory_id_temp : subcatid
        },
        onSuccess : function(responseJSON) {
          $('subsubcategory_backgroundimage').style.display = 'none';
  	  		if($('buttons-wrapper')) {
				  	$('buttons-wrapper').style.display = 'block';
					}

          clear('subsubcategory_id');
          var  subsubcatss = responseJSON.subsubcats;

          addSubOption($('subsubcategory_id')," ", '0');
          for (i=0; i< subsubcatss.length; i++) {
            addSubOption($('subsubcategory_id'), subsubcatss[i]['category_name'], subsubcatss[i]['category_id']);
              $('subsubcategory_id').value = subsubcate;
          }
        }
      });
      request.send();
    }
  if($('subcategory_id'))
  $('subcategory_id').style.display = 'none';
  if($('subcategory_id-label'))
	$('subcategory_id-label').style.display = 'none';
  if($('subsubcategory_id'))
	$('subsubcategory_id').style.display = 'none';
  if($('subsubcategory_id-label'))
	$('subsubcategory_id-label').style.display = 'none';
</script>
