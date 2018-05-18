<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitefaq
 * @copyright  Copyright 2011-2012 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 6590 2012-18-05 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
	$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
  	              . 'application/modules/Sitefaq/externals/styles/style_sitefaq.css');
?>
<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<script type="text/javascript" >

function owner(thisobj) {
	var Obj_Url = thisobj.href;
	Smoothbox.open(Obj_Url);
}
</script>

<?php if($this->scrollButton): ?>
	<a id="back_to_top_sitefaq_button" href="#" class="Offscreen" title="<?php echo $this->translate("Scroll to Top"); ?>">
		<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitefaq/externals/images/arrow-top.png" alt="" />
	</a>
<?php endif; ?>

<script type="text/javascript">

	<?php if($this->scrollButton): ?>

		window.addEvent('scroll', function (){
			var element=$("back_to_top_sitefaq_button");  
			if( typeof( $('browse_faq_top').offsetParent ) != 'undefined' ) {
				var elementPostionY=$('browse_faq_top').offsetTop;
			}else{
				var elementPostionY=$('browse_faq_top').y; 
			}
			if(elementPostionY + window.getSize().y < window.getScrollTop()){
				if(element.hasClass('Offscreen'))
					element.removeClass('Offscreen');
			}else if(!element.hasClass('Offscreen')){       
				element.addClass('Offscreen');
			}
		});

		en4.core.runonce.add(function() {
		
			<?php
				if (isset($this->layout()->siteinfo['identity'])) {
					$identity = $this->layout()->siteinfo['identity'];
				} else {
					$identity = $request->getModuleName() . '-' . $request->getControllerName() . '-' . $request->getActionName();
				}
			?>
			var scroll = new Fx.Scroll('global_page_<?php echo $identity ?>', {
				wait: false,
				duration: 750,
				offset: {'x': -200, 'y': -100},
				transition: Fx.Transitions.Quad.easeInOut
			});

			$('back_to_top_sitefaq_button').addEvent('click', function(event) {
				event = new Event(event).stop();
				scroll.toElement('browse_faq_top');       
			});
		});
	<?php endif; ?>

	function faq_show(id) {
		if($('faq_hide_'+id).style.display == 'block') {
			$('faq_hide_'+id).style.display = 'none';
			$('faq_expand_'+id).style.display = 'block';

			$('faq_arrow_hide_'+id).style.display = 'none';
			$('faq_arrow_expand_'+id).style.display = 'block';
		} else {
			$('faq_hide_'+id).style.display = 'block';
			$('faq_expand_'+id).style.display = 'none';

			$('faq_arrow_hide_'+id).style.display = 'block';
			$('faq_arrow_expand_'+id).style.display = 'none';
		}
	}

  function faq_expand_hide(value) {

		if(value == 2) {
			$$('.faq_details_hide').each(function(el){
				el.style.display = 'none';
			});

			$$('.faq_details_expand').each(function(el){
				el.style.display = 'block';
			});

			$('faq_hide').setStyle('display', 'block');
			$('faq_expand').setStyle('display', 'none');
		}
		else if(value == 1) {
			$$('.faq_details_hide').each(function(el){
				el.style.display = 'block';
			});

			$$('.faq_details_expand').each(function(el){
				el.style.display = 'none';
			});

			$('faq_hide').setStyle('display', 'none');
			$('faq_expand').setStyle('display', 'block');
		}
  }

	en4.core.runonce.add(function() {

		$$('.faq_details_hide').each(function(el){
			el.style.display = 'block';
		});

		$$('.faq_details_expand').each(function(el){
			el.style.display = 'none';
		});

		if($('faq_hide'))
		$('faq_hide').setStyle('display', 'none');

	});

	function helpfulAction(faq_id, viewer_id, helpful,option_id,count) {

    if(helpful == 1 && count != 0) {
			document.getElementById('show_option_'+faq_id).style.display = 'block';
      document.getElementById('showbox_'+faq_id).style.display = 'none';
    }

    if(option_id != '') {
     var helpful = 1;
    }    

    if(helpful == 2 || option_id != '' || count == 0) {
			(new Request.HTML({
				'url' : '<?php echo $this->url(array('module' => 'sitefaq', 'controller' => 'index', 'action' => 'helpful'), 'default', true) ?>',
				'data' : {
					'format' : 'html',
					'helpful' : helpful,
					'faq_id': faq_id,
          'option_id' : option_id,
					'viewer_id': viewer_id,
					'statisticsHelpful': '<?php echo $this->statisticsHelpful; ?>' 
				},
				onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
					$('helpful_content_'+faq_id).innerHTML = responseHTML;
          document.getElementById('show_option_'+faq_id).style.display = 'none';
          document.getElementById('showbox_'+faq_id).style.display = 'none';
          document.getElementById('showbox_'+faq_id).innerHTML = '<div class="success_message"><?php echo $this->translate('Thanks for your feedback!') ?></div>';
          document.getElementById('showbox_'+faq_id).style.display = 'block';
				}
			})).send();
    }
	}
</script>

<?php if($this->scrollButton): ?>
	<div id='browse_faq_top'></div>
<?php endif; ?>

	<div class="sitefaq_breadcrumbs">
  
  <?php if(empty($this->is_ajax)):?>
    <div> 
      <?php echo $this->translate(array('%s faq found.', '%s faqs found.', $this->paginator->getTotalItemCount()),$this->locale()->toNumber($this->paginator->getTotalItemCount())); ?>
    </div>
   <?php endif;?>
   <?php if(!empty($this->first_category_name) || (Count($this->paginator) > 0 && empty($this->linked)) || (isset($this->formValues['tag']) && !empty($this->formValues['tag']) && isset($this->formValues['tag_id']) && !empty($this->formValues['tag_id']))): ?>
		<?php if(Count($this->paginator) > 0):?>
			<div class="brd-r fright">
				<?php if(empty($this->linked)): ?>
					<div id="faq_expand" class="fleft">
						<a href='javascript:void(0);' onclick="javascript:faq_expand_hide('2');"><?php echo $this->translate("Expand All");?></a>
					</div>
					<div id="faq_hide" class="fleft">
						<a href='javascript:void(0);' onclick="javascript:faq_expand_hide('1');"><?php echo $this->translate("Hide All");?></a>
					</div>

					<?php if(!empty($this->can_print)): ?>
						<?php if(empty($this->linked)): ?><div class="fleft brd-sep">-</div><?php endif; ?>
						<div class="fleft">
							<a target="_blank" href="<?php echo $this->url(array( 'action' => 'print'),"sitefaq_general",true)."?".$this->query_string; ?>"><?php echo $this->translate('Print');?></a>
						</div>
					<?php endif; ?>	
				<?php endif; ?>
			</div>	
		<?php endif; ?>
		
		<div class="sitefaq_breadcrumbs_txt">

			<?php
				$this->first_category_name = $this->translate($this->first_category_name);
				$this->first_subcategory_name = $this->translate($this->first_subcategory_name);
				$this->first_subsubcategory_name = $this->translate($this->first_subsubcategory_name);
			?>
			<?php if ($this->first_category_name != '') :?>
				<b><?php echo $this->translate("Browse FAQs"); ?></b>
				<?php echo '<span class="brd-sep bold seaocore_txt_light">&raquo;</span>';?>  
				<?php echo $this->htmlLink($this->url(array('category' => $this->first_category_id, 'categoryname' => $this->categoryTable->getCategorySlug($this->first_category_name)), 'sitefaq_general_category'), $this->translate($this->first_category_name)) ?>

				<?php if($this->first_subcategory_name != ''):?> 
					<?php echo '<span class="brd-sep bold seaocore_txt_light">&raquo;</span>';?>  
		
					<?php echo $this->htmlLink($this->url(array('category' => $this->first_category_id, 'categoryname' => $this->categoryTable->getCategorySlug($this->first_category_name), 'subcategory' => $this->first_subcategory_id, 'subcategoryname' => $this->categoryTable->getCategorySlug($this->first_subcategory_name)), 'sitefaq_general_subcategory'), $this->translate($this->first_subcategory_name)) ?>

					<?php if($this->first_subsubcategory_name != ''): ?>
						<?php echo '<span class="brd-sep bold seaocore_txt_light">&raquo;</span>';?>

						<?php echo $this->htmlLink($this->url(array('category' => $this->first_category_id, 'categoryname' => $this->categoryTable->getCategorySlug($this->first_category_name), 'subcategory' => $this->first_subcategory_id, 'subcategoryname' => $this->categoryTable->getCategorySlug($this->first_subcategory_name),'subsubcategory' => $this->first_subsubcategory_id, 'subsubcategoryname' => $this->categoryTable->getCategorySlug($this->first_subsubcategory_name)), 'sitefaq_general_subsubcategory'),$this->translate($this->first_subsubcategory_name)) ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php endif; ?>

			<?php if(((isset($this->formValues['tag']) && !empty($this->formValues['tag']) && isset($this->formValues['tag_id']) && !empty($this->formValues['tag_id'])))): ?>
				<?php $tag_value = $this->formValues['tag']; $tag_value = urlencode($tag_value); $tag_value_id = $this->formValues['tag_id']; $browse_url = $this->url(array('action' => 'browse'), 'sitefaq_general', true)."?tag=$tag_value&tag_id=$tag_value_id";?>
				<?php if($this->first_category_name):?><br /><?php endif; ?>
				<?php echo $this->translate("Showing FAQs tagged with: ");?>
				<b><a href='<?php echo $browse_url;?>'>#<?php echo $this->formValues['tag'] ?></a>
				<?php if($this->current_url2): ?>
					<a href="<?php echo $this->url(array( 'action' => 'browse'),"sitefaq_general",true)."?".$this->current_url2; ?>"><?php echo $this->translate('(x)');?></a></b>
				<?php else: ?>
					<a href="<?php echo $this->url(array( 'action' => 'browse'),"sitefaq_general",true); ?>"><?php echo $this->translate('(x)');?></a></b>
				<?php endif; ?>
			<?php endif; ?>
		</div>
   <?php endif; ?>
	</div>

<?php if( count($this->paginator) > 0 ): ?>
<div id="list_view">
  <ul class='faq_list'>
    <?php foreach( $this->paginator as $sitefaq ): ?>
     <li>
      <div class="faq_list_img">

       <div id='<?php echo "faq_arrow_hide_$sitefaq->faq_id"?>' class='faq_details_hide' >
        <?php if(empty($this->linked)): ?>
         <a href="javascript:void(0);" onClick="faq_show('<?php echo $sitefaq->faq_id;?>');" class="sitefaq_icon sitefaq_icon_exp"></a>
        <?php endif; ?>
       </div>

       <div id='<?php echo "faq_arrow_expand_$sitefaq->faq_id"?>' class='faq_details_expand sitefaq_icon sitefaq_icon_coll'>
        <a href="javascript:void(0);" onClick="faq_show('<?php echo $sitefaq->faq_id;?>');"></a>
       </div>
      </div>
      <div class="faq_list_info">
       <div class="faq_list_info_top">
        <?php if($sitefaq->featured == 1): ?>
         <span class="sitefaq_icon sitefaq_icon_featured" title="<?php echo $this->translate('Featured'); ?>"></span>
        <?php endif;?>
        <?php if(($sitefaq->rating > 0) && $this->statisticsRating):?>
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
        <?php endif; ?>

         <?php if(empty($this->linked)): ?>
          <div class="faq_list_title faq_list_title_exp">
          <a href="<?php echo $sitefaq->getHref(); ?>" onClick="faq_show('<?php echo $sitefaq->faq_id;?>'); return false;"><?php echo $sitefaq->getTitle();?></a>
          </div></div>
          <div id='<?php echo "faq_hide_$sitefaq->faq_id"?>' class="faq_list_info_blurb seaocore_txt_light faq_details_hide">
           <?php echo $this->sitefaq_api->truncateText($sitefaq->getFullDescription(), 140); ?>
          </div>
          <div id='<?php echo "faq_expand_$sitefaq->faq_id"?>' class="faq_details_expand sitefaq_faq_body">

         <?php else: ?>
          <div class="faq_list_title">
          <?php echo $this->htmlLink($sitefaq->getHref(), $sitefaq->getTitle()) ?>
          </div></div>
          <div class="sitefaq_faq_body">

         <?php endif; ?>

        <?php if(!empty($this->truncation)): ?>
         <?php echo $this->sitefaq_api->truncateText($sitefaq->getFullDescription(), $this->truncation); ?>
        <?php else: ?>
         <?php echo $sitefaq->getFullDescription(); ?>
        <?php endif; ?>


        <?php
         //GET VIEWER DETAIL
         $viewer = Engine_Api::_()->user()->getViewer();
         $this->viewer_id = $viewer_id = $viewer->getIdentity();

         //GET USER LEVEL ID
         if (!empty($viewer_id)) {
          $level_id = $viewer->level_id;
         } else {
          $level_id = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchRow(array('type = ?' => "public"))->level_id;
         }

         //SEND FAQ ID TO TPL
         $this->faq_id = $sitefaq->faq_id;

         //SEND FAQ SLUG TO TPL
         $this->faq_slug = $sitefaq->getSlug();

         //HELPFUL PRIVACY
         $this->helpful_allow = Engine_Api::_()->authorization()->getPermission($level_id, 'sitefaq_faq', 'helpful');

         //GET HELPFUL TABLE
         $tableHelp = Engine_Api::_()->getDbTable('helps', 'sitefaq');

         //CHECK FOR PREVIOUS MARK
         if(!empty($this->helpful_allow)) {
          $this->previousHelpMark = $tableHelp->getHelpful($sitefaq->faq_id, $viewer_id);
         }

         //TOTAL HELPFUL COUNT
         $this->totalHelpCount = $tableHelp->countHelpful($sitefaq->faq_id, 1);

                //TOTAL HELPFUL COUNT
         $totalVoteCount = $tableHelp->countHelpful($sitefaq->faq_id, 0);
         $this->totalVoteCount = $totalVoteCount['total_marks'];
        ?>
        <div class="faq_list_info_links">

         <?php if(empty($this->linked)):?>
          <?php if(!empty($this->url_category_id)): ?>
           <?php echo $this->htmlLink($this->url(array('faq_id' => $sitefaq->faq_id, 'slug' => $sitefaq->getSlug(), 'category_id' => $this->url_category_id, 'subcategory_id' => $this->url_subcategory_id, 'subsubcategory_id' => $this->url_subsubcategory_id), 'sitefaq_view'), $this->translate('Permalink')) ?> |
          <?php else: ?>
           <?php echo $this->htmlLink($sitefaq->getHref(), $this->translate('Permalink')) ?> |
          <?php endif; ?>
         <?php endif;?>

         <?php if(!empty($this->viewer_id) && $sitefaq->draft == 0 && $sitefaq->approved == 1 && $sitefaq->search == 1 && !empty($this->can_share)): ?>
          <?php echo $this->htmlLink(Array('module'=> 'activity', 'controller' => 'index', 'action' => 'share', 'route' => 'default', 'type' => 'sitefaq_faq', 'id' => $sitefaq->faq_id, 'format' => 'smoothbox'), $this->translate("Share"), array('onclick' => 'owner(this);return false')); ?> |
         <?php endif;?>

         <?php if($this->statisticsComment):?>
          <?php echo $this->translate(array('%s comment', '%s comments', $sitefaq->comment_count), $this->locale()->toNumber($sitefaq->comment_count)) ?>,
          <?php echo $this->translate(array('%s like', '%s likes', $sitefaq->like_count), $this->locale()->toNumber($sitefaq->like_count)) ?><?php if($this->statisticsView): ?>,<?php endif;?>
         <?php endif;?>

         <?php if($this->statisticsView):?>
          <?php echo $this->translate(array('%s view', '%s views', $sitefaq->view_count), $this->locale()->toNumber($sitefaq->view_count)) ?>
         <?php endif; ?>
             </div>

        <?php if(!empty($this->helpful_allow) || (!empty($this->statisticsHelpful) && $sitefaq->helpful >= 0)): ?>
         <div id="helpful_content_<?php echo $this->faq_id;?>" class="sitefaq_helpful_content">
          <?php include APPLICATION_PATH . '/application/modules/Sitefaq/views/scripts/helpful_content.tpl'; ?>
         </div>
        <?php endif; ?>

        <?php if(empty($this->linked)):?>
        <div class="faq_list_btm_link">
         <a href="javascript:void(0);" onClick="faq_show('<?php echo $sitefaq->faq_id;?>');"><?php echo $this->translate("[Hide]");?></a>
        </div>
        <?php endif; ?>
       </div>
      </div>
     </li>
    <?php endforeach; ?>
   </ul>
</div>
	<?php if( count($this->paginator) > 1 ): ?>
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
	<?php endif; ?>
<?php elseif(count($this->paginator) <= 0 && ((isset($this->formValues['search_form']) && !empty($this->formValues['search_form'])) || !empty($this->first_category_name) || (isset($this->formValues['search']) && !empty($this->formValues['search'])) || (isset($this->formValues['tag']) && !empty($this->formValues['tag'])))):?>
	<div class="tip">
		<span>
			<?php echo $this->translate('No FAQs matching with that criteria could be found. Please try a different search.');?>
			<?php if(!empty($this->can_create)): ?>
				<?php echo $this->translate('Be the first to %1$screate%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sitefaq_general").'">', '</a>'); ?>
			<?php endif; ?>
		</span>
	</div>
<?php else: ?>
	<div class="tip">
		<span>
			<?php echo $this->translate('No FAQs has been found.');?>
			<?php if(!empty($this->can_create)): ?>
				<?php echo $this->translate('Be the first to %1$screate%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sitefaq_general").'">', '</a>'); ?>
			<?php endif; ?>
		</span>
	</div>
<?php endif; ?>

<?php if (empty($this->is_ajax)) : ?>
  <script type="text/javascript">
    function viewMoreFaq()
    {
      $('seaocore_view_more').style.display = 'none';
      $('loding_image').style.display = '';
      var params = {
        requestParams:<?php echo json_encode($this->params) ?>
      };
      setTimeout(function() {
        en4.core.request.send(new Request.HTML({
          method: 'get',
          'url': en4.core.baseUrl + 'widget/index/mod/sitefaq/name/browse-sitefaqs',
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
            $('list_view').getElement('.faq_list').innerHTML = $('list_view').getElement('.faq_list').innerHTML + $('hideResponse_div').getElement('.faq_list').innerHTML;
            $('hideResponse_div').innerHTML = '';
            $('loding_image').style.display = 'none';
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
  <?php if($request->getModuleName() == 'siteadvsearch'):?>
			 <?php echo $this->paginationControl($this->paginator, null, array("pagination/pagination.tpl", "siteadvsearch"), array("query" => $this->formValues,'pageAsQuery' => true)); ?>
			<?php else:?>
			  <?php echo $this->paginationControl($this->paginator, null, null, array('query' => $this->formValues,'pageAsQuery' => true,)); ?>
			<?php endif;?>
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
            viewMoreFaq();
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
        viewMoreFaq();
      });
    }
  }
</script>
