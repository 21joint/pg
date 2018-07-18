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
  var url = '<?= $this->url() ?>';
  window.searchRequest = new Request.HTML({
      url: url,
      data: data,
      onRequest: function(){
          loader.inject(form.getElement("#level_id"),"after");
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

<div class='sd_layout_middle'>
<div class='admin_search'>
    <?= $this->formFilter->render($this) ?>
</div>

<br />

<div class="admin_table_form">
<form id='multimodify_form' method="post" action="<?= $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("User Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("First Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("Last Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("Level") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("Options") ?></th>
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
              <?= $this->htmlLink($user->getHref(),
                  $this->string()->truncate($user->username, 10),
                  array('target' => '_blank'))?>
            </td>
            <td class='admin_table_centered admin_table_user'><?= $api->getFieldValue($item,3); ?></td>
            <td class='admin_table_centered admin_table_email'>
              <?= $api->getFieldValue($item,4); ?>
            </td>
            <td class="admin_table_centered nowrap">
              <?= Engine_Api::_()->getItem("authorization_level",$user->level_id)->getTitle(); ?>
            </td>
            <td class='admin_table_centered'>
                <a href='<?= $this->url(array('controller' => 'badge', 'action' => 'assign-user','user_id' => $user->getIdentity()));?>'>
                    <?= $this->translate("View") ?>
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
    <?= $this->translate(array("%s member found", "%s members found", $count),
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