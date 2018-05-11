<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>
<?php
$this->params['identity'] = $this->identity;
if (!$this->id)
    $this->id = $this->identity;
?>
<style>
	#module_id-element{
		max-height: 100px;
		overflow-y: auto;
		overflow-x: hidden;
	}
</style>
<?php 
$activityNotShown=array('friends_follow','logout',);
$activityNotShownDeletion=array('friends','login','profile_photo_update','nestedcomment_album','nestedcomment_album_photo','nestedcomment_blog','nestedcomment_classified','nestedcomment_event','nestedcomment_group','nestedcomment_poll','nestedcomment_siteevent_event','nestedcomment_siteevent_review','nestedcomment_sitestoreproduct_product','nestedcomment_sitestoreproduct_review','nestedcomment_sitevideo_channel','nestedcomment_video','siteeventticket_new_status','nestedcomment_sitereview_review','nestedcomment_sitereview_listing');
?>
<?php $credit_url =Engine_Api::_()->getApi("settings", "core")->getSetting("credit.manifestUrlP", "credits"); ?>

<?php if(!empty($this->levelcreditlimit->params)): ?>
	<div class="earn_credits_value"><?php echo $this->translate($this->viewer->getTitle()." you can earn ".$this->levelcreditlimit->params." ".$GLOBALS['credits']." more today by performing various activities.");
?></div>
<?php endif; ?>

<script type="text/javascript">
    var currentOrder = '<?php echo $this->order ?>';
    var currentOrderDirection = '<?php echo $this->order_direction ?>';
    var changeOrder = function (order, default_direction) {
        // Just change direction
        if (order == currentOrder) {
            $('order_direction').value = (currentOrderDirection == 'ASC' ? 'DESC' : 'ASC');
        } else {
            $('order').value = order;
            $('order_direction').value = default_direction;
        }

        $('filter_form').submit();
    };
</script>

<div class="search earn_search">
  <form name="credit_earn_credit_search_form" id="credit_earn_credit_search_form" method="post" class="global_form_box" action="">
    <input type="hidden" name="post_search" />

      <!-- <?php //if(!empty($this->modules->toArray())) : ?> -->	
         <div id="module_id-label"><p><?php echo $this->translate('Activities');?></p></div>
        <div id="module_id-element" >
        <select name="module_id" id="module_id">
        <option value=""></option>
        <?php foreach ($this->modules as $key => $modules) : 
        if($modules->name==$this->selectedModule):?>
        <option id="<?php echo $modules->name ?>" value="<?php echo $modules->name?>" selected><?php echo $this->translate($modules->label); ?></option>
      <?php endif; ?>
           <option id="<?php echo $modules->name ?>" value="<?php echo $modules->name?>"><?php echo $this->translate($modules->label); ?></option>
        <?php endforeach; ?>
        </select>
        </div>
    <!--   <?php //endif; ?>  -->
      <div>
        <button type="submit" name="submit"><?php echo $this->translate("Search") ?></button>
      </div>
  </form>
</div>

<div class='admin_search'>
    <?php echo $this->formFilter->render($this) ?>
</div>
</br> 
<?php if(count($this->paginator)==0) :?>
 <div class="tip"><span>
      <?php echo $this->translate("No Activity found.") ?></span>
   </div>

<?php endif; ?> 
<!-- <div class="mbot10">
  <?php //$count = $this->paginator->getTotalItemCount() ?>
  <?php //echo $this->translate(array("%s record found", "%s records found", $count), $count) ?>
</div> -->
<?php if(count($this->paginator)) :?>

  <div class="earn_credits_table">
  <table width="100%">
    <thead>
      <tr>
        <th width="50%" rowspan="2" style="word-break: break-all;"><?php echo $this->translate('Activity Type');?></th>
        <th width="50%" colspan="4"><?php echo $this->translate(ucfirst($GLOBALS['credit']).' Values');?></th>
      </tr>
      <tr>
      <th><?php echo $this->translate('First Activity');?></th>
      <th><?php echo $this->translate('Next Activities');?></th>
      <th><?php echo $this->translate('Max/Per Day');?></th>
      <th><?php echo $this->translate('Deduction');?></th>
    </tr>
    </thead>
    <tbody id="credits_manage_div<?php echo "_" . $this->id; ?>">
      
     <?php $diffModule; ?>
				<?php foreach ($this->paginator as $actionType): 
        if (!in_array($actionType->activity_type, $activityNotShown)):
        ?>
        <?php $module = $this->moduleLabels[$actionType->module];
        if($module!=$diffModule): 
        $diffModule=$module;
         ?>

        <tr class="credits_activity" ><td colspan="5" align="center"><?php echo $diffModule; ?></td></tr>

        <?php endif;?>

				<tr >
					<td style="word-break: break-all;">
              <?php 
              $column='language_'.$this->language;              
              if(empty($actionType->$column)){
              $activity_type = $this->string()->truncate($this->translate('ADMIN_ACTIVITY_TYPE_' . strtoupper($actionType->activity_type)), $this->textTruncation);
              if(!empty($activity_type)){
                    $activity_type = str_replace("(subject)","",$activity_type);
                    $activity_type = str_replace("(object)","",$activity_type);
                    echo $activity_type;
                    }
                    }
              else {
              echo $this->string()->truncate($actionType->$column, $this->textTruncation);
              } ?>
					</td>
					<td> 
						<div id='<?php echo $actionType->activity_type?>_first-wrapper' class="form-wrapper" style="display: block;">
							<div id='<?php echo $actionType->activity_type?>_first-element' class="form-element">
								<div name='<?php echo $actionType->activity_type?>_first' id='<?php echo $actionType->activity_type?>_first' style="text-align:center;"><?php echo $actionType->credit_point_first ?></div>
							</div>
						</div> 
					</td>
					<td> 
						<div id='<?php echo $actionType->activity_type?>_other-wrapper' class="form-wrapper" style="display: block;">

							<div id='<?php echo $actionType->activity_type?>_other-element' class="form-element">
								<div name='<?php echo $actionType->activity_type?>_other' id='<?php echo $actionType->activity_type?>_other' style="text-align:center;"><?php echo $actionType->credit_point_other ?></div>
							</div>
						</div> 
					</td>

					<td> 
						<div id='<?php echo $actionType->activity_type?>_limit-wrapper' class="form-wrapper" style="display: block;">
							<div id='<?php echo $actionType->activity_type?>_limit-element' class="form-element">
								<div name='<?php echo $actionType->activity_type?>_limit' id='<?php echo $actionType->activity_type?>_limit' style="text-align:center;"> <?php if(empty($actionType->limit_per_day)) : echo "No Limit"; else : echo $actionType->limit_per_day; endif; ?></div>
							</div>
						</div> 
					</td>

          <?php if (!in_array($actionType->activity_type, $activityNotShownDeletion)): ?>
          <td> 
            <div id='<?php echo $actionType->activity_type?>_delete-wrapper' class="form-wrapper" style="display: block;">
              <div id='<?php echo $actionType->activity_type?>_delete-element' class="form-element">
                <div name='<?php echo $actionType->activity_type?>_delete' id='<?php echo $actionType->activity_type?>_delete' style="text-align:center;"><?php echo $actionType->deduction ?></div>
              </div>
            </div> 
          </td>
          <?php else: ?> 
          <td> 
            <div id='<?php echo $actionType->activity_type?>_delete-wrapper' class="form-wrapper" style="display: block;">

              <div id='<?php echo $actionType->activity_type?>_delete-element' class="form-element">
                <div name='<?php echo $actionType->activity_type?>_delete' id='<?php echo $actionType->activity_type?>_delete' style="text-align:center;">N/A</div>
              </div>
            </div> 
          </td>
          <?php endif; ?>
				</tr>
				<?php endif; endforeach; ?>
    </tbody>
  </table>
  </div>
  
	
	<?php if (empty($this->is_ajax)) : ?>
            <div class = "seaocore_view_more mtop10" id="seaocore_view_more">
                <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => '', 'class' => 'buttonlink icon_viewmore')); ?>
            </div>
            <div class="seaocore_view_more" id="loding_image" style="display: none;">
                <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif' style='margin-right: 5px;' />
                <?php echo $this->translate("Loading ...") ?>
            </div>
            <div id="hideResponse_div"> </div>
  <?php endif; ?>
<?php endif;?>

<?php if (empty($this->is_ajax)) : ?>
        <script type="text/javascript">
            function viewMoreActivities()
            {
                var totalCount = '<?php echo $this->paginator->count(); ?>';
                var currentPageNumber = '<?php echo $this->paginator->getCurrentPageNumber(); ?>';
                if(totalCount == currentPageNumber) return false;

                $('seaocore_view_more').style.display = 'none';
                $('loding_image').style.display = '';
                var params = {
                    requestParams:<?php echo json_encode($this->params) ?>
                };
                en4.core.request.send(new Request.HTML({
                    method: 'get',
                    'url': en4.core.baseUrl + 'widget/index/mod/sitecredit/name/show-activity-credit',
                    data: $merge(params.requestParams, {
                        format: 'html',
                        subject: en4.core.subject.guid,
                        page: getNextPage(),
                        isajax: 1,
                        loaded_by_ajax: true
                    }),
                    evalScripts: true,
                    onSuccess: function (responseTree, responseElements, responseHTML, responseJavaScript) {
                        $('hideResponse_div').innerHTML = responseHTML;
                        var videocontainer = $('hideResponse_div').getElement('#credits_manage_div<?php echo "_" . $this->id; ?>').innerHTML;
                        $('credits_manage_div<?php echo "_" . $this->id; ?>').innerHTML = $('credits_manage_div<?php echo "_" . $this->id; ?>').innerHTML + videocontainer;
                        $('loding_image').style.display = 'none';
                        $('hideResponse_div').innerHTML = "";
                    }
                }));
                return false;
            }
        </script>
    <?php endif; ?>

    <?php if ($this->showContent == 3): ?>
        <script type="text/javascript">
            en4.core.runonce.add(function () {
                hideViewMoreLink('<?php echo $this->showContent; ?>');
            });
        </script>
    <?php elseif ($this->showContent == 2): ?>
        <script type="text/javascript">
            en4.core.runonce.add(function () {
                hideViewMoreLink('<?php echo $this->showContent; ?>');
            });
        </script>
    <?php else: ?>
        <script type="text/javascript">
            en4.core.runonce.add(function () {
                $('seaocore_view_more').style.display = 'none';
            });
        </script>
          <?php echo $this->paginationControl($this->paginator, null, null, array(
        'pageAsQuery' => true,
        'query' => $this->formValues,
      )); ?>
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
                function doOnScrollLoadChannel()
                { 
                   currentPageNumber = '<?php echo $this->paginator->getCurrentPageNumber(); ?>';
                    if (typeof ($('seaocore_view_more').offsetParent) != 'undefined') {
                        var elementPostionY = $('seaocore_view_more').offsetTop;
                    } else {
                        var elementPostionY = $('seaocore_view_more').y;
                    }
                    if (elementPostionY <= window.getScrollTop() + (window.getSize().y - 40)) {

                        if ((totalCount != currentPageNumber) && (totalCount != 0))
                            viewMoreActivities();
                    }
                }
                window.onscroll = doOnScrollLoadChannel;

            } else if (showContent == 2) {
                var view_more_content = $('seaocore_view_more');
                view_more_content.setStyle('display', '<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->totalCount == 0 ? 'none' : '' ) ?>');
                view_more_content.removeEvents('click');
                view_more_content.addEvent('click', function () {
                    viewMoreActivities();
                });
            }
        }
</script>
