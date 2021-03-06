<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<h2><?= $this->translate("Parental Guidance Customizations") ?></h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<style type='text/css'>
#category-wrapper,
#subcategory-wrapper{
    display: none;
}    
.admin_table tr td a {
    padding-left: 5px;
}
#filter_form div.badges {
    margin-left: 15px !important;
}
#fieldset-grp_sync {
    margin-left: 20px;
}
#fieldset-grp_sync .sync_tags {
    margin-top: 10px;
}
</style>
<div class='admin_search'>
    <?= $this->formFilter->render($this) ?>
</div>

<script type='text/javascript'>
function selectAll()
{
  var i;
  var multimodify_form = $('multimodify_form');
  var inputs = multimodify_form.elements;
  for (i = 1; i < inputs.length - 1; i++) {
    if (!inputs[i].disabled) {
      inputs[i].checked = inputs[0].checked;
    }
  }
}    
window.searchRequest = null;
window.searchTimeout = null;
en4.core.runonce.add(function(){
    var form = $("filter_form");
    var nameElement = form.getElement("#name");
    nameElement.addEvent("keyup",function(){
        if(window.searchTimeout){
            clearTimeout(window.searchTimeout);
        }
        window.searchTimeout = setTimeout(searchBadges(),300);
    });
});   
function searchTopics(){
    if(window.searchRequest){
        window.searchRequest.cancel();
    }
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var form = $("filter_form");
    var formData = form.toQueryString().parseQueryString();
    formData.format = 'html';
    
    window.searchRequest = new Request.HTML({
        url: '<?= $this->url(); ?>',
        data: formData,
        onRequest: function(){
            loader.inject(form,"bottom");
        },
        onCancel: function(){
            loader.destroy();
        },
        onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript){
          loader.destroy();
          var div = new Element("div",{
              html: responseHTML
          });
          var data = $$(".admin_table_form");
          var responseData = div.getElement(".admin_table_form");
          responseData.inject(data[0],"after");
          data.destroy();
          
          var pagination = $$(".admin_results");
          var responsePagination = div.getElement(".admin_results");
          responsePagination.inject(pagination[0],"after");
          pagination.destroy();
          Smoothbox.bind(responseData);
        }
    });
    window.searchRequest.send();
}
</script>

<br />

<div class="admin_table_form">
<form id='multimodify_form' method="post" action="<?= $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
  <button type="submit"><?= $this->translate("Delete Selected"); ?></button>
  <br><br>
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 5%;' class='admin_table_centered'>
            <input onclick="selectAll()" type='checkbox' class='checkbox' id="select-all" style="display:none;">
            <label for="select-all" class='admin_table_centered'><?= $this->translate("Select"); ?></label>
        </th>
        <th style='width: 50%;' class='admin_table_centered'><?= $this->translate("Topic Name"); ?></th>
        <th style='width: 10%;' class='admin_table_centered'><?= $this->translate("Allow Badges"); ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("Featured") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("Icon") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("Action") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if( count($this->paginator) ): ?>
        <?php $api = Engine_Api::_()->sdparentalguide(); ?>
        <?php foreach( $this->paginator as $item ):?>
          <tr>
            <td><input class="sd_select_checkbox" name="topic_ids[]" data-id='<?= $item->getIdentity(); ?>' value=<?= $item->getIdentity();?> type='checkbox' class='checkbox'></td>
            <td class='admin_table_centered admin_table_bold'>
              <?= $item->getTitle(); ?>
            </td>
            <td class='admin_table_centered'>
                <input type="radio" class="sd_radio_badges" disabled <?= ( $item->badges ? 'checked=checked' : '' ); ?>>
            </td>
            <td class='admin_table_centered'>
                <input type="radio" class="sd_radio_featured" disabled <?= ( $item->featured ? 'checked=checked' : '' ); ?>>
            </td>
            <td class='admin_table_centered'>
                <?php if($item->photo_id): ?>
                    <img src='<?= $item->getPhotoUrl("thumb.icon"); ?>' alt='<?= $item->getTitle(); ?>'/>
                <?php endif; ?>
            </td>
            <td class='admin_table_centered'>
                <a class='smoothbox' href='<?= $this->url(array( 'action' => 'edit','topic_id' => $item->getIdentity()));?>'>
                    <?= $this->translate("Edit") ?>
                </a>
                <?php if($item->approved): ?>
                    <a href='javascript:void(0);' onclick="approveTopic(this,'<?= $item->getIdentity(); ?>','0');">
                        <?= $this->translate("Inactivate") ?>
                    </a>
                <?php else: ?>
                    <a href='javascript:void(0);' onclick="approveTopic(this,'<?= $item->getIdentity(); ?>','1');">
                        <?= $this->translate("Activate") ?>
                    </a>
                <?php endif; ?>                
                <a class='smoothbox' href='<?= $this->url(array('action' => 'delete','topic_id' => $item->getIdentity()));?>'>
                    <?= $this->translate("Delete") ?>
                </a>
            </td>            
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
  <br />
</form>
</div>


<br />

<div class='admin_results'>
  <div>
    <?php $count = $this->paginator->getTotalItemCount() ?>
    <?= $this->translate(array("%s topic found", "%s topics found", $count),
        $this->locale()->toNumber($count)) ?>
  </div>
  <div>
    <?= $this->paginationControl($this->paginator, null, null, array(
      'pageAsQuery' => true,
      'query' => $this->formValues,
      //'params' => $this->formValues,
    )); ?>
  </div>
</div>

<script type="text/javascript"> 
en4.core.runonce.add(function(){
    loadCategories($("category_id"));
    loadSubCategories();
});
function loadSubCategories(){
    var categoryId = $("category_id").get("value");
    if(categoryId.length <= 0){
        $$("#filter_form .sd_inline_field.subcategory").setStyle("display","none");
        return;
    }
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var url = '<?= $this->url(array('action' => 'get-subcategories'), 'sdparentalguide_general', true) ?>';
    var req = new Request.JSON({
      url: url,
      data: {
          category_id: categoryId,
          format: 'json'
      },
      onRequest: function(){
          loader.inject($("category_id"),"after");
      },
      onFail: function(){
          loader.destroy();
      },
      onCancel: function(){
          loader.destroy();
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          $("subcategory_id").empty();
          if(responseJSON.status && responseJSON.categories.length > 0){
              var options = "<option value='0'><?= $this->translate('Sub Category'); ?></option>";
              Object.each(responseJSON.categories,function(category){
                  options += "<option value='"+category.id+"'>"+category.title+"</option>";
              });
              $("subcategory_id").set("html",options);
              $$("#filter_form .sd_inline_field.subcategory").setStyle("display","inline-block");
          }else{
              $$("#filter_form .sd_inline_field.subcategory").setStyle("display","none");
          }
      }
    });
    req.send();
}
function loadCategories(element){
  var loader = en4.core.loader.clone();
  loader.addClass("sd_loader");
  $("category-wrapper").setStyle("display","none");
  $$(".sd_listing_search .sd_inline_field.subcategory").setStyle("display","none");
  var url = '<?= $this->url(array('action' => 'get-categories'), 'sdparentalguide_general', true) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          listing_type: $(element).get("value"),
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onFail: function(){
          loader.destroy();
      },
      onCancel: function(){
          loader.destroy();
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          $("category_id").empty();
          if(responseJSON.status && responseJSON.categories.length > 0){
              $("category_id").set("html","");
              var options = "<option value='0'><?= $this->translate('Category'); ?></option>";
              Object.each(responseJSON.categories,function(category){
                  options += "<option value='"+category.id+"'>"+category.title+"</option>";
              });
              $("category_id").set("html",options);
              $("category-wrapper").setStyle("display","inline-block");
          }
      }
  });
  req.send();
}
function approveTopic(element,topicId,status){
    var url = en4.core.baseUrl+"admin/sdparentalguide/topics/approve/topic_id/"+topicId;
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    $(element).setStyle("display","none");
    var req = new Request.JSON({
        url: url,
        data: {
            format: 'json',
            status: status
        },
        onRequest: function(){
            loader.inject($(element),"after");
        },
        onCancel: function(){
            loader.destroy();
        },
        onSuccess: function(responseJSON){
          loader.destroy();
          if(!responseJSON.status){
              return;
          }
          var row = $(element).getParent("tr");
          var options = row.getElement(".table_options");
          var anchor = new Element("a",{
                href: 'javascript:void(0);',
                html: "<?= $this->translate('Inactivate'); ?>",
                onclick: "approveTopic(this,'"+topicId+"','0');"
          });
          if(status == '0'){
            anchor = new Element("a",{
                href: 'javascript:void(0);',
                html: "<?= $this->translate('Activate'); ?>",
                onclick: "approveTopic(this,'"+topicId+"','1');"
            });
          }
          anchor.inject($(element),"after");
          $(element).destroy();
          Smoothbox.bind(row);
        }
    });
    req.send();
}
function bulkApprove(element,status){
  var multimodify_form = $('multimodify_form');
  var inputs = multimodify_form.getElements("tbody input[type=checkbox]:checked");
  var ids = [];
  inputs.each(function(input){
      ids.push(input.get("data-id"));
  });
  if(ids.length <= 0){
      return;
  }
  var loader = en4.core.loader.clone();
  loader.addClass("sd_loader");
  
  var url = '<?= $this->url(array('action' => 'approve-bulk')) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          topic_ids: ids,
          status: status,
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          window.location.reload();
      }
  });
  req.send();  
}
function bulkAllowBadges(element,status){
  var multimodify_form = $('multimodify_form');
  var inputs = multimodify_form.getElements("tbody input[type=checkbox]:checked");
  var ids = [];
  inputs.each(function(input){
      ids.push(input.get("data-id"));
  });
  if(ids.length <= 0){
      return;
  }
  var loader = en4.core.loader.clone();
  loader.addClass("sd_loader");
  
  var url = '<?= $this->url(array('action' => 'bulk-badges')) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          topic_ids: ids,
          status: status,
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          window.location.reload();
      }
  });
  req.send();  
}
</script>