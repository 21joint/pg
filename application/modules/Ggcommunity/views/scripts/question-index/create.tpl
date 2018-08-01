
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
#tags-element .sd_loader {
    float: right;
    position: absolute;
    margin: 17px 0px 0px -26px;    
}    
</style>
<script type="text/javascript">
 en4.core.runonce.add(function(){
    var topicSuggest = en4.core.baseUrl+"api/v1/topic";
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var topicAutoCompleter = new Autocompleter.Request.JSON('tags', topicSuggest, {
      'postVar' : 'topicName',
      'customChoices' : true,
      'ajaxOptions' : {
          method: 'get'
      },
      'minLength': 1,
      'maxChoices': 10,// Max value of displayed items in suggested list
      'selectMode': 'pick',
      'autocompleteType': 'message',
      'className': 'tag-autosuggest',
      'filterSubset' : false,
      'selectFirst': false,
      'multiple' : false,// Only one topic choice
      'tokenValueKey' : 'topicName',
      'tokenIdKey' : 'topicID',
      'onChoiceSelect' : function(choice){
          setTimeout(function(){ $("tags").set("value",choice.get("data-value")); },100);
      },
      onComplete: function(){
            loader.destroy();
      },
      onCancel: function(){
            loader.destroy();
      },
      onRequest: function(){
            loader.inject($("tags"),"after");
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
    });
    topicAutoCompleter.doPushSpan = function(name, toID, newItem, hideLoc, list){
        //doPushSpan;
    };
    topicAutoCompleter.doAddValueToHidden = function(name, toID, hideLoc, newItem, list){
        $("topic_id").value = name;
    };
    topicAutoCompleter.update = function(tokens){
        $(this.choices).empty();
        this.cached = tokens;
        var dataTokens = tokens.body.Results;
        this.cachedQueryValue = this.queryValue;
        var type = dataTokens && $type(dataTokens);
        if (!type || (type == 'array' && !dataTokens.length) || (type == 'hash' && !dataTokens.getLength())) {
            (this.options.emptyChoices || this.hideChoices).call(this);
        } else {
            if (this.options.maxChoices < dataTokens.length && !this.options.overflow) dataTokens.length = this.options.maxChoices;
            var that = this;
            dataTokens.each(function(token){
                var choice = new Element('li', {'class': 'autocompleter-choices', 'value':token.topicName, 'id':token.topicID});
                choice.set("data-value",token.topicName);
                new Element('div', {'html': that.markQueryValue(token.topicName),'class': 'autocompleter-choice'}).inject(choice);
                choice.inputValue = token.topicID;
                this.addChoiceEvents(choice).inject(this.choices);
                choice.store('autocompleteChoice', token);
            }, this);
            this.showChoices();
        }
    };
});
</script>

<div class="holder-back-btn">
  <a onclick="window.history.go(-1); return false;"><svg aria-hidden="true" width="20px" style="margin-right:7px;" data-prefix="fas" data-icon="arrow-circle-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#5CC7CE" d="M256 504C119 504 8 393 8 256S119 8 256 8s248 111 248 248-111 248-248 248zm28.9-143.6L209.4 288H392c13.3 0 24-10.7 24-24v-16c0-13.3-10.7-24-24-24H209.4l75.5-72.4c9.7-9.3 9.9-24.8.4-34.3l-11-10.9c-9.4-9.4-24.6-9.4-33.9 0L107.7 239c-9.4 9.4-9.4 24.6 0 33.9l132.7 132.7c9.4 9.4 24.6 9.4 33.9 0l11-10.9c9.5-9.5 9.3-25-.4-34.3z"</path></svg>go back</a>
</div>

<?php echo $this->form->render($this); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl."application/modules/Pgservicelayer/externals/scripts/core.js"); ?>
<script>
  var textarea = document.getElementById("title");
  textarea.addEventListener('keyup',function() {
    if (textarea.value.length > 57 ) {
      document.getElementById("title").rows = '2';
    } else {
      document.getElementById("title").rows = '1';
    }
  });
    
  // Functionality for Submit Draft Button
  // var submit  = document.getElementById('submit_draft');
  // submit.onclick = function(){
  //   document.getElementById('draft').value = 1;
  // };
  document.getElementById("submit_draft").addClass("d-none");
  // Taking it out of the view of the page with d-none attribute

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
<script type='text/javascript'>
en4.core.runonce.add(function(){
    $$(".extfox_form").addEvent("submit",function(event){
        event.preventDefault();
        if($("sd_response_error")){
            $("sd_response_error").destroy();
        }
        var formValid = true;
        var title = $("title").value.trim();
        var topicID = $("topic_id").value;
        if(title.length <= 0){
            formValid = false;
        }
        if(topicID.length <= 0){
            formValid = false;
        }
        if(!formValid){
            //return;
        }
        var closeDate = null;
        if($("date_closed-day").get("value") != '0' && $("date_closed-month").get("value") != '0' && $("date_closed-year").get("value") != '0'){
            closeDate = $("date_closed-year").get("value")+"-"+$("date_closed-month").get("value")+"-"+$("date_closed-day").get("value");
        }
        
        var form = $(this);
        var loader = en4.pgservicelayer.loader.clone();
        new Request.JSON({
            'url' : en4.core.baseUrl+'api/v1/question',
            emulation: false,
            method: 'post',
            data: {
              'photoID': $("fancyuploadfileids").value.trim(),
              'title': title,
              'topicID':topicID,
              'body': tinymce.get("body").getContent(),
              'closedDateTime': closeDate
            },
            onRequest: function(){
                loader.inject($("global_form_front"),"after");
            },
            'onSuccess' : function(responseJSON) {                
                if(responseJSON.status_code == 200){
                    responseJSON.body.Results.each(function(question){
                        window.location.href = en4.core.baseUrl+"struggles/question/"+question.questionID;
                    });
                }else{
                    loader.destroy();
                    en4.pgservicelayer.handleResponseError(responseJSON,form.getElement(".form-elements"),"before");
                    var myFx = new Fx.Scroll($(document.body), {
                        offset: {
                            x: 0,
                            y: form.getPosition().y
                        }
                    }).toTop();
                }
            }
        }).send();
        
    });
});    
</script>

<script type="text/javascript">
var uploaderInstance = null;
en4.core.runonce.add(function () {
    uploaderInstance = new Uploader('upload_file', {
      uploadLinkClass : 'buttonlink icon_photos_new',
      uploadLinkTitle : '<?php echo $this->translate("Add Photos");?>',
      uploadLinkDesc : '',
      singleUpload: true,
      uploadLimit: 1
    });
});

 var deleteFile = function (el) {
    var photo_id = el.get('data-file_id');
    $("upload_file_link").setStyle("display","");
    el.getParent('li').destroy();
    new Request.JSON({
      'url' : en4.core.baseUrl+'api/v1/photo',
      emulation: false,
      method: 'delete',
      data: {
        'photoID': photo_id,
      },
      'onSuccess' : function(responseJSON) {
          
      }
    }).send();
}
</script>