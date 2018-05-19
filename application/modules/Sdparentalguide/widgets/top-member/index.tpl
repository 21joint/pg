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
$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/style_infotooltip.css');
?>
<?php
  $baseUrl = $this->layout()->staticBaseUrl;
  $this->headLink()->appendStylesheet($baseUrl . 'application/modules/Sitecredit/externals/styles/style_sitecredit.css');
?>
<?php 
$this->headLink()
    ->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/style_icon_toolbar.css');
?>
<script type="text/javascript">
  
  en4.core.runonce.add(function() {
       // Add hover event to get tool-tip
   var show_tool_tip=false;
   var counter_req_pendding=0;
    $$('.sea_add_tooltip_link').addEvent('mouseover', function(event) {  
      var el = $(event.target); 
      ItemTooltips.options.offset.y=el.offsetHeight;
      ItemTooltips.options.showDelay=100;
        if(!el.hasAttribute("rel")){
                  el=el.parentNode;      
           } 
       show_tool_tip=true;
      if( !el.retrieve('tip-loaded', false) ) {
       counter_req_pendding++;
       var resource='';
      if(el.hasAttribute("rel"))
         resource=el.rel;
       if(resource =='')
         return;
      
        el.store('tip-loaded', true);
       el.store('tip:title', '<div class="" style="">'+
 ' <div class="uiOverlay info_tip" style="width: 300px; top: 0px; ">'+
    '<div class="info_tip_content_wrapper" ><div class="info_tip_content"><div class="info_tip_content_loader">'+
  '<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Core/externals/images/loading.gif" alt="Loading" /><?php echo $this->translate("Loading ...") ?></div>'+
'</div></div></div></div>'  
);
        el.store('tip:text', '');       
        // Load the likes
        var url = en4.core.baseUrl+'/seaocore/feed/show-tooltip-info';
        el.addEvent('mouseleave',function(){
         show_tool_tip=false;  
        });       
     
        var req = new Request.HTML({
          url : url,
          data : {
          format : 'html',
          'resource':resource
        },
        evalScripts : true,
        onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {          
            el.store('tip:title', '');
            el.store('tip:text', responseHTML);
            ItemTooltips.options.showDelay=0;
            ItemTooltips.elementEnter(event, el); // Force it to update the text 
             counter_req_pendding--;            
              if(!show_tool_tip || counter_req_pendding>0){             
              //ItemTooltips.hide(el);
              ItemTooltips.elementLeave(event,el);
             }
            var tipEl=ItemTooltips.toElement();          
            tipEl.addEvents({
              'mouseenter': function() {
               ItemTooltips.options.canHide = false;
               ItemTooltips.show(el);
              },
              'mouseleave': function() {                
              ItemTooltips.options.canHide = true;
              ItemTooltips.hide(el);                    
              }
            });
           Smoothbox.bind($$(".sea_add_tooltip_link_tips"));
          }
        });
        req.send();
      }
    });
    // Add tooltips
   var window_size = window.getSize()
   var ItemTooltips = new SEATips($$('.sea_add_tooltip_link'), {
      fixed : true,
      title:'',
      className : 'sea_add_tooltip_link_tips',
      hideDelay :0,
      offset : {'x' : 0,'y' : 0},
      windowPadding: {'x':370, 'y':(window_size.y/2)}
    }
    );  
  });  
</script>

<style type="text/css">
.layout_sdparentalguide_top_member .sd_rank {
    vertical-align: middle;
    font-size: 20px;
    font-weight: bold;    
}
.sd_active_member_wrap {
    display: inline-block;
    vertical-align: top;    
    min-width: 150px;    
}
.sd_user_badge {
    display: inline-block;
    vertical-align: middle;   
    width: 100px;
}
.sd_user_badge .badge_title {
    font-weight: bold;
}
.sd_featured_user {
    display: inline-block;
    vertical-align: middle;       
}
</style>
<?php $api = Engine_Api::_()->sdparentalguide(); ?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<div class="top_members_table">
    <table>
      <?php foreach($this->paginator as $key => $user ): ?>
      <?php if($this->page == 1): ?>
        <?php $rank = $key+1; ?>
      <?php else: ?>
        <?php $rank = (($this->page-1)*$this->perPage)+($key+1); ?>
      <?php endif; ?>
      
      <tr>
        <td style="width: 50px;">
            <span class="sd_rank"><?php echo $rank; ?></span>
            <span><?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon')); ?></span>
        </td>
        <td>
            <div class="sd_active_member_wrap">
            <div class="active_member_title"><?php echo $this->htmlLink($user->getHref(), Engine_Api::_()->seaocore()->seaocoreTruncateText($user->getTitle(),15), array('title' => $user->getTitle())) ?></div>
           <?php if($this->basedon=='activities') :  ?>
             <div class="active_member_icon">
               <span> <?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/icons/activity.png'." />"; ?> </span>
               <span class="active_member_icon_points"> <?php echo $user->gg_activities; ?></span>
               <?php else : ?>
               <span><?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/icons/credit.png'." />"; ?> </span>
               <span class="active_member_icon_points"><?php echo $user->gg_contribution; ?></span>
             </div>
             <?php endif; ?>
            </div>
            <?php $badge = $api->getUserBadge($user->gg_contribution); ?>
            <div class="sd_user_badge">
                <?php if(!empty($badge)): ?>            
                    <?php echo $this->htmlLink(array('route' => 'credit_general', 'module' => 'sitecredit', 'controller' => 'index', 'action' => 'view-detail', 'id' => 
                    $badge->badge_id), $this->itemPhoto($badge, 'thumb.icon'),array('class' => 'smoothbox'));?>
                    <div class="badge_title"><?php echo $this->translate($badge->title) ?></div>            
                <?php endif; ?>
            </div>
            <?php if($user->gg_featured): ?>
            <div class="sd_featured_user">
                <img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sdparentalguide/externals/images/featured_small.png"/>
            </div>
            <?php endif; ?>
        </td>
      </tr>
      <?php  endforeach;  ?>
    </table>   
    
    <?php if( $this->rawdata ):
        $pagination = $this->paginationControl($this->rawdata, null, null, array(
          'pageAsQuery' => true,
        ));
      ?>
      <?php if( trim($pagination) ): ?>
        <div class='browsemembers_viewmore' id="browsemembers_viewmore">
          <?php echo $pagination ?>
        </div>
      <?php endif ?>
    <?php endif; ?>
</div>