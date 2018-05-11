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



<?php $viewer = Engine_Api::_()->user()->getViewer();?>
  <div class="top_members_table">
    <table>
      <?php foreach($this->rawdata as $data ) : 
      $users=Engine_Api::_()->user()->getUser($data->user_id);?>
      <tr>
        <td style="width: 50px;">
          <span><?php echo $this->htmlLink($users->getHref(), $this->itemPhoto($users, 'thumb.icon')); ?></span>
        </td>
        <td>
           <div class="active_member_title"><?php echo $this->htmlLink($users->getHref(), Engine_Api::_()->seaocore()->seaocoreTruncateText($users->getTitle(),15), array('title' => $users->getTitle(), 'class' => 'sea_add_tooltip_link', 'rel' => 'user' . ' ' . $users->user_id)) ?></div>
          <?php if($this->basedon=='activities') :  ?>
          <div class="active_member_icon">
            <span> <?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/icons/activity.png'." />"; ?> </span>
            <span class="active_member_icon_points"> <?php echo $data->activities; ?></span>
            <?php else : ?>
            <span><?php echo "<img src =".$this->layout()->staticBaseUrl.'application/modules/Sitecredit/externals/images/icons/credit.png'." />"; ?> </span>
            <span class="active_member_icon_points"><?php echo $data->credit; ?></span>
          </div>
            <?php endif; ?>
        </td>
      </tr>
      <?php  endforeach;  ?>
    </table>   
  </div>