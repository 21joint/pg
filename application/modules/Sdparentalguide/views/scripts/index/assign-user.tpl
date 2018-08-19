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

<style type='text/css'>
.sd_user_details,
.user_info>div{
    text-align: center;
}
.sd_user_details>div {
    vertical-align: middle;
    display: inline-block;
}
.sd_user_details .user_info {
    margin-right: 100px;
}
.assigned_badges .user_photo img {
    max-height: 150px;
}
.sd-badges-badges-list form>div {
    max-width: 180px;
    float: left;
    margin-right: 10px;
}
.sd-badges-badges-list form{
    overflow: hidden;
}
.sd-badges-badges-list #level{
    padding: 5px 8px;
}
.sd-badges-list-table table,
.admin_table{
    width: 100%;
    border: 1px solid #ddd;
}
</style>    

<h2><?php echo $this->translate("Assign Badges") ?></h2>
<?php $api = Engine_Api::_()->sdparentalguide(); ?>
<div class='sd_layout_middle'>
<div class="admin_table_form assigned_badges">
<div class='sd_user_details'>
    <?php $user = $this->user; ?>
    <div class='user_info'>
        <div class='user_username'>
            <?php echo $this->htmlLink($user->getHref(),
                  $this->string()->truncate($user->username, 32),
                  array('target' => '_blank'))?>
        </div>
        <div class='user_username'>
            <?php echo $this->htmlLink($user->getHref(),
                  $this->string()->truncate($api->getFieldValue($user,3)." ".$api->getFieldValue($user,4), 32),
                  array('target' => '_blank'))?>
        </div>
    </div>
    <div class='user_photo'>
        <?php echo $this->htmlLink($this->user->getHref(array('target' => '_blank')),$this->itemPhoto($this->user,'thumb.profile')); ?>
    </div>
</div>
<form id='multimodify_form' method="post" action="<?php echo $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Badge Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Image") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Type") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Level") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Topic Name") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if( count($this->assignedBadges) ): ?>
        <?php $api = Engine_Api::_()->sdparentalguide(); ?>
        <?php foreach( $this->assignedBadges as $item ):?>
          <tr>
            <td class='admin_table_centered admin_table_bold'>
              <?php echo $item->getTitle(); ?>
            </td>
            <td class='admin_table_centered admin_table_user'><?php echo $this->itemPhoto($item,'thumb.icon',$item->getTitle()); ?></td>
            <td class="admin_table_centered nowrap">
               <?php if(($badgeType = $item->getBadgeType())): ?>
                    <?php echo $this->translate($badgeType); ?>
                <?php endif; ?>
            </td>
            <td class="admin_table_centered nowrap">
               <?php if(($level = $item->getLevel())): ?>
                    <?php echo $this->translate($level); ?>
                <?php endif; ?>
            </td>            
            <td class='admin_table_centered admin_table_email'>
                <?php if(($topic = $item->getTopic())): ?>
                    <?php echo $topic->getTitle(); ?>
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

<div class='admin_results assigned_badges'>
  <div>
    <?php $count = count($this->assignedBadges) ?>
    <?php echo $this->translate(array("%s badge assigned", "%s badges assigned", $count),
        $this->locale()->toNumber($count)) ?>
  </div>
</div>

<script type='text/javascript'>
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
function searchBadges(){
    if(window.searchRequest){
        window.searchRequest.cancel();
    }
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var form = $("filter_form");
    var formData = form.toQueryString().parseQueryString();
    formData.format = 'html';
    
    window.searchRequest = new Request.HTML({
        url: '<?php echo $this->url(); ?>',
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
          var data = $$(".admin_table_form.all_badges");
          var responseData = div.getElement(".admin_table_form.all_badges");
          responseData.inject(data[0],"after");
          data.destroy();
          
          var pagination = $$(".admin_results.all_badges");
          var responsePagination = div.getElement(".admin_results.all_badges");
          responsePagination.inject(pagination[0],"after");
          pagination.destroy();
        }
    });
    window.searchRequest.send();
}
function assignBadge(element,badgeId,userId){
    var url = en4.core.baseUrl+"gg/assign-quick/user_id/"+userId+"/badge_id/"+badgeId;
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    $(element).setStyle("display","none");
    var req = new Request.JSON({
        url: url,
        data: {
            format: 'json'
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
          var row = $(element).getParent("tr").clone();
          row.getElement(".table_options").destroy();
          
          $(element).getParent("tr").destroy();
          var tableBody = $$(".admin_table_form.assigned_badges tbody");
          row.inject(tableBody[0],"bottom");
          Smoothbox.bind(row);
        }
    });
    req.send();
}
function deleteAssignedBadge(element,badgeId,userId){
    var url = en4.core.baseUrl+"admin/sdparentalguide/badge/delete-assigned/user_id/"+userId+"/badge_id/"+badgeId;
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    $(element).setStyle("display","none");
    var req = new Request.JSON({
        url: url,
        data: {
            format: 'json'
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
          var row = $(element).getParent("tr").clone();
          $(element).getParent("tr").destroy();
          var options = row.getElement(".table_options");
          options.empty();
          var anchor = new Element("a",{
              href: 'javascript:void(0);',
              html: "<?php echo $this->translate('Assign'); ?>",
              onclick: "assignBadge(this,'"+badgeId+"','"+userId+"');"
          });
          anchor.inject(options,"bottom");
          var tableBody = $$(".admin_table_form.all_badges tbody");
          row.inject(tableBody[0],"bottom");
          Smoothbox.bind(row);
        }
    });
    req.send();
}
function updateAssignStatus(element,badgeId,userId,status){
    var url = en4.core.baseUrl+"admin/sdparentalguide/badge/assign-status/user_id/"+userId+"/badge_id/"+badgeId;
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
                html: "<?php echo $this->translate('Revoke'); ?>",
                onclick: "updateAssignStatus(this,'"+badgeId+"','"+userId+"','0');"
          });
          if(status == '0'){
            anchor = new Element("a",{
                href: 'javascript:void(0);',
                html: "<?php echo $this->translate('Reinstate'); ?>",
                onclick: "updateAssignStatus(this,'"+badgeId+"','"+userId+"','1');"
            });
          }
          anchor.inject($(element),"after");
          $(element).destroy();
          Smoothbox.bind(row);
        }
    });
    req.send();
}
</script>
<script type='text/javascript'>
en4.core.runonce.add(function(){
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    $("topic").addEvent((Browser.Engine.trident || Browser.Engine.webkit) ? 'keydown' : 'keypress',function(event){
        if(event.key == "enter"){
            searchBadges();
        }
    });
    $("topic").addEvent('keyup',function(event){
        var topic = $(this).value;
        if(topic.length <= 0){
            searchBadges();
        }
    });
    var autoCompleter = new Autocompleter.Request.JSON('topic', '<?php echo $this->url(array('action' => 'suggest-topic'), 'sdparentalguide_general', true) ?>', {
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
        onCommand: function(e){
            if(!e){
                return;
            }
            if(e.control || e.shift || e.alt || e.meta){
                return;
            }
            if(e.key == 'enter' || e.key == 'tab' || e.key == 'capslock'){
                return;
            }
            $("topic_id").value = '';
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
        searchBadges();
    };
});
</script>
<br />

<?php if($this->user->getIdentity() != $this->viewer_id): ?>

<div class='admin_search sd-badges-badges-list'>
    <?php echo $this->formFilter->render($this) ?>
</div>
<br/>
<div class="admin_table_form all_badges sd-badges-list-table">
<form id='multimodify_form' method="post" action="<?php echo $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Badge Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Image") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Type") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Level") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Topic Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Action") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if( count($this->paginator) ): ?>
        <?php $api = Engine_Api::_()->sdparentalguide(); ?>
        <?php foreach( $this->paginator as $item ):?>
          <tr>
            <td class='admin_table_centered admin_table_bold'>
              <?php echo $item->getTitle(); ?>
            </td>
            <td class='admin_table_centered admin_table_user'><?php echo $this->itemPhoto($item,'thumb.icon',$item->getTitle()); ?></td>            
            <td class="admin_table_centered nowrap">
               <?php if(($badgeType = $item->getBadgeType())): ?>
                    <?php echo $this->translate($badgeType); ?>
                <?php endif; ?>
            </td>
            <td class="admin_table_centered nowrap">
               <?php if(($level = $item->getLevel())): ?>
                    <?php echo $this->translate($level); ?>
                <?php endif; ?>
            </td>
            <td class='admin_table_centered admin_table_email'>
                <?php if(($topic = $item->getTopic())): ?>
                    <?php echo $topic->getTitle(); ?>
                <?php endif; ?>
            </td>
            <td class='admin_table_centered table_options'>
                <a href='javascript:void(0);' onclick="assignBadge(this,'<?php echo $item->getIdentity(); ?>','<?php echo $this->user->getIdentity(); ?>');">
                    <?php echo $this->translate("Assign") ?>
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

<div class='admin_results all_badges' style='position: relative;overflow: visible;'>
  <div>
    <?php $count = $this->paginator->getTotalItemCount() ?>
    <?php echo $this->translate(array("%s badge found", "%s badges found", $count),
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

<?php endif; ?>
</div>
<style type='text/css'>
.sd_close {
    font-size: 20px;
    position: absolute;
    display: block;
    right: 0px;
    bottom: -5px;    
}    
.sd_profile_display {
    display: none !important;
}
</style>