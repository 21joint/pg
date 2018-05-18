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
en4.core.runonce.add(function(){
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    
    var autoCompleter = new Autocompleter.Request.JSON('username', '<?php echo $this->url(array('action' => 'suggest-username'), 'sdparentalguide_general', true) ?>', {
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
        },
        onCommand: function(){
            $("user_id").value = '';
        }
    });
    autoCompleter.doAddValueToHidden = function(name, toID, hideLoc, newItem, list){
        $("username").value = name;
        $("username").blur();
        $("user_id").value = toID;
    };
    
    var autoCompleter2 = new Autocompleter.Request.JSON('first_name', '<?php echo $this->url(array('action' => 'suggest-displayname'), 'sdparentalguide_general', true) ?>', {
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
            loader.inject($("first_name"),"after");
        },
        onCommand: function(){
            $("user_id").value = '';
        }
    });
    autoCompleter2.doAddValueToHidden = function(name, toID, hideLoc, newItem, list){
        $("first_name").value = name;
        $("first_name").blur();
        $("user_id").value = toID;
    };
    
    var autoCompleter3 = new Autocompleter.Request.JSON('last_name', '<?php echo $this->url(array('action' => 'suggest-displayname'), 'sdparentalguide_general', true) ?>', {
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
            loader.inject($("last_name"),"after");
        },
        onCommand: function(){
            $("user_id").value = '';
        }
    });
    autoCompleter3.doAddValueToHidden = function(name, toID, hideLoc, newItem, list){
        $("last_name").value = name;
        $("last_name").blur();
        $("user_id").value = toID;
    };
    
});    

function loadSubCategories(){
    var categoryId = $("category_id").get("value");
    if(categoryId.length <= 0){
        $$(".sd_listing_search .sd_inline_field.subcategory").setStyle("display","none");
        return;
    }
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var url = '<?php echo $this->url(array('action' => 'get-subcategories'), 'sdparentalguide_general', true) ?>';
    var req = new Request.JSON({
      url: url,
      data: {
          category_id: categoryId,
          format: 'json'
      },
      onRequest: function(){
          loader.inject($("category_id"),"after");
      },
      onFail: function(){
          loader.destroy();
      },
      onCancel: function(){
          loader.destroy();
      },
      onSuccess: function(responseJSON){
          loader.destroy();
          $("subcategory_id").empty();
          if(responseJSON.status && responseJSON.categories.length > 0){
              var options = "<option value='0'><?php echo $this->translate('Sub Category'); ?></option>";
              Object.each(responseJSON.categories,function(category){
                  options += "<option value='"+category.id+"'>"+category.title+"</option>";
              });
              $("subcategory_id").set("html",options);
              $$(".sd_listing_search .sd_inline_field.subcategory").setStyle("display","inline-block");
          }else{
              $$(".sd_listing_search .sd_inline_field.subcategory").setStyle("display","none");
          }
      }
    });
    req.send();
}
function loadCategories(element){
  var loader = en4.core.loader.clone();
  loader.addClass("sd_loader");
  $("category-wrapper").setStyle("display","none");
  $$(".sd_listing_search .sd_inline_field.subcategory").setStyle("display","none");
  var url = '<?php echo $this->url(array('action' => 'get-categories'), 'sdparentalguide_general', true) ?>';
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
          if(responseJSON.status && responseJSON.categories.length > 0){
              $("category_id").set("html","");
              var options = "<option value='0'><?php echo $this->translate('Category'); ?></option>";
              Object.each(responseJSON.categories,function(category){
                  options += "<option value='"+category.id+"'>"+category.title+"</option>";
              });
              $("category_id").set("html",options);
              $("category-wrapper").setStyle("display","inline-block");
          }
      }
  });
  req.send();
}
</script>