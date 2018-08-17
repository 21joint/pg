<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<script type="text/javascript">
  function check_submit()
  { 
    if(document.getElementById('user_id').value == '' ) 
    {
      return false;
    }
    else 
    {
      return true;
    }
  }
</script>

<?php
$this->headScript()
        ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Observer.js')
        ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.js')
        ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Local.js')
        ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Request.js');
?>

<div class="sitereview_admin_popup">
  <div>
    <?php echo $this->form->setAttrib('class', 'global_form_popup')->render($this) ?>
  </div>
</div>

<script type="text/javascript">
  //en4.core.runonce.add(function()
  //{
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var autoCompleter = new Autocompleter.Request.JSON('title', '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'suggest-user','format' => 'json'), 'admin_default', true) ?>', {
        'minLength': 3,
        'delay' : 250,
        'postVar': 'displayname',
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
            $("user_id").value = '';
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
            loader.inject($("title"),"after");
        }
    });
    autoCompleter.doPushSpan = function(name, toID, newItem, hideLoc, list){
        //doPushSpan;
    };
    autoCompleter.doAddValueToHidden = function(name, toID, hideLoc, newItem, list){
        $("title").value = name;
        $("title").blur();
        $("user_id").value = toID;
    };
  //});
  
</script>
