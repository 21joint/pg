<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sdparentalguide
 * @author     Stars Developer
 */
?>

<div class="search_buttons">
    <div>
        <button type='submit' onclick="searchSearch(this);" id='sd_searchterm_button'><?php echo $this->translate("Search"); ?></button>
    </div>
    <div>
        <a href="<?php echo $this->url(array('action' => 'create')); ?>" class='create_search smoothbox'><button type='button'><?php echo $this->translate("New Search Term"); ?></button></a>
    </div>
</div>

<script type='text/javascript'>
en4.core.runonce.add(function(){
    try{
        $("filter_form").addEvent("submit",function(event){
            event.preventDefault();
            searchSearch($("sd_searchterm_button"));
        });
    }catch(e){ console.log(e); }
});    
</script>