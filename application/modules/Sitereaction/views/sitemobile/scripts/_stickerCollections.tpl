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
<script type="text/javascript">
    var smSticker = {};
    smSticker = {
        stickersLayer: null,
        init: function(layer) {
            this.stickersLayer = layer;
            var iScrollArray = [];
            this.showBox();
            var tabPerWapper = 1;
            if(layer.jqmData('sticker_layer_store')) {
                return;
            }
            layer.find('.iscroll_container').not('.iscroller').each(function() {
            layer.find('.iscroll_wapper').css('width', layer.width() + 'px');
            var scrollKey = $(this).jqmData('rel') == 'tabs' ? 'tabs' : 'board';
             var width = 0, height = 0, margin = 0;
             if (scrollKey === 'board') {
               $(this).find('.iscroll_item').css('width', $(layer).width()+ 'px');
             }
             $(this).find('.iscroll_item').each(function() {
               if ($(this).jqmData('margin'))
                 margin = $(this).jqmData('margin');
               width = width + ($(this).outerWidth() + margin);
               if (height < ($(this).outerHeight()))
                 height = $(this).outerHeight();
             });
             var $self = $(this);
             $self.css('width', width + 'px');
             $self.css('height', height + 'px');
             var itemWidth = $(this).find('.iscroll_item').width();
             if ($(this).find('.iscroll_item').length > 1) {
               var $this = $self.closest('.iscroll_wapper')[0];
               setTimeout(function() {
               var iScroll =  new IScroll($this, {
                   scrollX: true,
                   scrollY: false,
                   preventDefault: false,
                   momentum: false,
                   snap: true,
                   snapSpeed: 400,
                   keyBindings: true
                 });

                 if (scrollKey === 'board') {
                    iScroll.on('scrollEnd', function() {
                        var foundIndex = iScroll.x/iScroll.wrapperWidth;
                        if(foundIndex < 0 ) {
                            foundIndex *= -1;
                        }
                        var tabIndex = Math.round(foundIndex);
                        var liActiveEl = layer.find('.stickers_tab_wapper .iscroll_item')[tabIndex];
                        if (tabPerWapper === 1 || ( tabIndex % tabPerWapper === 0)){
                            iScrollArray['tabs'].scrollToElement(liActiveEl);
                        }
                        layer.find('.stickers_tab_wapper .tab').removeClass('active');
                        $(liActiveEl).find('.tab').addClass('active');
                    });
                   layer.find('.stickers_tab_wapper .tab').on('click', function () {
                     var liActiveEl = $self.find('.' + $(this).jqmData('target'))[0];
                     iScroll.scrollToElement(liActiveEl);
                   });
                 } else {
                   tabPerWapper = Math.round(iScroll.wrapperWidth/itemWidth);

                   iScroll.on('scrollEnd', function() {
                    var tabIndex = Math.round(iScroll.x/itemWidth * -1);
                    var liActiveEl = layer.find('.stickers_icon_wapper .iscroll_item')[tabIndex];
                    iScrollArray['board'].scrollToElement(liActiveEl);
                   });
                 }
                 iScrollArray[scrollKey] = iScroll;

               });
             }
             $(this).addClass('iscroller');
           });
           this.bind();
           layer.jqmData('sticker_layer_store', true);
        },
        showBox: function() {
            this.stickersLayer.removeClass('dnone');
            this.clearSearch();
        },
        bind: function() {
            this.stickersLayer.find('.str_search_text input').on('keyup', this.searchHandler.bind(this));
            this.stickersLayer.find('.str_search_button').on('click', this.clickSearchHandler.bind(this));
            this.stickersLayer.find('.sticker_icons .icon').on('click', this.iconClickHandler.bind(this));
            this.stickersLayer.find('.stickers_search_clear').on('click', this.clearSearch.bind(this));
            this.stickersLayer.closest('.sm-ui-popup').on('click', this.hideBox.bind(this));
        },
        iconClickHandler: function() {
           this.hideBox();
        },
        hideBox: function(event) {
            if (event) {
            var el = $(event.target);
            if (!el.hasClass('stickers_layer_hide') && el.closest('.stickers_layer').length > 0 || el.hasClass('stickers_wapper_target') || el.closest('.stickers_wapper_target').length > 0) {
              // if (el.getParent('.stickers_layer') == this.stickersLayer) {
                return;
                //  }
              }
            }
            this.stickersLayer.addClass('dnone');
            this.stickersLayer.closest('.sm-composer-options').show();
        },
        clearSearch: function() {
            this.stickersLayer.find('.str_search_text input').val('');
            this.search('');
        },
        clickSearchHandler: function(event) {
          var target = $(event.target);
          if(!target.hasClass('str_search_button')){
              target = target.closest('.str_search_button');
          }
          this.stickersLayer.find('.str_search_text input').val(target.jqmData('tag'));
          this.search(target.jqmData('tag'));
        },
        searchHandler: function(event) {
          var target = $(event.target);
          this.search(target.val());
        },
        search:function(text) {
            text = text.trim();
            this.stickersLayer.find('.str_search_not_found').addClass('dnone');
            if (!text) {
              this.stickersLayer.find('.sticker_search_icons').addClass('dnone');
              this.stickersLayer.find('.sticker_search_list').removeClass('dnone');
              this.stickersLayer.find('.stickers_search_clear').addClass('dnone');
              return;
            }
            this.stickersLayer.find('.sticker_search_icons').removeClass('dnone');
            this.stickersLayer.find('.sticker_search_list').addClass('dnone');
            this.stickersLayer.find('.stickers_search_clear').removeClass('dnone');
            this.stickersLayer.find('.sticker_search_icons .icon').addClass('dnone');
            this.stickersLayer.find('.sticker_search_icons .icon').each(function(key, el) {
              var title = $(el).jqmData('title');
              if (title.search(new RegExp(text, "i")) < 0) {
                return;
              }
              $(el).removeClass('dnone');
            });
            if (this.stickersLayer.find('.sticker_search_icons .icon.dnone').length === this.stickersLayer.find('.sticker_search_icons .icon').length) {
              this.stickersLayer.find('.str_search_not_found').removeClass('dnone');
            }
        }
    }
    sm4.core.runonce.add(function() {

    });
</script>
<div class="stickers_layer dnone" id="stickers_layer_dummy">
  <div class="stickers_arrow"></div>
  <div class="stickers_layer_content">
    <div id="stickers-comment-icons" class="stickers_wapper">
      <div id="stickers_icon_wapper" class="iscroll_wapper stickers_icon_wapper">
        <div id="stickers_icon_content" class="scroll_content stickers_icon_content iscroll_container">
          <div class="sticker_icons iscroll_item tab_search_content">
            <div class="stickers_search_wpr">
              <div class="str_search_text">
                <span class="search_icon"></span>
                <label>
                  <input type="text" name="tag_search" placeholder="Search stickers">
                </label>
                <span class="stickers_search_clear dnone">X</span>
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
            <div class="iscroll_item sticker_icons tab_<?php echo $collection->getIdentity() ?>" id="tab_<?php echo $collection->getIdentity() ?>">
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
      <!--  TOP TAB-->
      <div class="stickers_tab_wapper">
        <div class="st_tabs_icons iscroll_wapper" style="">
          <div class="scroll_tabs iscroll_container" data-rel="tabs">
              <div class='tab_wp iscroll_item'>
                <a class="tab active tab_search ui-link-inherit ui-link" data-target="tab_search_content" >
                  <i class="ui-icon"></i>
                </a>
              </div>
              <?php $i = 0; $rowCount = $this->hasStore ? 3 : 4 ?>
              <?php
              foreach ($this->collections as $collection):
                ?>
                <div class="tab_wp iscroll_item">
                  <a class="tab" data-target="tab_<?php echo $collection->getIdentity() ?>" title="<?php echo $collection->getTitle() ?>">
                    <?php echo $this->itemPhoto($collection); ?>
                  </a>
                </div>
              <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <a class="stickers_layer_hide dnone"> X </a>
</div>
