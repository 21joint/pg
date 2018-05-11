
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: composer_photo.js 2016-07-07 00:00:00 SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

(function () { // START NAMESPACE
  var $ = 'id' in document ? document.id : window.$;

  Composer.Plugin.Sticker = new Class({
    Extends: Composer.Plugin.Interface,
    name: 'sticker',
    activeIndex: 0,
    shouldAutoSubmit: false,
    clickEvent: null,
    activator: null,
    scroller: null,
    loaded: false,
    stickersLayer: null,
    options: {
      title: 'Post a Sticker',
      lang: {},
      attachment: {},
    },
    initialize: function (options) {
      this.elements = new Hash(this.elements);
      this.params = new Hash(this.params);
      this.parent(options);
    },
    attach: function () {
      this.parent();
      this.loadPreReq();
      this.makeActivator();
      this.doEditProcess();
      return this;
    },
    loadPreReq: function () {
      var self = this;
      var request = new Request.HTML({
        'url': en4.core.baseUrl + 'sitereaction/index/load-sticker',
        method: 'get',
        'data': {
          'format': 'html',
        },
        onSuccess: function (responseTree, responseElements, responseHTML, responseJavaScript) {
          new Element('div', {
            html: responseHTML
          }).inject(document.body);
          self.stickersLayer = $('stickers_layer_dummy').clone().inject(document.body);
          self.stickersLayer.addClass('stickers_layer_activtiy_comment');
          self.loaded = true;
        }
      });
      request.send();
    },
    makeActivator: function () {
      if (!this.elements.activator) {
        this.parent();
        this.elements.activator.addClass('stickers_wapper_target aaf_click_ignore');
        this.elements.activator.addEvent('click', this.showBox.bind(this));
        this.elements.activator.removeEvent('click', this.activate);
        this.elements.activator.addEvent('stickerSelected', this.doProcessResponse.bind(this));
        this.activator = this.elements.activator;
      }
    },
    refresh: function () {
      this.stickersLayer.set('html', $('stickers_layer_dummy').get('html'));
      this.bind();
    },
    deactivate: function () {
      this.parent();
      this.getComposer().elements.container.getParent().removeClass('sticker_added');
      return this;
    },
    detach: function () {
      this.parent();
      return this;
    },
    activate: function (event) {
      if (event) {
        if ($(event.target).hasClass('stickers_wapper_target') || $(event.target).getParent('.stickers_wapper_target')) {
          return;
        }
      }
      if (this.active)
        return;
      this.parent();
      this.makeMenu();
      this.makeTopBody();
    },
    makeMenu: function () {
      if (!this.elements.menu) {
        var tray = this.getComposer().getTopTray();
        this.elements.menu = new Element('div', {
          'id': 'compose-' + this.getName() + '-menu',
          'class': 'nested-compose-menu'
        }).inject(tray);

        this.elements.menuClose = new Element('a', {
          'href': 'javascript:void(0);',
          'class': 'aaf-composer-cancel-menu',
          'html': '',
          'events': {
            'click': function (e) {
              e.stop();
              this.getComposer().deactivate();
            }.bind(this)
          }
        }).inject(this.elements.menu);

      }
    },
    bind: function () {
      this.stickersLayer.addEvent('click', function (event) {
        event.stopPropagation();
        event.preventDefault();
      });
      this.stickersLayer.getElements('.tab').addEvent('click', this.tabClickHandler.bind(this));
      this.stickersLayer.getElements('.prev,.next').addEvent('click', this.tabsShow.bind(this));
      this.stickersLayer.getElements('.stickers_layer_hide').addEvent('click', this.hideBox.bind(this));
      this.stickersLayer.getElements('.str_search_text input').addEvent('keyup', this.searchHandler.bind(this));
      this.stickersLayer.getElements('.str_search_button').addEvent('click', this.clickSearchHandler.bind(this));
      this.stickersLayer.getElements('.sticker_icons .icon').addEvent('click', this.iconClickHandler.bind(this));
      this.stickersLayer.getElements('.stickers_search_clear').addEvent('click', this.clearSearch.bind(this));
      SmoothboxSEAO.bind(this.stickersLayer);
    },
    clearSearch: function () {
      this.stickersLayer.getElements('.str_search_text input').set('value', '');
      this.search('');
    },
    iconClickHandler: function (event) {
      var $icon = $(event.target);
      if (!$icon.hasClass('icon')) {
        $icon = $icon.getParent('.icon');
      }
      this.clickEvent = event;
      this.shouldAutoSubmit = true;
      this.activator.fireEvent('stickerSelected', $icon);
      this.hideBox();
    },
    clickSearchHandler: function (event) {
      var target = $(event.target);
      if (!target.hasClass('str_search_button')) {
        target = target.getParent('.str_search_button');
      }
      this.stickersLayer.getElements('.str_search_text input').set('value', target.get('data-tag'));
      this.search(target.get('data-tag'));
    },
    searchHandler: function (event) {
      var target = $(event.target);
      this.search(target.get('value'));
    },
    search: function (text) {
      text = text.trim(); // .split(" ");
      this.stickersLayer.getElements('.str_search_not_found').addClass('dnone');
      if (!text) {
        this.stickersLayer.getElements('.sticker_search_icons').addClass('dnone');
        this.stickersLayer.getElements('.sticker_search_list').removeClass('dnone');
        this.stickersLayer.getElements('.stickers_search_clear').addClass('dnone');
        return;
      }
      this.stickersLayer.getElements('.sticker_search_icons').removeClass('dnone');
      this.stickersLayer.getElements('.sticker_search_list').addClass('dnone');
      this.stickersLayer.getElements('.stickers_search_clear').removeClass('dnone');
      this.stickersLayer.getElements('.sticker_search_icons .icon').addClass('dnone');
      this.stickersLayer.getElements('.sticker_search_icons .icon').each(function (el) {
        var title = el.get('data-title');
        if (title.search(new RegExp(text, "i")) < 0) {
          return;
        }
        el.removeClass('dnone');
      });
      if (this.stickersLayer.getElements('.sticker_search_icons .icon.dnone').length === this.stickersLayer.getElements('.sticker_search_icons .icon').length) {
        this.stickersLayer.getElements('.str_search_not_found').removeClass('dnone');
      }
    },
    tabsShow: function (event) {
      var target = $(event.target);
      if (target.get('tag') != 'a') {
        target = target.getParent('a');
      }
      var count = this.stickersLayer.getElements('.stickers_tab_wapper .inline_row').length;
      var targetIndex = (target.hasClass('prev')) ? this.activeIndex - 1 : this.activeIndex + 1;
      targetIndex = targetIndex < 0 ? 0 : targetIndex;
      targetIndex = targetIndex < count ? targetIndex : count - 1;
      this.updateBoard(targetIndex);
    },
    updateBoard: function (index) {
      var left = 0;
      var count = this.stickersLayer.getElements('.stickers_tab_wapper .inline_row').length;
      this.stickersLayer.getElements('.stickers_tab_wapper .inline_row').each(function (el, key) {
        if (key < index) {
          left += el.offsetWidth;
        }
      }.bind(this));
      this.stickersLayer.getElements('.stickers_tab_wapper .inline_row')[index].addClass('row_active');
      this.stickersLayer.getElements('.stickers_tab_wapper .inline_row')[index].getElement('.tab').click();
      if (index === 0) {
        this.stickersLayer.getElement('.prev').addClass('dnone');
      } else {
        this.stickersLayer.getElement('.prev').removeClass('dnone');
      }
      if (index === count - 1) {
        this.stickersLayer.getElement('.next').addClass('dnone');
      } else {
        this.stickersLayer.getElement('.next').removeClass('dnone');
      }

      this.stickersLayer.getElement('.scroll_tabs').setStyles({
        'left': -left
      });
      this.activeIndex = index;
    },
    hideBox: function (event) {
      if (event) {
        var el = $(event.target);
        if (!el.hasClass('stickers_layer_hide') && el.getParent('.stickers_layer') || el.hasClass('stickers_wapper_target') || el.getParent('.stickers_wapper_target')) {
          // if (el.getParent('.stickers_layer') == this.stickersLayer) {
          return;
          //  }
        }
      }
      this.stickersLayer.addClass('dnone');
      this.scroller = null;
      $(document).removeEvent('click', this.hideBox);
    },
    shouldShowOnBottom: function () {
      return $('global_footer').getPosition().y - (this.activator.getPosition().y + 450) > 0;
    },
    showBox: function (event) {
      if (!this.loaded) {
        return;
      }
      this.refresh();
      if (this.getComposer().options.template == 'activator_icon') {
        this.stickersLayer.setStyles({
          left: (this.activator.getPosition().x - 200) + 'px',
          top: (this.activator.getPosition().y + 20) + 'px'
        });
        this.stickersLayer.getElement('.stickers_arrow').setStyles({
          left: '200px'
        });
      } else {
        (function () {
          this.stickersLayer.setStyles({
            left: (this.activator.getPosition().x) + 'px',
            top: (this.activator.getPosition().y + 35) + 'px'
          });
        }).bind(this).delay(10);
      }
      var el = $(event.target);
      if (!el.hasClass('stickers_wapper_target')) {
        el = el.getParent('.stickers_wapper_target');
      }
      $(document).addEvent('click', this.hideBox.bind(this));
      if (this.shouldShowOnBottom()) {
        this.stickersLayer.removeClass('stickers_layer_bottom');
      } else {
        this.stickersLayer.addClass('stickers_layer_bottom');
      }
      // dnone
      this.stickersLayer.removeClass('dnone');
      if (!this.scroller) {
        this.scroller = new SEAOMooVerticalScroll(this.stickersLayer.getElement('.stickers_icon_wapper'), this.stickersLayer.getElement('.stickers_icon_content'), {});
      }
//      this.stickersLayer.getElement('.str_search_text input').set('value', '');
//      this.search('');
      this.updateBoard(0);
    },
    tabClickHandler: function (event) {
      event.stop();
      var el = $(event.target);
      if (!el.hasClass('tab')) {
        el = el.getParent('.tab');
      }
      el.getParent('.stickers_tab_wapper').getElements('.tab').removeClass('active');
      this.stickersLayer.getElements('.sticker_icons').addClass('dnone');
      this.stickersLayer.getElement('.' + el.get('data-target')).removeClass('dnone');
      el.addClass('active');
      this.stickersLayer.getElement('.stickers_icon_wapper').setStyle('height', '320px');
      this.scroller.update();
    },
    doProcessResponse: function (iconEl) {
      // Success
      this.activate();
      this.getComposer().getTray().setStyle('display', 'none');
      this.getComposer().elements.container.getParent().addClass('sticker_added');
      this.params.set('stikcer_guid', iconEl.get('data-guid'));
      this.params.set('src', iconEl.get('data-img'));
      this.elements.body.empty();
      this.elements.stickerBody = new Element('div', {
        class: 'sticker-content-wapper'
      }).inject(this.elements.body);
      this.elements.menuClose.inject(this.elements.stickerBody);
      this.elements.preview = Asset.image(iconEl.get('data-img'), {
        'id': 'compose-activity-sticker-preview-image',
        'class': 'compose-preview-image',
        'onload': this.doImageLoaded.bind(this)
      });
      if (this.activator && this.activator.getParent('.more-menu-compose-activator')) {
        $('adv_post_container_icons').removeClass('composer_activator_expand_more_options');
        $('adv_post_container_icons').addClass('composer_activator_collapse_more_options');
      }
    },
    doImageLoaded: function () {
      this.elements.preview.erase('width');
      this.elements.preview.erase('height');
      this.elements.preview.inject(this.elements.stickerBody);
      this.getComposer().autogrow.handle();
      this.makeFormInputs();
    },
    makeFormInputs: function () {
      this.ready();
      if (document.getElementsByName("attachment[stikcer_guid]")[0]) {
        document.getElementsByName("attachment[stikcer_guid]")[0].value = this.params.stikcer_guid;
        document.getElementsByName("attachment[src]")[0].value = this.params.src;
      } else {
        this.parent({
          'stikcer_guid': this.params.stikcer_guid,
          'src': this.params.src
        });
      }
    }
  });
})(); // END NAMESPACE
