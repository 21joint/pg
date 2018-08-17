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

<script type="text/javascript">
  var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
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
    return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete the selected user accounts?")) ?>');
  }
}

function selectAll()
{
  var i;
  var multimodify_form = $('multimodify_form');
  var inputs = multimodify_form.getElements("input[type=checkbox]");
  inputs.each(function(input){
      if (!input.disabled) {
        input.checked = !input.checked;
      }
  });
}
function loadCategories(element){
  var loader = en4.core.loader.clone();
  loader.addClass("sd_loader");
  
  var url = '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'guides','action' => 'get-categories'), 'admin_default', true) ?>';
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
          if(responseJSON.status){
              var options = "<option value='0'><option/>";
              Object.each(responseJSON.categories,function(category){
                  console.log(category);
                  options += "<option value='"+category.id+"'>"+category.title+"</option>";
                  console.log(options);
              });
              $("category_id").set("html",options);
          }
      }
  });
  req.send();
}
</script>

<script type='text/javascript'>
en4.core.runonce.add(function(){
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    
    var autoCompleter = new Autocompleter.Request.JSON('username', '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'suggest'), 'admin_default', true) ?>', {
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

    var autoCompleter2 = new Autocompleter.Request.JSON('level', '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'suggest-level'), 'admin_default', true) ?>', {
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
function approveSelected(element){
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
  var form = $("filter_form");
  var data = form.toQueryString().parseQueryString();
  data.format = 'html';
  data.guide_ids = ids;
  
  var url = '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'guides','action' => 'approve-guide'), 'admin_default', true) ?>';
  var req = new Request.HTML({
      url: url,
      data: data,
      onRequest: function(){
          loader.inject($("sd_delete_selected"),"after");
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
  req.send();
}
function denySelected(element){
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
  var form = $("filter_form");
  var data = form.toQueryString().parseQueryString();
  data.format = 'html';
  data.guide_ids = ids;
  
  var url = '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'guides','action' => 'deny-guide'), 'admin_default', true) ?>';
  var req = new Request.HTML({
      url: url,
      data: data,
      onRequest: function(){
          loader.inject($("sd_delete_selected"),"after");
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
  req.send();
}
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
  var url = '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'guides','action' => 'index'), 'admin_default', true) ?>';
  window.searchRequest = new Request.HTML({
      url: url,
      data: data,
      onRequest: function(){
          loader.inject($("sd_delete_selected"),"after");
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
function deleteSelected(element){
  var confirm = window.confirm("<?php echo $this->translate('Are you sure you want to delete selected listings?'); ?>");
  if(!confirm){
      return;
  }
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
  var form = $("filter_form");
  var data = form.toQueryString().parseQueryString();
  data.format = 'html';
  data.guide_ids = ids;
  
  var url = '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'guides','action' => 'delete-guide'), 'admin_default', true) ?>';
  var req = new Request.HTML({
      url: url,
      data: data,
      onRequest: function(){
          loader.inject($("sd_delete_selected"),"after");
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
  req.send();  
}

function featureListing(element,status){
  event.preventDefault();
  var id = $(element).getParent("tr");
  var req = new Request.JSON({
      url: $(element).getParent().href,
      data: {
          format: 'json',
          param_key: 'featured',
          status: status,
          guide_id: id.get("data-id")
      },
      onComplete: function(responseJSON){
          var newHref = '<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => ""), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/unfeatured.gif', '', array('title' => $this->translate('Make Featured'),'onclick' => 'featureListing(this,1);'))) ?>';
          if(status == 1){
              newHref = '<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => ""), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/featured.gif', '', array('title' => $this->translate('Make Un-featured'),'onclick' => 'featureListing(this,0);'))) ?>';
          }
          $(element).getParent("td").set("html",newHref);
      }
  });
  req.send();
}
function sponsoredListing(element,status){
  event.preventDefault();
  var id = $(element).getParent("tr");
  var req = new Request.JSON({
      url: $(element).getParent().href,
      data: {
          format: 'json',
          param_key: 'sponsored',
          status: status,
          guide_id: id.get("data-id")
      },
      onComplete: function(responseJSON){
          var newHref = '<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => ""), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/unsponsored.png', '', array('title' => $this->translate('Make Sponsored'),'onclick' => 'sponsoredListing(this,1);'))) ?>';
          if(status == 1){
              newHref = '<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => ""), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/sponsored.png', '', array('title' => $this->translate('Make Unsponsored'),'onclick' => 'sponsoredListing(this,0);'))) ?>';
          }
          $(element).getParent("td").set("html",newHref);
      }
  });
  req.send();
}
function markAsNew(element,status){
  event.preventDefault();
  var id = $(element).getParent("tr");
  var req = new Request.JSON({
      url: $(element).getParent().href,
      data: {
          format: 'json',
          param_key: 'newlabel',
          status: status,
          guide_id: id.get("data-id")
      },
      onComplete: function(responseJSON){
          var newHref = '<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => ""), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitereview/externals/images/icons/new-disable.png', '', array('title' => $this->translate('Set New Label'),'onclick' => 'markAsNew(this,1);'))) ?>';
          if(status == 1){
              newHref = '<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => ""), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitereview/externals/images/icons/new.png', '', array('title' => $this->translate('Remove New Label'),'onclick' => 'markAsNew(this,0);'))) ?>';
          }
          $(element).getParent("td").set("html",newHref);
      }
  });
  req.send();
}
function approveListing(element,status){
  event.preventDefault();
  var id = $(element).getParent("tr");
  var req = new Request.JSON({
      url: $(element).getParent().href,
      data: {
          format: 'json',
          param_key: 'approved',
          status: status,
          guide_id: id.get("data-id")
      },
      onComplete: function(responseJSON){
          var newHref = '<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => ""), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/disapproved.gif', '', array('title' => $this->translate('Make Approved'),'onclick' => 'approveListing(this,1);'))) ?>';
          if(status == 1){
              newHref = '<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => ""), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/approved.gif', '', array('title' => $this->translate('Make Dis-Approved'),'onclick' => 'approveListing(this,0);'))) ?>';
          }
          $(element).getParent("td").set("html",newHref);
      }
  });
  req.send();
}
function closeListing(element,status){
  event.preventDefault();
  var id = $(element).getParent("tr");
  var req = new Request.JSON({
      url: $(element).getParent().href,
      data: {
          format: 'json',
          param_key: 'closed',
          status: status,
          guide_id: id.get("data-id")
      },
      onComplete: function(responseJSON){
          var newHref = '<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => ""), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitereview/externals/images/unclose.png', '', array('title' => $this->translate('Make Closed'),'onclick' => 'closeListing(this,0);'))) ?>';
          if(status == 1){
              newHref = '<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => ""), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitereview/externals/images/close.png', '', array('title' => $this->translate('Make Open'),'onclick' => 'closeListing(this,1);'))) ?>';
          }
          $(element).getParent("td").set("html",newHref);
      }
  });
  req.send();
}
</script>
<script type="text/javascript">
en4.core.runonce.add(function(){
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    $("topic").addEvent('keyup',function(event){
        var topic = $(this).value;
        if(topic.length <= 0){
            $("topic_id").value = "";
        }
    });
    var autoCompleter = new Autocompleter.Request.JSON('topic', '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'topics','action' => 'suggest'), 'admin_default', true) ?>', {
        'minLength': 3,
        'delay' : 250,
        'selectMode': 'pick',
        'autocompleteType': 'message',
        'multiple': false,
        'selectFirst': false,
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
            loader.inject($("topic"),"after");
        }
    });
    autoCompleter.doPushSpan = function(name, toID, newItem, hideLoc, list){
        //doPushSpan;
    };
    autoCompleter.doAddValueToHidden = function(name, toID, hideLoc, newItem, list){
        $("topic_id").value = toID;
        $("topic").value = name;
        $("topic").blur();
    };
});
</script>

<div class='admin_search'>
    <div class="sd_top_search" style="">
        <form>
            <div class='sd_search_button'>
                <button class="button" type='button' onclick="startSearch(this);"><?php echo $this->translate("Search"); ?></button>
            </div>
            <div class="sd_approve_selected">
                <label id="sd_approve_selected" class="button" onclick='approveSelected(this);'><?php echo $this->translate("Approve All Selected"); ?></label>
            </div>
            <div class="sd_deny_selected">
                <label id="sd_deny_selected" class="button" onclick="denySelected(this);"><?php echo $this->translate("Deny All Selected"); ?></label>
            </div>   
            <div class="sd_delete_selected">
                <label id="sd_delete_selected" class="button" onclick="deleteSelected(this);"><?php echo $this->translate("Delete Selected"); ?></label>
            </div> 
        </form>
    </div>
    <?php echo $this->formFilter->render($this) ?>
</div>

<br />

<div class='admin_results'>
  <div>
    <?php $count = $this->paginator->getTotalItemCount() ?>
    <?php echo $this->translate(array("%s guide found", "%s guides found", $count),
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
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("User ID") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Display Name") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Username") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Email") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("User Level") ?></th>        
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("User Approved") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Guide ID") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Guide Title") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Guide Topic") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Views") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Comments") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Likes") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Featured") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Sponsored") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("New") ?></th>        
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Approved") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Closed") ?></th>
        <th style='width: 5%;' class='admin_table_centered'><?php echo $this->translate("Guide Info") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if( count($this->paginator) ): ?>
        <?php $api = Engine_Api::_()->sdparentalguide(); ?>
        <?php foreach( $this->paginator as $item ):
          $user = Engine_Api::_()->getItem('user', $item->owner_id);
          if(empty($user)) { continue; }
          ?>
          <tr data-id='<?php echo $item->getIdentity(); ?>'>
            <td><input data-id='<?php echo $item->getIdentity(); ?>' value=<?php echo $item->getIdentity();?> type='checkbox' class='checkbox'></td>
            <td><?php echo $this->htmlLink($user->getHref(),
                  $this->string()->truncate($user->getIdentity(), 10),
                  array('target' => '_blank'))?></td>
            <td class='admin_table_centered admin_table_bold'>
              <?php echo $this->htmlLink($user->getHref(),
                  $this->string()->truncate($user->getTitle(), 10),
                  array('target' => '_blank'))?>
            </td>
            <td class='admin_table_centered admin_table_bold'>
              <?php echo $this->htmlLink($user->getHref(),
                  $this->string()->truncate($user->username, 10),
                  array('target' => '_blank'))?>
            </td>
            <td class='admin_table_centered admin_table_bold'>
                <a href="mailto:<?php echo $user->email; ?>"><?php echo $user->email; ?></a>
            </td>
            <td class="admin_table_centered nowrap">
              <?php echo $this->string()->truncate(Engine_Api::_()->getItem("authorization_level",$user->level_id)->getTitle(),10); ?>
            </td>
            <td class='admin_table_centered admin_table_user'><?php echo ( $user->approved ? $this->translate('Yes') : 'No' ); ?></td>
            <td class='admin_table_centered admin_table_bold'>
              <?php echo $this->htmlLink($item->getHref(),
                  $this->string()->truncate($item->getIdentity(), 10),
                  array('target' => '_blank'))?>
            </td>
            <td class='admin_table_centered admin_table_bold'>
              <?php echo $this->htmlLink($item->getHref(),
                  $this->string()->truncate($item->getTitle(), 15),
                  array('target' => '_blank'))?>
            </td>
            <td class='admin_table_centered admin_table_bold'>
              <?php if($topic = $item->getTopic()): ?>
                <?php echo $this->htmlLink($topic->getHref(),
                    $this->string()->truncate($topic->getTitle(), 15),
                    array('target' => '_blank'))?>
              <?php endif; ?>
            </td>
            <td class='admin_table_centered'>
                <?php echo $item->view_count; ?>
            </td>
            <td class='admin_table_centered'>
                <?php echo $item->comment_count; ?>
            </td>
            <td class='admin_table_centered'>
                <?php echo $item->like_count; ?>
            </td>
            <?php if($item->featured == 1):?> 
                <td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => $item->guide_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/featured.gif', '', array('title' => $this->translate('Make Un-featured'),'onclick' => 'featureListing(this,0);'))) ?></td>
            <?php else: ?>
		<td align="center" class="admin_table_centered"><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => $item->guide_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/unfeatured.gif', '', array('title' => $this->translate('Make Featured'),'onclick' => 'featureListing(this,1);'))) ?></td>
            <?php endif; ?>
            <?php if($item->sponsored == 1):?>
                <td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => $item->guide_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/sponsored.png', '', array('title' => $this->translate('Make Unsponsored'),'onclick' => 'sponsoredListing(this,0);'))); ?></td>
            <?php else: ?>
                <td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => $item->guide_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/unsponsored.png', '', array('title' => $this->translate('Make Sponsored'),'onclick' => 'sponsoredListing(this,1);'))); ?>
            <?php endif; ?>   
            <?php if($item->newlabel == 1):?> 
		<td align="center" class="admin_table_centered"><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => $item->guide_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitereview/externals/images/icons/new.png', '', array('title' => $this->translate('Remove New Label'),'onclick' => 'markAsNew(this,0);'))) ?></td>
            <?php else: ?>
		<td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => $item->guide_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitereview/externals/images/icons/new-disable.png', '', array('title' => $this->translate('Set New Label'),'onclick' => 'markAsNew(this,1);'))) ?></td>
            <?php endif; ?>
            <?php if($item->approved == 1):?>
                <td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => $item->guide_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/approved.gif', '', array('title' => $this->translate('Make Dis-Approved'),'onclick' => 'approveListing(this,0);'))) ?></td>
            <?php else: ?>
                <td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => $item->guide_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/images/disapproved.gif', '', array('title' => $this->translate('Make Approved'),'onclick' => 'approveListing(this,1);'))) ?></td>
            <?php endif; ?>

            <?php if($item->closed == 0):?>
                <td align="center" class="admin_table_centered">  <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => $item->guide_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitereview/externals/images/unclose.png', '', array('title'=> $this->translate('Make Closed'),'onclick' => 'closeListing(this,1);'))) ?>
            <?php else: ?>
                <td align="center" class="admin_table_centered"> <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'update-guide', 'guide_id' => $item->guide_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sitereview/externals/images/close.png', '', array('title'=> $this->translate('Make Open'),'onclick' => 'closeListing(this,0);'))) ?>
            <?php endif; ?>
            <td>
                <?php echo $this->htmlLink($item->getHref(),$this->translate("view"),array('target' => '_blank'))?>
                | <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'change-owner', 'guide_id' => $item->guide_id), $this->translate('change owner'), array('class' => 'smoothbox')) ?>
                | <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'change-topic', 'guide_id' => $item->guide_id), $this->translate('change topic'), array('class' => 'smoothbox')) ?>
                | <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'guides', 'action' => 'delete', 'guide_id' => $item->guide_id), $this->translate('delete'), array('class' => 'smoothbox')) ?>
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
    margin-top: 10px;
}
.sd_top_search form > div {
    display: inline-block;
}
div .search .featured_checkbox {
    margin-top: 20px;
}
div .search .featured_checkbox label {
    display: inline-block;
    margin-left: -5px !important;
}
input[type=email] {
    border: 1px solid #999;
    font-family: tahoma,verdana,arial,sans-serif;
    padding: .5em;
    color: #444;
    font-size: 10pt;    
}
div.search div select {
    max-width: 175px;
    width: 175px;
    padding: 0.39em;
    font-size: 10pt;
    margin-bottom: 8px;
}
div.search div input {
    margin-bottom: 8px;
}
div .admin_table tr td a {
    border-left: 0px;
}
div .admin_search .search div label {
    display: block;
}
</style>