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



<?php echo $this->form->render($this); ?>

<script type="text/javascript">
function previewBadgeIcon(element){
    var files = element.files;
    var parent = $(element).getParent();
    if(files.length <= 0){
        return;
    }
    
    var reader = new FileReader();
    reader.onload = function(){
        var dataURL = reader.result;
        if(parent.getElement(".icon_preview")){
            parent.getElement(".icon_preview").destroy();
        }
        var div = new Element("div",{
            'class': 'icon_preview',
        });
        var img = new Element("img",{
            'src': dataURL
        });
        img.inject(div,"bottom");
        div.inject(parent,"bottom");
        window.parent.Smoothbox.instance.doAutoResize();
    };
    reader.readAsDataURL(files[0]);
    
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
<style type="text/css">
.icon_preview {
    margin-top: 10px;
}
.icon_preview img {
    max-width: 150px;
}    
</style>