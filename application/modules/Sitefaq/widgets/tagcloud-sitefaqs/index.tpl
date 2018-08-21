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

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl .'application/modules/Sitefaq/externals/scripts/core.js'); ?>
<?php if($this->loaded_by_ajax && !$this->isajax):?>
<script>

  var browsetagparams = {
            requestParams:<?php echo json_encode($this->allParams) ?>,
            responseContainer: $$('.layout_sitefaq_tagcloud_sitefaqs'),
            requestUrl: en4.core.baseUrl + 'widget/index/content_id/' + <?php echo sprintf('%d', $this->identity) ?>,
            loading: false
        }
    
  en4.core.runonce.add(function() {  
    browsetagparams.responseContainer.each(function(__ind, element) {   
     new Element('div', {
        'class': 'sitefaq_profile_loading_image'
      }).inject(element);
    });
    en4.sitefaq.ajaxTab.sendReq(browsetagparams);
  });

 </script>           
<?php endif;?>

<?php if($this->showContent):?>
    <script type="text/javascript">

      var tagCloudAction = function(tag, tag_id){
        if($('filter_form')) {
           var form = document.getElementById('filter_form');
          }else if($('filter_form_tag')){
            var form = document.getElementById('filter_form_tag');
        }

        form.elements['tag'].value = tag;
        form.elements['tag_id'].value = tag_id;
        form.submit();
      }
    </script>

    <form id='filter_form_tag' class='global_form_box' method='get' action='<?php echo $this->url(array('action' => 'browse'), 'sitefaq_general', true) ?>' style='display: none;'>
      <input type="hidden" id="tag" name="tag"  value=""/>
      <input type="hidden" id="tag_id" name="tag_id" value=""/>
    </form>

    <?php if($this->owner_id): ?>
      <h3><?php echo $this->translate($this->owner->getTitle()), $this->translate("'s Tags") ?></h3>
    <?php else: ?>
      <h3><?php echo $this->translate('Popular Tags ');?>(<?php echo $this->count_only ?>)</h3>
    <?php endif; ?>
    <ul class="seaocore_sidebar_list">
      <li>
        <div>
          <?php foreach($this->tag_array as $key => $frequency):?>
            <?php $string =  $this->string()->escapeJavascript($key); ?>
            <?php $step = $this->tag_data['min_font_size'] + ($frequency - $this->tag_data['min_frequency'])*$this->tag_data['step'] ?>
            <a href='javascript:void(0);' onclick='javascript:tagCloudAction("<?php echo $string; ?>", <?php echo $this->tag_id_array[$key]; ?>);' style="font-size:<?php echo $step ?>px;" title=''><?php echo $key ?><sup><?php echo $frequency ?></sup></a> 
          <?php endforeach;?>
        </div>		
      </li>
      <li><?php echo $this->htmlLink(array('action' => 'tagscloud', 'route' => 'sitefaq_general'), $this->translate('Explore Tags &raquo;'), array('class'=>'more_link')) ?></li>
    </ul>
<?php endif; ?>
