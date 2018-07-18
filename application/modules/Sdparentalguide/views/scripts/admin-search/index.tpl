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

<style type='text/css'>
.admin_table tr td a {
    padding-left: 5px;
}
.search_search #fieldset-searchgrp label{
    display: none;
}
.search_search .search_buttons {
    margin-top: 4px;
}
.create_search {
    margin-top: 5px;
    display: inline-block;
}
#global_content{
    border: 1px solid #ddd;
    padding: 10px;
}
.search_search {
    margin-bottom: 30px;
}
</style>


<script type='text/javascript'>
window.searchRequest = null;
function searchSearch(){
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

<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<div class='sd_layout_left'>
  <?php if( count($this->navigation2) ): ?>
    <div class='tabs_left'>
      <?php
        // Render the menu
        //->setUlClass()
        echo $this->navigation()->menu()->setContainer($this->navigation2)->render()
      ?>
    </div>
<?php endif; ?>
</div>

<div class='sd_layout_middle'>
    
<div class='admin_search search_search'>
    <?= $this->formFilter->render($this) ?>
</div>

<div class="admin_table_form">
<form id='multimodify_form' method="post" action="<?= $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 50%;' class='admin_table_centered'><?= $this->translate("Search Term"); ?></th>
        <th style='width: 50%;' class='admin_table_centered'><?= $this->translate("Action") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if( count($this->paginator) ): ?>
        <?php $api = Engine_Api::_()->sdparentalguide(); ?>
        <?php foreach( $this->paginator as $item ):?>
          <tr>
            <td class='admin_table_centered admin_table_bold'>
              <?= $item->name; ?>
            </td>
            <td class='admin_table_centered'>
                <a href='<?= $this->url(array('module' => 'sdparentalguide', 'controller' => 'alias', 'action' => 'index' ,'searchterm_id' => $item->getIdentity()));?>'>
                    <?= $this->translate("Alias") ?>
                </a>
                <a class='smoothbox' href='<?= $this->url(array('action' => 'delete','searchterm_id' => $item->getIdentity()));?>'>
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
    <?= $this->translate(array("%s Search Term found", "%s Search Terms found", $count),
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

</div>