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

<h2><?php echo $this->translate("Parental Guidance Customizations") ?></h2>

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
  try{
      if($("assigned_checkbox").getElement("input[type=radio]:checked") && $("assigned_checkbox").getElement("input[type=radio]:checked").get("value").length > 0){
          data.assigned = $("assigned_checkbox").getElement("input[type=radio]:checked").get("value");
      }
  }catch(e){ console.log(e); }
  try{
      if($("profile_display_checkbox").getElement("input[type=radio]:checked") && $("profile_display_checkbox").getElement("input[type=radio]:checked").get("value").length > 0){
          data.profile_display = $("profile_display_checkbox").getElement("input[type=radio]:checked").get("value");
      }
  }catch(e){ console.log(e); }
  data.format = 'html';
  var url = '<?php echo $this->url() ?>';
  window.searchRequest = new Request.HTML({
      url: url,
      data: data,
      onRequest: function(){
          loader.inject($$(".sd_start_search button")[0],"after");
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
function markAssigned(element){
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
  
  var url = '<?php echo $this->url(array('action' => 'assign-bulk')) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          user_ids: ids,
          badge_id: '<?php echo $this->badge->getIdentity(); ?>',
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          inputs.each(function(input){
              var tr = input.getParent("tr");
              var radio = tr.getElement("input.sd_radio_assigned");
              radio.set("checked","checked");
              
              var checkbox = tr.getElement("input.sd_radio_revoked");
              checkbox.set("checked",null);
              
              var checkbox = tr.getElement("input.sd_select_checkbox");
              checkbox.set("checked",null);
              $("select-all").set("checked",null);
          });
      }
  });
  req.send();  
}
function unmarkAssigned(element){
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
  
  var url = '<?php echo $this->url(array('action' => 'delete-bulk')) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          user_ids: ids,
          badge_id: '<?php echo $this->badge->getIdentity(); ?>',
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          inputs.each(function(input){
              var tr = input.getParent("tr");
              var radio = tr.getElement("input.sd_radio_assigned");
              radio.set("checked",null);
              
              var checkbox = tr.getElement("input.sd_radio_revoked");
              checkbox.set("checked",null);
              
              var checkbox = tr.getElement("input.sd_select_checkbox");
              checkbox.set("checked",null);
              $("select-all").set("checked",null);
          });
      }
  });
  req.send();  
}
function activateAssigned(element){
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
  
  var url = '<?php echo $this->url(array('action' => 'status-bulk')) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          user_ids: ids,
          badge_id: '<?php echo $this->badge->getIdentity(); ?>',
          status: 1,
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          inputs.each(function(input){
              var tr = input.getParent("tr");
              var radio = tr.getElement("input.sd_radio_assigned");              
              var checkbox = tr.getElement("input.sd_radio_revoked");
              if(checkbox.checked){
                  radio.set("checked","checked");
              }
              checkbox.set("checked",null);
              
              var checkbox = tr.getElement("input.sd_select_checkbox");
              checkbox.set("checked",null);
              $("select-all").set("checked",null);
          });
      }
  });
  req.send();  
}
function revokeAssigned(element){
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
  
  var url = '<?php echo $this->url(array('action' => 'status-bulk')) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          user_ids: ids,
          badge_id: '<?php echo $this->badge->getIdentity(); ?>',
          status: 0,
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          inputs.each(function(input){
              var tr = input.getParent("tr");
              var radio = tr.getElement("input.sd_radio_assigned");                            
              var checkbox = tr.getElement("input.sd_radio_revoked");
              if(radio.checked){
                  checkbox.set("checked","checked");
              }              
              
              var checkbox = tr.getElement("input.sd_select_checkbox");
              checkbox.set("checked",null);
              $("select-all").set("checked",null);
          });
      }
  });
  req.send();  
}
function switchProfileDisplay(element,display){
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
  
  var url = '<?php echo $this->url(array('action' => 'display-bulk')) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          user_ids: ids,
          badge_id: '<?php echo $this->badge->getIdentity(); ?>',
          display: display,
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          inputs.each(function(input){
              var tr = input.getParent("tr");
              var radio = tr.getElement("input.sd_radio_display");                            
              radio.set("checked",display);
              var radio = tr.getElement("input.sd_radio_assigned");
              if(!radio.checked){
                  radio.checked = display;
              }
              var checkbox = tr.getElement("input.sd_select_checkbox");
              checkbox.set("checked",null);
              $("select-all").set("checked",null);
          });
      }
  });
  req.send();  
}
</script>

<?php $badge = $this->badge; ?>
<div class='sd_layout_middle'>
<div class='admin_search'>
    <div class="sd_badge_actions">
        <div class="sd_badge_details">
            <h3><?php echo $badge->getTitle(); ?></h3>
            <div class="sd_assign_selected">
                <a href='javascript:void(0);'><button onclick="markAssigned(this);"><?php echo $this->translate("Assign Badge"); ?></button></a>
            </div>
            <div class="sd_assign_selected">
                <a href='javascript:void(0);'><button onclick="unmarkAssigned(this);"><?php echo $this->translate("Un-Assign Badge"); ?></button></a>
            </div>
            
            <div class="sd_profile_display_selected">
                <a href='javascript:void(0);'><button onclick="switchProfileDisplay(this,1);"><?php echo $this->translate("Display of Profile"); ?></button></a>
            </div>
            <div class="sd_profile_display_selected">
                <a href='javascript:void(0);'><button onclick="switchProfileDisplay(this,0);"><?php echo $this->translate("Remove from Profile"); ?></button></a>
            </div>
        </div>
        <div class="sd_badge_photo">
            <?php echo $this->itemPhoto($badge,'thumb.profile',$badge->getTitle()); ?>
        </div>
    </div>
    <div class="sd_badge_actions_wrap">
        <div class="sd_badge_actions">
            <div class="sd_activate_selected">
                <a href='javascript:void(0);'><button onclick="activateAssigned(this);"><?php echo $this->translate("Activate"); ?></button></a>
            </div>
            <div class="sd_revoke_selected">
                <a href='javascript:void(0);'><button onclick="revokeAssigned(this);"><?php echo $this->translate("Inactive"); ?></button></a>
            </div>
            <div class="sd_start_search">
                <a href='javascript:void(0);'><button onclick="startSearch();"><?php echo $this->translate("Search"); ?></button></a>
            </div>
        </div>
        <div class='sd_assign_filter' id="assigned_checkbox">
            <input type="radio" name="active" id="active-" value="" onchange="startSearch();"><label for="active-"><?php echo $this->translate("All"); ?></label><br>
            <input type="radio" name="active" id="active-1" value="1" onchange="startSearch();"><label for="active-1"><?php echo $this->translate("Active"); ?></label><br>
            <input type="radio" name="active" id="active-0" value="0" onchange="startSearch();"><label for="active-0"><?php echo $this->translate("Inactive"); ?></label>
        </div>
        <div class='sd_assign_filter' id="profile_display_checkbox">
            <input type="radio" name="profile_display" id="profile_display-" value="" onchange="startSearch();"><label for="profile_display-"><?php echo $this->translate("All"); ?></label><br>
            <input type="radio" name="profile_display" id="profile_display-1" value="1" onchange="startSearch();"><label for="profile_display-1"><?php echo $this->translate("Displayed on Profile"); ?></label><br>
            <input type="radio" name="profile_display" id="profile_display-0" value="0" onchange="startSearch();"><label for="profile_display-0"><?php echo $this->translate("Not Displayed on Profile"); ?></label>
        </div>
    </div>
    <?php echo $this->formFilter->render($this) ?>
</div>

<br />

<div class="admin_table_form">
<form id='multimodify_form' method="post" action="<?php echo $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 100px;' class='admin_table_centered'>
            <input onclick="selectAll()" type='checkbox' class='checkbox' id="select-all" style="display:none;">
            <label for="select-all" class='admin_table_centered'><?php echo $this->translate("Select"); ?></label>
        </th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("User Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("First Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Last Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Level") ?></th>        
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Assigned") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Inactive") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Displayed") ?></th>
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
            <td><input class="sd_select_checkbox" data-id='<?php echo $item->user_id; ?>' value=<?php echo $item->getIdentity();?> type='checkbox' class='checkbox'></td>
            <td class='admin_table_centered admin_table_bold'>
              <?php echo $this->htmlLink($user->getHref(),
                  $this->string()->truncate($user->username, 10),
                  array('target' => '_blank'))?>
            </td>
            <td class='admin_table_centered admin_table_user'><?php echo $api->getFieldValue($item,3); ?></td>
            <td class='admin_table_centered admin_table_email'>
              <?php echo $api->getFieldValue($item,4); ?>
            </td>
            <td class="admin_table_centered nowrap">
              <?php echo Engine_Api::_()->getItem("authorization_level",$user->level_id)->getTitle(); ?>
            </td>
            <td class='admin_table_centered'>
                <input type="radio" class="sd_radio_assigned" disabled <?php echo ( $item->badge_id ? $this->translate('checked=checked') : '' ); ?>>
            </td>
            <td class='admin_table_centered'>
                <input type="radio" class="sd_radio_revoked" disabled <?php echo ( $item->active === 0 ? $this->translate('checked=checked') : '' ); ?>>
            </td>
            <td class="admin_table_centered nowrap">
                <input type="radio" class="sd_radio_display" disabled <?php echo ( $item->profile_display ? $this->translate('checked=checked') : '' ); ?>>  
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
<style type='text/css'>
div .sd_badge_actions_wrap .sd_badge_actions {
    margin-right: 25px;    
}    
div .sd_badge_actions_wrap .sd_assign_filter {
    min-width: 80px;
}
</style>