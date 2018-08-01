
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

<script type="text/javascript">
    en4.core.runonce.add(function()
    {
      new Autocompleter.Request.JSON('tags', '<?php echo $this->url(array('module' => 'ggcommunity','controller' => 'question-index', 'action' => 'topic'), 'default', true) ?>', {
        'postVar' : 'text',
        'customChoices' : true,
        'minLength': 1,
        'maxChoices': 1,
        'selectMode': 'pick',
        'autocompleteType': 'tag',
        'className': 'tag-autosuggest',
        'filterSubset' : true,
        'multiple' : true,
        'injectChoice': function(token){
          var choice = new Element('li', {'class': 'autocompleter-choices', 'value':token.label, 'id':token.id});
          new Element('div', {'html': this.markQueryValue(token.label),'class': 'autocompleter-choice'}).inject(choice);
          choice.inputValue = token;
          this.addChoiceEvents(choice).inject(this.choices);
          choice.store('autocompleteChoice', token);
        }
      });
    });
</script>

<div class="large-8">
  <?php echo $this->form->render($this); ?>
</div>

<script>
  var textarea = document.getElementById("title");
  if (textarea.value.length >57 ) {
    document.getElementById("title").rows = '2';
  }
  textarea.addEventListener('keyup',function() {
    if (textarea.value.length >55 ) {
      document.getElementById("title").rows = '2';
    } else {
      document.getElementById("title").rows = '1';
    }
  });

</script>





	
