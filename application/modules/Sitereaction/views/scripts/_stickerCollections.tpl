<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: install.php 6590 2016-07-07 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class="stickers_layer dnone" id="stickers_layer_dummy">
  <div class="stickers_arrow"></div>
  <div class="stickers_layer_content">
    <div id="stickers-comment-icons" class="stickers_wapper">
      <!--  TOP TAB-->
      <div class="stickers_tab_wapper">
        <?php if ($this->hasStore): ?>
        <a title="Sticker Store" class="store seao_smoothbox" href="<?php echo $this->url(array('module' => 'sitereaction','controller' => 'store','action' => 'list'), 'default', true)?>">
          <i></i>
        </a>
        <?php endif; ?>
        <a class="prev dnone" title="Prev"><i></i></a>
        <a class="next" title="Next"><i></i></a>
        <div class="st_tabs_icons" style="">
          <div class="scroll_tabs">
            <div class="inline_row">
              <div class='tab_wp'>
                <a class="tab active tab_search" data-target="tab_search_content" >
                  <i></i>
                </a>
              </div>
              <?php $i = 0; $rowCount = $this->hasStore ? 4 : 5 ?>
              <?php
              foreach ($this->collections as $collection):
                ?>
                <?php if ($i > 0 && $i % $rowCount === 0): ?>
                </div><div class="inline_row">
                <?php endif; ?>
                <?php $i++; ?>
                <div class="tab_wp">
                  <a class="tab" data-target="tab_<?php echo $collection->getIdentity() ?>" title="<?php echo $collection->getTitle() ?>">
                    <?php echo $this->itemPhoto($collection); ?>
                  </a>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
      <div id="stickers_icon_wapper" class="stickers_icon_wapper">
        <div id="stickers_icon_content" class="scroll_content stickers_icon_content">
          <div class="sticker_icons tab_search_content">
            <div class="stickers_search_wpr">
              <div class="str_search_text">
                <span class="search_icon"></span>
                <label>
                  <input type="text" name="tag_search" placeholder="Search stickers">
                </label>
                <span class="stickers_search_clear dnone"></span>
              </div>
              <div class="sticker_search_list">
                <?php foreach ($this->searchList as $search): ?>
                <button type="button" class="str_search_button" style="background: <?php echo $search->background_color ?>" data-tag="<?php echo $search->keyword ?>">
                    <?php echo $this->itemPhoto($search, '', '', array('align' => 'center'))?>
                    <?php echo $search->title ?>                    
                  </button>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="sticker_search_icons">
              <?php
              foreach ($this->collections as $collection):
                ?>
                <?php
                foreach ($collection->getStickers() as $sticker):
                  ?>
              <span class="icon dnone" data-guid="<?php echo $sticker->getGuid() ?>" data-title="<?php echo strtolower($sticker->getTitle()) ?>" data-img="<?php echo $sticker->getPhotoUrl() ?>">
                    <i style="background-image: url('<?php echo $sticker->getPhotoUrl() ?>')"> </i>
                  </span>
                <?php endforeach; ?>
              <?php endforeach; ?>
              <div class="str_search_not_found dnone">
                <i></i>
                <p>No Stickers to Show</p>
              </div>
            </div>
          </div>
          <?php foreach ($this->collections as $collection): ?>
            <div class="sticker_icons dnone tab_<?php echo $collection->getIdentity() ?>" id="tab_<?php echo $collection->getIdentity() ?>">
              <?php
              foreach ($collection->getStickers() as $sticker):
                ?>
                <div class="icon" data-guid="<?php echo $sticker->getGuid() ?>" data-img="<?php echo $sticker->getPhotoUrl() ?>">
                  <i style="background-image: url('<?php echo $sticker->getPhotoUrl() ?>')"> </i>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <a class="stickers_layer_hide dnone"> X </a>
</div>
