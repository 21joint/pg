<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Seaocore
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2015-05-15 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<script type="text/javascript">
function toggleNavigation(obj){
 
 navigation = obj.getNext('ul');
 if(navigation.style.display=='block'){
  navigation.style.display='none';
  obj.removeClass('menu_icon_active');
 }else{
  navigation.style.display='block';
  obj.addClass('menu_icon_active');
 }
return false;
}
</script>
<?php
$displayMenus = true;
if(Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode') && (Engine_API::_()->seaocore()->isMobile() || Engine_API::_()->seaocore()->isTabletDevice())) {
    //$displayMenus = false;  
}
?>
<div class="captivate_main_menu">
    <?php $key = 0; ?>
    <a class="menu_icon" href="javascript:void(0);" onclick="return toggleNavigation(this)"><i class="fa fa-navicon"></i></a>
    <ul class='navigation'>
        <?php if($displayMenus):?>
            <?php foreach ($this->browsenavigation as $nav): ?>
         <?php $key++ ?>
            <?php if (isset($nav->show_to_guest) && empty($nav->show_to_guest) && !$this->viewer()->getIdentity()): ?>
                <?php continue; ?>
            <?php endif; ?>

            <?php if ($key < $this->max): ?>
                <li 
                <?php
                if ($nav->active): echo "class='active'";
                endif;
                ?> >
                        <?php if ($nav->action): ?>
                        <a class= "<?php echo $nav->class ?>" href='<?php echo empty($nav->uri) ? $this->url(array('action' => $nav->action), $nav->route, true) : $nav->uri ?>'><?php echo $this->translate($nav->label); ?></a>
                    <?php else : ?>
                        <a class= "<?php echo $nav->class ?>" href='<?php echo $nav->getHref() ?>'><?php echo $this->translate($nav->label); ?></a>
                    <?php endif; ?>
                </li>
            <?php else: ?>
                <?php break; ?>
            <?php endif; ?>
           
        <?php endforeach; ?>
        <?php endif; ?>
        <?php if (count($this->browsenavigation) >= $this->max): ?>
            <li class="more_link capt_more_link" id="capt_more_link">
                <span></span>
                <span></span>
                <span></span>
                <ul class="capt_submenu" id="capt_submenu">
                    <?php $key = 0; ?>
                    <?php foreach ($this->browsenavigation as $nav): ?>
<?php $key++ ?>
                        <?php if (isset($nav->show_to_guest) && empty($nav->show_to_guest) && !$this->viewer()->getIdentity()): ?>
                            <?php continue; ?>
                        <?php endif; ?>
                        <?php if($displayMenus):?>  
                        <?php if ($key >= $this->max): ?>
                            <li 
                            <?php
                            if ($nav->active): echo "class='active'";
                            endif;
                            ?> >
                                    <?php if ($nav->action): ?>
                                    <a class= "<?php echo $nav->class ?>" href='<?php echo empty($nav->uri) ? $this->url(array('action' => $nav->action), $nav->route, true) : $nav->uri ?>'><?php echo $this->translate($nav->label); ?></a>
                                <?php else : ?>
                                    <a class= "<?php echo $nav->class ?>" href='<?php echo $nav->getHref() ?>'><?php echo $this->translate($nav->label); ?></a>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>
                            
                        
                            <?php else:?>
                             <li 
                            <?php
                            if ($nav->active): echo "class='active'";
                            endif;
                            ?> >
                                    <?php if ($nav->action): ?>
                                    <a class= "<?php echo $nav->class ?>" href='<?php echo empty($nav->uri) ? $this->url(array('action' => $nav->action), $nav->route, true) : $nav->uri ?>'><?php echo $this->translate($nav->label); ?></a>
                                <?php else : ?>
                                    <a class= "<?php echo $nav->class ?>" href='<?php echo $nav->getHref() ?>'><?php echo $this->translate($nav->label); ?></a>
                                <?php endif; ?>
                            </li>
                            <?php endif;?>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endif; ?>
    </ul>
</div>

<style type="text/css">
.layout_captivate_browse_menu_main > h3  {
        display:none;
}
</style>

<?php
if(Engine_API::_()->seaocore()->checkSitemobileMode('fullsite-mode') && (Engine_API::_()->seaocore()->isMobile() || Engine_API::_()->seaocore()->isTabletDevice())) :?>
    <script type="text/javascript">

        document.body.addEvent('click', function(event) {
           var el = $(event.target);

           if(el.getParent('.capt_more_link') || el.hasClass('capt_more_link')) {
               if ($('capt_submenu').style.display == 'none') {
                  $('capt_submenu').style.display = 'block';
                  return;
               }
           }
           $('capt_submenu').style.display = 'none';
    });
    </script>
<?php endif;?>