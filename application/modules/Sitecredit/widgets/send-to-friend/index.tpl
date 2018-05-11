<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitecredit
 * @copyright  Copyright 2016-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-03-08 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
 ?>
<?php echo $this->form->setAttrib('class', 'credit_form')->render($this); ?>
<?php
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()->appendStylesheet($baseUrl . 'application/modules/Sitecredit/externals/styles/style_sitecredit.css');
?>
<?php
 $this->headScript()
  ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Observer.js')
  ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.js')
  ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Local.js')
  ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Request.js');
?>


<script type="text/javascript">
var contentAutocomplete;
var maxRecipients = 10;

function removeFromToValue(id, elmentValue,element) {
    // code to change the values in the hidden field to have updated values
    // when recipients are removed.
    var toValues = $(elmentValue).value;
    var toValueArray = toValues.split(",");
    var toValueIndex = "";

    var checkMulti = id.search(/,/);

    // check if we are removing multiple recipients
    if (checkMulti!=-1) {
      var recipientsArray = id.split(",");
      for (var i = 0; i < recipientsArray.length; i++){
        removeToValue(recipientsArray[i], toValueArray, elmentValue);
      }
    } else {
      removeToValue(id, toValueArray, elmentValue);
    }

    // hide the wrapper for element if it is empty
    if ($(elmentValue).value==""){
      $(elmentValue+'-wrapper').setStyle('height', '0');
      $(elmentValue+'-wrapper').setStyle('display', 'none');
    }
    $(element).disabled = false;
  }
 
  function removeToValue(id, toValueArray, elmentValue) {
    for (var i = 0; i < toValueArray.length; i++){
      if (toValueArray[i]==id) toValueIndex =i;
    }
    toValueArray.splice(toValueIndex, 1);
    $(elmentValue).value = toValueArray.join();
  }
en4.core.runonce.add(function()
  {

     contentAutocomplete = new Autocompleter.Request.JSON('friend_name', '<?php 
      if($this->sendCredits) {
        echo $this->url(array('module' => 'user', 'controller' => 'friends', 'action' => 'suggest'), 'default', true);
      } else {
        echo $this->url(array('module' => 'sitecredit', 'controller' => 'index', 'action' => 'getallusers'), 'default', true);
      }
       ?>', {
      'postVar' : 'search',
      'postData' : {'friend_ids': $('friend_id').value},
      'minLength': 1,
      'delay' : 250,
      'selectMode': 'pick',
      'autocompleteType': 'tag',
      'className': 'tag-autosuggest',
      'filterSubset' : true,
      'multiple' : false,
      'injectChoice': function(token){
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
         },
         onPush : function() {
                if ($('friend_id-wrapper')) {
                    $('friend_id-wrapper').style.display='block';
                }
                
             if( $(this.options.elementValues).value.split(',').length >= maxRecipients ) {
                    this.element.disabled = true;
                }
        contentAutocomplete.setOptions({
          'postData' : {'friend_ids': $('friend_id').value}
        });
        
            }

    });
    contentAutocomplete.addEvent('onSelection', function(element, selected, value, input) {
      $('friend_id').value = selected.retrieve('autocompleteChoice').id;
    });

  });

</script>
