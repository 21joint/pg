<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9915 2013-02-15 01:30:19Z alex $
 * @author     John
 */
?>
<h2><?= $this->translate("Parental Guidance Customizations") ?></h2>

<style type='text/css'>
.admin_table tr td a {
    padding-left: 5px;
}
.search_search #fieldset-searchgrp label{
    display: none;
}
.search_search .search_buttons {
    margin-top: 4px;
}
.create_search {
    margin-top: 5px;
    display: inline-block;
}
#global_content{
    border: 1px solid #ddd;
    padding: 10px;
}
.search_search {
    margin-bottom: 30px;
}
.sort_active:after {
    content: '';
    display: inline-block;
    font: normal normal normal 14px/1 FontAwesome;
    font-size: inherit;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    margin-left: 3px;
    font-size: 17px;   
}
.sort_active.asc:after {
    content: '\f0de';
    position: relative;
    top: 7px;
}
.sort_active.desc:after {
    content: '\f0dd';    
}
</style>

<?php if( count($this->navigation) ): ?>
    <div class='tabs'>
        <?php
            // Render the menu
            //->setUlClass()
            echo $this->navigation()->menu()->setContainer($this->navigation)->render()
        ?>
    </div>
<?php endif; ?>

<div class='sd_layout_left'>
    <?php if( count($this->navigation2) ): ?>
        <div class='tabs_left'>
            <?php
                // Render the menu
                //->setUlClass()
                echo $this->navigation()->menu()->setContainer($this->navigation2)->render()
            ?>
        </div>
    <?php endif; ?>
</div>



<script type="text/javascript">
function changeOrder(elm, column_name, direction){
    var content = elm.getParent(".admin_table_form");
    var loader = new Element('div',{'class':'sd_loader'});
    var page = <?= $this->paginator->getCurrentPageNumber(); ?>;
    direction = direction == 'ASC' ? 'DESC' : 'ASC' ;
    en4.core.loader.clone().inject(loader,"bottom");
    var url = '<?= $this->url(array('module' => 'sdparentalguide', 'controller' => 'search', 'action' => 'analytics'), '', true) ?>';
    var req = new Request.HTML({
    url: url,
    data: {
        column_name: column_name,
        order: direction,
        format : 'html',
        page : <?= $this->paginator->getCurrentPageNumber(); ?>,
        query: $("search_query").value
    },
    onRequest: function(){
        loader.inject(content.getElement(".admin_table"),"after");
    },
    onSuccess: function(responseTree, responseElements, responseHTML, responseJavaScript){
        loader.destroy();
        var div = new Element('div',{
            'html': responseHTML
        });   
        var searchLists = div.getElement(".admin_table");
        if(!searchLists){
            return;
        }
        var pageContent = elm.getParent(".admin_table_form").getElement(".admin_table");        
        searchLists.inject(pageContent,"after");
        pageContent.destroy();
    }
    });
    req.send();  
}
function startSearch(element){
    var direction = '<?= $this->order; ?>';
    var order = '<?= $this->order_column; ?>';
    changeOrder(element, order, direction);
    event.preventDefault();
}
</script>

<div class="admin_table_form sd_layout_middle">
    <form method="post" onsubmit="startSearch(this);">
        <input type='text' id='search_query' placeholder="<?= $this->translate('Search'); ?>" style='margin-right:5px;vertical-align: middle;'/>
        <button onclick="startSearch(this);" style='margin-right:5px;vertical-align: middle;' type="submit"><?= $this->translate("Search"); ?></button>
        <a href='<?= $this->url(array("clear" => 1)); ?>'><button type="button"><?= $this->translate("Clear All"); ?></button></a>
    </form>
    <br>
    <table class='admin_table'>
        <thead>
            <tr>
                <th><a href="javascript:void(0);" class="<?= $this->order_column == 'search_term'?'sort_active '.strtolower($this->order):''; ?>" onclick="changeOrder(this, 'search_term','<?= $this->order ;?>')"><?= $this->translate("Search Term"); ?></a></th>
                <th><a href="javascript:void(0);" class="<?= $this->order_column == 'count'?'sort_active '.strtolower($this->order):''; ?>" onclick="changeOrder(this, 'count', '<?= $this->order ;?>')"><?= $this->translate("Count"); ?></a></th>
                <th><a href="javascript:void(0);" class="<?= $this->order_column == 'gg_dt_created'?'sort_active '.strtolower($this->order):''; ?>" onclick="changeOrder(this, 'gg_dt_created', '<?= $this->order ;?>')"><?= $this->translate("Date/Time"); ?></a></th>
            </tr>
        </thead>
        <tbody>
            <?php if( count($this->paginator) ): ?>
                <?php foreach( $this->paginator as $item ):
                ?>
                    <tr>
                        <td><?= $item->search_term; ?></td>
                        <td class='admin_table_bold'>
                            <?= $item->count; ?>
                        </td>
                        <td><?= $item->gg_dt_created; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3"><div class="tip"><span><?= $this->translate("No search analytics found."); ?></span></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div>
        <?= $this->paginationControl($this->paginator, null, null, array(
            'pageAsQuery' => true,
            'query' => $this->formValues,
            //'params' => $this->formValues,
        )); ?>
    </div>
</div>
