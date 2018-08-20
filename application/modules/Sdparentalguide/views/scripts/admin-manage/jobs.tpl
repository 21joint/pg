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
<?php echo $this->formFilterJobs->render($this) ?>
<div class="admin_table_form" style="clear:both;display:none;">
<form id='multimodify_form' method="post" action="<?php echo $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("User Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("First Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Last Name") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Email") ?></th>
        <th style='width: 20%;' class='admin_table_centered'><?php echo $this->translate("Options") ?></th>
      </tr>
    </thead>
    <tbody>
      
    </tbody>
  </table>
  <br />
</form>
</div>
<div class="tabs">
  <ul class="navigation sd_tasks_navigation">
    <li class="active job_tab"><a href="javascript:void(0);" onclick="changeTabs('job');">User Jobs</a></li>
    <li class=" content_tab"><a href="javascript:void(0);" onclick="changeTabs('content');">Content Jobs</a></li>
    <li class=" database_tab"><a href="javascript:void(0);" onclick="changeTabs('database');">Database Operations</a></li>
  </ul>
</div>
<?php
  $jobsArray = array('Contribution', 'Following', 'ContributionLevel', 'Reviews', 'Questions', 'Answers', 'Guide', 'Badges', 'CalMemberViews', 'CalMemberClicks');
  $contentArray = array('CalGuideViews', 'CalGuideClicks', 'CalReviewViews', 'CalReviewClicks', 'CalQuestionViews', 'CalQuestionClicks');
  $databaseArray = array('SearchAnalytics');
?>

<div class='clear'>
    <div class='search'>
        <form>
            <ul class="sd_jobs_list">
                <li class="sd_jobs_header">
                    <ul>
                        <li><?php echo $this->translate("Job Description"); ?></li>
                        <li><?php echo $this->translate("Action"); ?></li>
                    </ul>
                </li>
                <?php foreach($this->tasks as $task): ?>
                    <?php $exp = explode("Sdparentalguide_Plugin_Task_", $task["plugin"]); ?>
                    <li class="sd_tasks <?php if(in_array($exp[1], $jobsArray)){ echo 'job'; }elseif(in_array($exp[1], $contentArray)){ echo 'content'; }elseif(in_array($exp[1], $databaseArray)){ echo 'database'; } ?>">
                        <ul>
                            <li><?php echo $task->getTitle(); ?></li>
                            <li><button type="button" <?php echo $task->enabled?'':'disabled="disabled"'; ?> onclick="runCustomTask(this,'<?php echo $task->getIdentity(); ?>','1');"><?php echo $this->translate("Run"); ?></button></li>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        </form>
    </div>
</div>
<script type='text/javascript'>
  function changeTabs(tab){
    $$(".sd_tasks_navigation li").removeClass("active");
    $$(".sd_tasks_navigation li." + tab + "_tab").addClass("active");
    $$(".sd_tasks").hide();
    $$(".sd_tasks." + tab).show();
  }
function runCustomTask(element,taskId,page){
    if(page == '1' && $("job_user").get("value").length <= 0){
        var confirm = window.confirm("<?php echo $this->translate('Are you sure you want to process for All Users?'); ?>");
        if(!confirm){
            return;
        }
    }
    var smoothBoxHtml = "<div class='sd_task_loader'><h3><?php echo $this->translate('Running Task'); ?></h3></div>";
    if(page == '1'){
        var smoothBoxHtmlElement = new Element("div",{
            'html': smoothBoxHtml,
            'id': 'sd_task_loader'
        });
        Smoothbox.open(smoothBoxHtmlElement,{ mode: 'Inline', width: 250 });
    }    
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var req = new Request.JSON({
        url: "<?php echo $this->url(); ?>",
        data: {
            format: 'json',
            task_id: taskId,
            page: page,
            job_user: $("job_user").value
        },
        onRequest: function(){
            loader.inject($$(".sd_task_loader")[0],"bottom");
        },
        onError: function(){
            runCustomTask(element,taskId,page);
        },
        onSuccess: function(responseJSON){
            if(responseJSON.status && responseJSON.nextPage){
                runCustomTask(element,taskId,responseJSON.nextPage);
            }else{
                $$(".sd_task_loader h3").set("html","<?php echo $this->translate('Task Completed Successfully.'); ?>");
                loader.destroy();
                setTimeout(function(){
                    Smoothbox.close()
                },3000);
            }
        },
        onComplete: function(){
            loader.destroy();
        }
    });
    req.send();
}
en4.core.runonce.add(function(){
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    
    var autoCompleter = new Autocompleter.Request.JSON('displayname', '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'suggest-user','format' => 'json'), 'admin_default', true) ?>', {
        'minLength': 3,
        'delay' : 250,
        'postVar': 'username',
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
            $("job_user").value = '';
            $$(".admin_table_form").setStyle("display","none");
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
            loader.inject($("displayname"),"after");
        }
    });
    autoCompleter.doPushSpan = function(name, toID, newItem, hideLoc, list){
        //doPushSpan;
    };
    autoCompleter.doAddValueToHidden = function(name, toID, hideLoc, newItem, list){
        $("displayname").value = name;
        $("displayname").blur();
        $("job_user").value = toID;
        loadUserData(toID);
        $$(".admin_table_form").setStyle("display","block");
    };
    
    var autoCompleterEmail = new Autocompleter.Request.JSON('email', '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'suggest-user','type' => 'email','format' => 'json'), 'admin_default', true) ?>', {
        'minLength': 3,
        'delay' : 250,
        'postVar': 'email',
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
            $("job_user").value = '';
            $$(".admin_table_form").setStyle("display","none");
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
            loader.inject($("email"),"after");
        }
    });
    autoCompleterEmail.doPushSpan = function(name, toID, newItem, hideLoc, list){
        //doPushSpan;
    };
    autoCompleterEmail.doAddValueToHidden = function(name, toID, hideLoc, newItem, list){
        $("email").value = name;
        $("email").blur();
        $("job_user").value = toID;
        loadUserData(toID);
        $$(".admin_table_form").setStyle("display","block");
    };
    
    var autoCompleterFirstName = new Autocompleter.Request.JSON('first_name', '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'suggest-user','type' => 'first_name','format' => 'json'), 'admin_default', true) ?>', {
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
            $("job_user").value = '';
            $$(".admin_table_form").setStyle("display","none");
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
        }
    });
    autoCompleterFirstName.doPushSpan = function(name, toID, newItem, hideLoc, list){
        //doPushSpan;
    };
    autoCompleterFirstName.doAddValueToHidden = function(name, toID, hideLoc, newItem, list){
        $("first_name").value = name;
        $("first_name").blur();
        $("job_user").value = toID;
        loadUserData(toID);
        $$(".admin_table_form").setStyle("display","block");
    };
    
    var autoCompleterLastName = new Autocompleter.Request.JSON('last_name', '<?php echo $this->url(array('module' => 'sdparentalguide','controller' => 'manage','action' => 'suggest-user','type' => 'last_name','format' => 'json'), 'admin_default', true) ?>', {
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
            $("job_user").value = '';
            $$(".admin_table_form").setStyle("display","none");
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
        }
    });
    autoCompleterLastName.doPushSpan = function(name, toID, newItem, hideLoc, list){
        //doPushSpan;
    };
    autoCompleterLastName.doAddValueToHidden = function(name, toID, hideLoc, newItem, list){
        $("last_name").value = name;
        $("last_name").blur();
        $("job_user").value = toID;
        $$(".admin_table_form").setStyle("display","block");
        loadUserData(toID);
    };
});
function clearJobSearch(){
    $("job_user").value = '';
    $("email").value = "";
    $("first_name").value = "";
    $("displayname").value = "";
    $("last_name").value = "";
    $$(".admin_table_form").setStyle("display","none");
}
function loadUserData(toID){
    var tbody = $("multimodify_form").getElement("tbody");
    tbody.empty();
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var req = new Request.JSON({
        url: "<?php echo $this->url(array('action' => 'user-detail','format' => 'json')); ?>",
        data: {
            user_id: toID
        },
        onRequest: function(){
            loader.inject(tbody,"bottom");
        },
        onError: function(){
            loader.destroy();
        },
        onCancel: function(){
            loader.destroy();
        },
        onSuccess: function(responseJSON){
            loader.destroy();
            if(!responseJSON.status){
                return;
            }
            var tr = new Element("tr",{
                html: "<td class='admin_table_centered admin_table_user'><a href='"+responseJSON.data.href+"' target='_blank'>"+responseJSON.data.username+"</a></td>"+
                        "<td class='admin_table_centered admin_table_user'>"+responseJSON.data.first_name+"</td>"+
                        "<td class='admin_table_centered admin_table_user'>"+responseJSON.data.last_name+"</td>"+
                        "<td class='admin_table_centered admin_table_user'>"+responseJSON.data.email+"</td>"+"<td></td>"
            });
            tr.inject(tbody,"bottom");
        }
    });
    req.send();
}
</script>

<style type="text/css">
.sd_jobs_list li {
    overflow: hidden;
}
.sd_jobs_list > li + li {
    margin-top: 10px;
}
.sd_jobs_list li ul > li {
    display: block;
    float: left;
    width: 300px;
}
.sd_jobs_list li.sd_jobs_header li{
    font-weight: bold;
}
.sd_jobs_list button:disabled {
    cursor: no-drop;
}
.sd_clear_filter {
    margin-top: 15px;
}
.sd_tasks {
    display: none;
}
.sd_tasks.job {
    display: block;
}
</style>