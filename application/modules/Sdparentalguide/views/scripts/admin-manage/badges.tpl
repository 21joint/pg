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

<div class='sd_layout_middle'>
<div class='admin_search'>
    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sdparentalguide', 'controller' => 'badge', 'action' => 'create'), $this->translate('Add Badge'), array(
      'class' => 'smoothbox buttonlink',
      'style' => 'background-image: url(' . $this->layout()->staticBaseUrl . 'application/modules/Core/externals/images/admin/new_category.png);')) ?>
    <?php echo $this->formFilter->render($this) ?>
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
          var data = $$(".admin_table_form");
          var responseData = div.getElement(".admin_table_form");
          responseData.inject(data[0],"after");
          data.destroy();
          
          var pagination = $$(".admin_results");
          var responsePagination = div.getElement(".admin_results");
          responsePagination.inject(pagination[0],"after");
          pagination.destroy();
        }
    });
    window.searchRequest.send();
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
            $("topic_id").value = "";
            searchBadges();
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
        searchBadges();
    };
});
</script>
<br />

<div class="admin_table_form">
<form id='multimodify_form' method="post" action="<?php echo $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Badge Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Image") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Topic Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Type") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Active") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Displayed") ?></th>
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
            <td class='admin_table_centered admin_table_email'>
                <?php if(($topic = $item->getTopic())): ?>
                    <?php echo $topic->getTitle(); ?>
                <?php endif; ?>
            </td>
            <td class="admin_table_centered nowrap">
               <?php if(($badgeType = $item->getBadgeType())): ?>
                    <?php echo $this->translate($badgeType); ?>
                <?php endif; ?>
            </td>
            <td class="admin_table_centered nowrap">
                <input type="radio" disabled <?php echo ( $item->active ? $this->translate('checked=checked') : '' ); ?>>  
            </td>
            <td class="admin_table_centered nowrap">
                <input type="radio" disabled <?php echo ( $item->profile_display ? $this->translate('checked=checked') : '' ); ?>>  
            </td>
            <td class='admin_table_centered'>
                <a href='<?php echo $this->url(array('controller' => 'badge', 'action' => 'assign','badge_id' => $item->getIdentity()));?>'>
                    <?php echo $this->translate("Assign") ?>
                </a>
                <a class='smoothbox' href='<?php echo $this->url(array('controller' => 'badge', 'action' => 'edit','badge_id' => $item->getIdentity()));?>'>
                    &nbsp;&nbsp;<?php echo $this->translate("Edit") ?>
                </a>&nbsp;
                <a class='smoothbox' href='<?php echo $this->url(array('controller' => 'badge', 'action' => 'delete','badge_id' => $item->getIdentity()));?>'>
                    <?php echo $this->translate("Delete") ?>
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
</div>