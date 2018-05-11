
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
  en4.core.runonce.add(function(){

    new Autocompleter.Request.JSON('tags', '<?php echo $this->url(array('module' => 'ggcommunity', 'controller' => 'question-index', 'action' => 'topic'), 'default', true) ?>', {
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

<div class="holder-back-btn">
  <a onclick="window.history.go(-1); return false;"><svg aria-hidden="true" width="20px" style="margin-right:7px;" data-prefix="fas" data-icon="arrow-circle-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#5CC7CE" d="M256 504C119 504 8 393 8 256S119 8 256 8s248 111 248 248-111 248-248 248zm28.9-143.6L209.4 288H392c13.3 0 24-10.7 24-24v-16c0-13.3-10.7-24-24-24H209.4l75.5-72.4c9.7-9.3 9.9-24.8.4-34.3l-11-10.9c-9.4-9.4-24.6-9.4-33.9 0L107.7 239c-9.4 9.4-9.4 24.6 0 33.9l132.7 132.7c9.4 9.4 24.6 9.4 33.9 0l11-10.9c9.5-9.5 9.3-25-.4-34.3z"</path></svg>go back</a>
</div>

<?php echo $this->form->render($this); ?>

<script>
  var textarea = document.getElementById("title");
  textarea.addEventListener('keyup',function() {
    if (textarea.value.length > 57 ) {
      document.getElementById("title").rows = '2';
    } else {
      document.getElementById("title").rows = '1';
    }
  });
    
  var submit  = document.getElementById('submit_draft');
  submit.onclick = function(){
    document.getElementById('draft').value = 1;
  };

  // this is for changing options for year of closed date, take from current year and next 10 years
  var select_year = document.getElementById("date_closed-year");
  var year = (new Date()).getFullYear();

  select_year.options.length = 0;
  var options = select_year.getElementsByTagName('option');
  var opt = document.createElement('option');
  opt.innerHTML = "";
  opt.value = 0;
  document.getElementById("date_closed-year").appendChild(opt);

  for(var i = 0;  i< 10; i++) {
    var opt = document.createElement('option');
    opt.innerHTML = year + i;
    opt.value = year + i;
    document.getElementById("date_closed-year").appendChild(opt);
  }
</script>






	
