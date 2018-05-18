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
<h2><?php echo $this->translate("Parental Guidance Customizations") ?></h2>

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
    var page = <?php echo $this->paginator->getCurrentPageNumber(); ?>;
    direction = direction == 'ASC' ? 'DESC' : 'ASC' ;
    en4.core.loader.clone().inject(loader,"bottom");
    var url = '<?php echo $this->url(array('module' => 'sdparentalguide', 'controller' => 'search', 'action' => 'analytics'), '', true) ?>';
    var req = new Request.HTML({
    url: url,
    data: {
        column_name: column_name,
        order: direction,
        format : 'html',
        page : <?php echo $this->paginator->getCurrentPageNumber(); ?>
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
        var pageContent = elm.getParent(".admin_table");        
        searchLists.inject(pageContent,"after");
        pageContent.destroy();
    }
    });
    req.send();  
}
</script>

<div class="admin_table_form sd_layout_middle">
    <table class='admin_table'>
        <thead>
            <tr>
                <th><a href="javascript:void(0);" onclick="changeOrder(this, 'search_term','<?php echo $this->order ;?>')"><?php echo $this->translate("Search Term"); ?></a></th>
                <th><a href="javascript:void(0);" onclick="changeOrder(this, 'count', '<?php echo $this->order ;?>')"><?php echo $this->translate("Count"); ?></a></th>
                <th><a href="javascript:void(0);" onclick="changeOrder(this, 'gg_dt_created', '<?php echo $this->order ;?>')"><?php echo $this->translate("Date/Time"); ?></a></th>
            </tr>
        </thead>
        <tbody>
            <?php if( count($this->paginator) ): ?>
                <?php foreach( $this->paginator as $item ):
                ?>
                    <tr>
                        <td><?php echo $item->search_term; ?></td>
                        <td class='admin_table_bold'>
                            <?php echo $item->count; ?>
                        </td>
                        <td><?php echo $item->gg_dt_created; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <div>
        <?php echo $this->paginationControl($this->paginator, null, null, array(
            'pageAsQuery' => true,
            'query' => $this->formValues,
            //'params' => $this->formValues,
        )); ?>
    </div>
</div>
