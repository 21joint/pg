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

<script type="text/javascript">
  var currentOrder = '<?= $this->order ?>';
  var currentOrderDirection = '<?= $this->order_direction ?>';
  var changeOrder = function(order, default_direction){
    // Just change direction
    if( order == currentOrder ) {
      $('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
    } else {
      $('order').value = order;
      $('order_direction').value = default_direction;
    }
    $('filter_form').submit();
  }

function multiModify()
{
  var multimodify_form = $('multimodify_form');
  if (multimodify_form.submit_button.value == 'delete')
  {
    return confirm('<?= $this->string()->escapeJavascript($this->translate("Are you sure you want to delete the selected user accounts?")) ?>');
  }
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

</script>

<script type='text/javascript'>
en4.core.runonce.add(function(){
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    
    var autoCompleter = new Autocompleter.Request.JSON('username', '<?= $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'suggest'), 'admin_default', true) ?>', {
        'minLength': 3,
        'delay' : 250,
        'selectMode': 'pick',
        'autocompleteType': 'message',
        'multiple': false,
        'className': 'message-autosuggest sd_user_suggest',
        'filterSubset' : true,
        'tokenFormat' : 'object',
        'tokenValueKey' : 'label',
        'injectChoice': function(token){
            if(loader){
                loader.destroy();
            }
          if(token.type == 'user'){
            var choice = new Element('li', {
              'class': 'autocompleter-choices',
              'html': token.photo,
              'id':token.label
            });
            new Element('div', {
              'html': this.markQueryValue(token.label),
              'class': 'autocompleter-choice'
            }).inject(choice);
            this.addChoiceEvents(choice).inject(this.choices);
            choice.store('autocompleteChoice', token);
          }            
        },
        onPush : function(){
          
        },
        onChoiceSelect: function(choice){
            //onChoiceSelect;
        },
        onComplete: function(){
            loader.destroy();
        },
        onCancel: function(){
            loader.destroy();
        },
        onRequest: function(){
            loader.inject($("username"),"after");
            $("level_id").value = "";
        }
    });
    autoCompleter.doPushSpan = function(name, toID, newItem, hideLoc, list){
        //doPushSpan;
    };
    autoCompleter.doAddValueToHidden = function(name, toID, hideLoc, newItem, list){
        $("username").value = name;
        $("username").blur();
        startSearch();
    };

    var autoCompleter2 = new Autocompleter.Request.JSON('level', '<?= $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'suggest-level'), 'admin_default', true) ?>', {
        'minLength': 3,
        'delay' : 250,
        'selectMode': 'pick',
        'autocompleteType': 'message',
        'multiple': false,
        'className': 'message-autosuggest sd_user_suggest',
        'filterSubset' : true,
        'tokenFormat' : 'object',
        'tokenValueKey' : 'label',
        'injectChoice': function(token){
            if(loader){
                loader.destroy();
            }
          if(token.type == 'user'){
            var choice = new Element('li', {
              'class': 'autocompleter-choices',
              'html': token.photo,
              'id':token.label
            });
            new Element('div', {
              'html': this.markQueryValue(token.label),
              'class': 'autocompleter-choice'
            }).inject(choice);
            this.addChoiceEvents(choice).inject(this.choices);
            choice.store('autocompleteChoice', token);
          }            
        },
        onPush : function(){
          
        },
        onChoiceSelect: function(choice){
            //onChoiceSelect;
        },
        onComplete: function(){
            loader.destroy();
        },
        onCancel: function(){
            loader.destroy();
        },
        onRequest: function(){
            loader.inject($("level"),"after");
        }
    });
    autoCompleter2.doPushSpan = function(name, toID, newItem, hideLoc, list){
        //doPushSpan;
    };
    autoCompleter2.doAddValueToHidden = function(name, toID, hideLoc, newItem, list){
        $("level_id").value = toID;
        $("level").value = name;
        $("level").blur();
        startSearch();
    };
});
function markFeatured(element){
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
  
  var url = '<?= $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'mark-featured'), 'admin_default', true) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          featured: 1,
          user_ids: ids,
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          inputs.each(function(input){
              var tr = input.getParent("tr");
              var radio = tr.getElement("input[type=radio]");
              radio.set("checked","checked");
              
              var checkbox = tr.getElement("input[type=checkbox]");
              checkbox.set("checked",null);
          });
          $(element).set("checked",null);
      }
  });
  req.send();
}
function markUnfeatured(element){
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
  
  var url = '<?= $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'mark-featured'), 'admin_default', true) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          featured: 0,
          user_ids: ids,
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          inputs.each(function(input){
              var tr = input.getParent("tr");
              var radio = tr.getElement("input[type=radio]");
              radio.set("checked",null);
              
              var checkbox = tr.getElement("input[type=checkbox]");
              checkbox.set("checked",null);
          });
          $(element).set("checked",null);
      }
  });
  req.send();  
}
window.searchRequest = null;
function startSearch(){
  var loader = en4.core.loader.clone();
  loader.addClass("sd_loader loader_after");
  var form = $("filter_form");
  var data = form.toQueryString().parseQueryString();
  try{
      if($("featured_checkbox").getElement("input[type=checkbox]:checked")){
          data.featured = 1;
      }
      if($("mvp_checkbox").getElement("input[type=checkbox]:checked")){
          data.mvp = 1;
      }
      if($("expert_checkbox").getElement("input[type=checkbox]:checked")){
          data.expert = 1;
      }
  }catch(e){ console.log(e); }
  if(window.searchRequest){
      window.searchRequest.cancel();
  }
  data.format = 'html';
  var url = '<?= $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'featured-users'), 'admin_default', true) ?>';
  window.searchRequest = new Request.HTML({
      url: url,
      data: data,
      onRequest: function(){
          loader.inject(form.getElement(".featured_checkbox"),"after");
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
function loginAsUser(id) {
  if( !confirm('<?= $this->translate('Note that you will be logged out of your current account if you click ok.') ?>') ) {
    return;
  }
  var url = '<?= $this->url(array('module' => 'user','controller' => 'manage','action' => 'login'),'admin_default',true) ?>';
  var baseUrl = '<?= $this->url(array(), 'default', true) ?>';
  (new Request.JSON({
    url : url,
    data : {
      format : 'json',
      id : id
    },
    onSuccess : function() {
      window.location.replace( baseUrl );
    }
  })).send();
}

function markMvp(element){
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
  
  var url = '<?= $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'mark-mvp'), 'admin_default', true) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          mvp: 1,
          user_ids: ids,
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          inputs.each(function(input){
              var tr = input.getParent("tr");
              var radio = tr.getElement("input[type=radio].radio_mvp");
              radio.set("checked","checked");
              
              var checkbox = tr.getElement("input[type=checkbox]");
              checkbox.set("checked",null);
          });
          $(element).set("checked",null);
      }
  });
  req.send();
}
function removeMvp(element){
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
  
  var url = '<?= $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'mark-mvp'), 'admin_default', true) ?>';
  var req = new Request.JSON({
      url: url,
      data: {
          mvp: 0,
          user_ids: ids,
          format: 'json'
      },
      onRequest: function(){
          loader.inject($(element),"after");
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          inputs.each(function(input){
              var tr = input.getParent("tr");
              var radio = tr.getElement("input[type=radio].radio_mvp");
              radio.set("checked",null);
              
              var checkbox = tr.getElement("input[type=checkbox]");
              checkbox.set("checked",null);
          });
          $(element).set("checked",null);
      }
  });
  req.send();  
}
</script>

<div class='admin_search'>
    <div class="sd_top_search" style="">
        <form>
            <div class="sd_feature_selected">
                <input type="radio" id="sd_feature_selected" name='featured' style="display: none;"/>
                <label for="sd_feature_selected" class="button" onclick='markFeatured(this);'><?= $this->translate("Feature Selected"); ?></label>
            </div>
            <div class="sd_unfeature_selected">
                <input type="radio" id="sd_unfeature_selected" name='featured' style="display: none;"/>
                <label for="sd_unfeature_selected" class="button" onclick="markUnfeatured(this);"><?= $this->translate("Un-Feature Selected"); ?></label>
            </div>
            <div class="sd_make_mvp_selected">
                <input type="radio" id="sd_make_mvp_selected" name='mvp' style="display: none;"/>
                <label for="sd_make_mvp_selected" class="button" onclick="markMvp(this);"><?= $this->translate("Make MVP"); ?></label>
            </div>
            <div class="sd_remove_mvp_selected">
                <input type="radio" id="sd_remove_mvp_selected" name='mvp' style="display: none;"/>
                <label for="sd_remove_mvp_selected" class="button" onclick="removeMvp(this);"><?= $this->translate("Remove MVP"); ?></label>
            </div>
            <div class='sd_search_button'>
                <button class="button" type='button' onclick="startSearch(this);"><?= $this->translate("Search"); ?></button>
            </div>
        </form>
    </div>
    <?= $this->formFilter->render($this) ?>
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

<br />

<div class="admin_table_form">
<form id='multimodify_form' method="post" action="<?= $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 100px;' class='admin_table_centered'>
            <input onclick="selectAll()" type='checkbox' class='checkbox' id="select-all" style="display:none;">
            <label for="select-all" class='admin_table_centered'><?= $this->translate("Select"); ?></label>
        </th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("User Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("First Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("Last Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("Level") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("Featured") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?= $this->translate("MVP") ?></th>
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
            <td><input data-id='<?= $item->user_id; ?>' value=<?= $item->getIdentity();?> type='checkbox' class='checkbox'></td>
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
                <input type="radio" disabled <?= ( $item->gg_featured ? $this->translate('checked=checked') : '' ); ?>>              
            </td>
            <td class='admin_table_centered'>
                <input type="radio" disabled class='radio_mvp' <?= ( $item->gg_mvp ? $this->translate('checked=checked') : '' ); ?>>              
            </td>
            <td>
                <a class='smoothbox' href='<?= $this->url(array('module' => 'user','controller' => 'manage','action' => 'edit', 'id' => $item->user_id),'admin_default',true);?>'>
                <?= $this->translate("edit") ?>
              </a>
              <?php if ( $item->level_id != 1 ): ?>
                <a href='<?= $this->url(array('module' => 'user','controller' => 'manage','action' => 'login', 'id' => $item->user_id),'admin_default',true);?>' onclick="loginAsUser(<?= $item->user_id ?>); return false;">
                  <?= $this->translate("login") ?>
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
  <br />
</form>
</div>
<style type="text/css">
.button {
    padding: 8px 10px;
    display: inline-block;
    background: #ddd;
    width: 180px;
    text-align: center;
    margin: 5px;
    cursor: pointer;
    text-shadow: none;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    border: 0px;
    color: #666;
    font-weight: normal;
    max-width: none;
    box-sizing: border-box; 
}        
.button:hover,
.button:checked,
.sd_top_search input:checked + label{
    background-color: #7eb6d5;
    cursor: pointer;
    color: #fff;    
}
.button:focus {
    outline: 0px;
}
.sd_loader {
    position: absolute;
    margin-left: -20px;
    margin-top: 10px;    
}
img.sd_loader.loader_after {
    margin-left: 5px;
    margin-top: 20px;
}
.sd_top_search form > div {
    display: inline-block;
}
div .search .featured_checkbox ,
div .search .expert_checkbox ,
div .search .mvp_checkbox {
    margin-top: 20px;
}
div .search .featured_checkbox label,
div .search .expert_checkbox label,
div .search .mvp_checkbox label{
    display: inline-block;
    margin-left: -5px !important;
}
</style>