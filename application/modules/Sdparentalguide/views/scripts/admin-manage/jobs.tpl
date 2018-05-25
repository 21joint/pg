<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
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
                    <li class="sd_tasks">
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
function runCustomTask(element,taskId,page){
    
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
            page: page
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
</style>