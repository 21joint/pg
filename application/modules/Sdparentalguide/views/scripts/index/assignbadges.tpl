<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>
<?php
  if (APPLICATION_ENV == 'production')
    $this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.min.js');
  else
    $this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Observer.js')
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.js')
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Local.js')
      ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Request.js');
?>
<style type="text/css">
.sd-badges-badges-list form>div {
    max-width: 180px;
    float: left;
    margin-right: 10px;
}
.sd-badges-badges-list form {
    overflow: hidden;
}
.sd-badges-badges-list #level_id{
    padding: 5px 8px;
}
.sd-badges-list-table table{
    width: 100%;
    border: 1px solid #ddd;
}
.admin_results>div {
    display: inline-block;
    vertical-align: middle;
}
.admin_results .pages{
    margin-left: 20px;
    margin-top: 0px;
}
</style>
<h2><?php echo $this->translate("Assign Badges") ?></h2>

<script type='text/javascript'>
window.searchTimeout = null;
en4.core.runonce.add(function(){
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    
    var form = $("filter_form");
    var usernameElement = form.getElement("#username");
    usernameElement.addEvent("keyup",function(){
        if(window.searchTimeout){
            clearTimeout(window.searchTimeout);
        }
        window.searchTimeout = setTimeout(startSearch(),300);
    });
    
    var firstNameElement = form.getElement("#first_name");
    firstNameElement.addEvent("keyup",function(){
        if(window.searchTimeout){
            clearTimeout(window.searchTimeout);
        }
        window.searchTimeout = setTimeout(startSearch(),300);
    });
    
    var lastNameElement = form.getElement("#last_name");
    lastNameElement.addEvent("keyup",function(){
        if(window.searchTimeout){
            clearTimeout(window.searchTimeout);
        }
        window.searchTimeout = setTimeout(startSearch(),300);
    });
    
    var levelElement = form.getElement("#level");
    levelElement.addEvent("keyup",function(){
        if(window.searchTimeout){
            clearTimeout(window.searchTimeout);
        }
        window.searchTimeout = setTimeout(startSearch(),300);
    });
    
});
window.searchRequest = null;
function startSearch(){
  var loader = en4.core.loader.clone();
  loader.addClass("sd_loader loader_after");
  var form = $("filter_form");
  var data = form.toQueryString().parseQueryString();
  if(window.searchRequest){
      window.searchRequest.cancel();
  }
  data.format = 'html';
  var url = '<?php echo $this->url() ?>';
  window.searchRequest = new Request.HTML({
      url: url,
      data: data,
      onRequest: function(){
          loader.inject(form.getElement("#last_name"),"after");
      },
      onCancel: function(){
          loader.destroy();
      },
      onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript){
          loader.destroy();
          var div = new Element("div",{
              html: responseHTML
          });
          var users = $$(".admin_table_form");
          var responseUsers = div.getElement(".admin_table_form");
          responseUsers.inject(users[0],"after");
          users.destroy();
          
          var pagination = $$(".admin_results");
          var responsePagination = div.getElement(".admin_results");
          responsePagination.inject(pagination[0],"after");
          pagination.destroy();
          
      }
  });
  window.searchRequest.send();  
}
</script>

<div class='sd_layout_middle sd-badges-badges-list'>
<div class='admin_search'>
    <?php echo $this->formFilter->render($this) ?>
</div>

<br />

<div class="admin_table_form sd-badges-list-table">
<form id='multimodify_form' method="post" action="<?php echo $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Profile Photo") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("User Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("First Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Last Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Options") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if( count($this->paginator) ): ?>
        <?php $api = Engine_Api::_()->sdparentalguide(); ?>
        <?php foreach( $this->paginator as $item ):
          $user = Engine_Api::_()->getItem('user', $item->user_id);
          if(empty($user)) { continue; }
          ?>
          <tr>
            <td class='admin_table_centered admin_table_bold'>
              <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon')); ?> 
            </td>
            <td class='admin_table_centered admin_table_bold'>
              <?php echo $this->htmlLink($user->getHref(),
                  $this->string()->truncate($user->username, 15),
                  array('target' => '_blank'))?>
            </td>
            <td class='admin_table_centered admin_table_user'><?php echo $api->getFieldValue($item,3); ?></td>
            <td class='admin_table_centered admin_table_email'>
              <?php echo $api->getFieldValue($item,4); ?>
            </td>
            <td class='admin_table_centered'>
                <a href='<?php echo $this->url(array('action' => 'assign-user','user_id' => $user->getIdentity()),'sdparentalguide_badger_assignuser',true);?>'>
                    <?php echo $this->translate("View") ?>
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
    <?php echo $this->translate(array("%s member found", "%s members found", $count),
        $this->locale()->toNumber($count)) ?>
  </div>
  <div>
    <?php echo $this->paginationControl($this->paginator, null, null, array(
      'pageAsQuery' => true,
      'query' => $this->formValues,
      //'params' => $this->formValues,
    )); ?>
  </div>
</div>
</div>