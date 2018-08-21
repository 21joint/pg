
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitereaction
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: composer_nested_comment_activity_sticker.js 2016-07-07 00:00:00 SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

(function() { // START NAMESPACE
  var $ = 'id' in document ? document.id : window.$;
  ComposerNestedComment.Plugin.Sticker = new Class({
    Extends: ComposerNestedComment.Plugin.Interface,
    name: 'sticker',
    activeIndex: 0,
    shouldAutoSubmit : false,
    clickEvent: null,
    scroller: null,
    stickersLayer: null,
    options: {
      title: 'Post a sticker',
      lang: {},
    },
    initialize: function(options) {
      this.elements = new Hash(this.elements);
      this.params = new Hash(this.params);
      this.parent(options);
    },
    attach: function() {
      this.parent();
      this.makeActivator();
      this.elements.activator.addClass('stickers_wapper_target');
      this.stickersLayer = $('stickers_layer_dummy').clone().inject(this.elements.activator, 'after');
      this.stickersLayer.addClass('stickers_layer_content_comment');
      this.elements.activator.addEvent('click', this.showBox.bind(this));
      this.elements.activator.removeEvent('click', this.activate);
      this.elements.activator.addEvent('stickerSelected', this.doProcessResponse.bind(this));
      return this;
    },
    refresh: function() {
      this.stickersLayer.set('html', $('stickers_layer_dummy').get('html'));
      this.bind();
    },
    detach: function() {
      this.parent();
      return this;
    },
    activate: function(event) {
      if (event) {
        if ($(event.target).hasClass('stickers_wapper_target') || $(event.target).getParent('.stickers_wapper_target')) {
          return;
        }
      }
      if (this.active)
        return;
      var activator = this.elements.activator;
      this.parent();
      this.makeMenu();
      this.makeBody();
      this.elements.activator = activator;
      this.elements.activator.hide();
      // Generate form
      //this.elements.body

    },
    deactivate: function() {
      if (!this.active)
        return;
      var activator = this.elements.activator;
      this.parent();
      this.elements.activator = activator;
      this.elements.activator.setStyle('display', '');
    },
    bind: function() {
      this.stickersLayer.getElements('.tab').addEvent('click', this.tabClickHandler.bind(this));

      this.stickersLayer.getElements('.prev,.next').addEvent('click', this.tabsShow.bind(this));
      this.stickersLayer.getElements('.stickers_layer_hide').addEvent('click', this.hideBox.bind(this));
      this.stickersLayer.getElements('.str_search_text input').addEvent('keyup', this.searchHandler.bind(this));
      this.stickersLayer.getElements('.str_search_button').addEvent('click', this.clickSearchHandler.bind(this));
      this.stickersLayer.getElements('.sticker_icons .icon').addEvent('click', this.iconClickHandler.bind(this));
      this.stickersLayer.getElements('.stickers_search_clear').addEvent('click', this.clearSearch.bind(this));
      SmoothboxSEAO.bind(this.stickersLayer);
    },
    clearSearch: function() {
      this.stickersLayer.getElements('.str_search_text input').set('value', '');
      this.search('');
    },
    iconClickHandler: function(event) {
      var $icon = $(event.target);
      if (!$icon.hasClass('icon')) {
        $icon = $icon.getParent('.icon');
      }
      this.shouldAutoSubmit = true;
      this.clickEvent = event;
      this.elements.activator.fireEvent('stickerSelected', $icon);
      this.hideBox();
    },
    clickSearchHandler: function(event) {
      var target = $(event.target);
      if(!target.hasClass('str_search_button')){
          target = target.getParent('.str_search_button');
      }
      this.stickersLayer.getElements('.str_search_text input').set('value', target.get('data-tag'));
      this.search(target.get('data-tag'));
    },
    searchHandler: function(event) {
      var target = $(event.target);
      this.search(target.get('value'));
    },
    search: function(text) {
      text = text.trim(); // .split(" ");
      this.stickersLayer.getElements('.str_search_not_found').addClass('dnone');
      if (!text) {
        this.stickersLayer.getElements('.sticker_search_icons').addClass('dnone');
        this.stickersLayer.getElements('.sticker_search_list').removeClass('dnone');
        this.stickersLayer.getElements('.stickers_search_clear').addClass('dnone');
        return;
      }
      this.stickersLayer.getElements('.sticker_search_icons').removeClass('dnone');
      this.stickersLayer.getElements('.stickers_search_clear').removeClass('dnone');
      this.stickersLayer.getElements('.sticker_search_list').addClass('dnone');
      this.stickersLayer.getElements('.sticker_search_icons .icon').addClass('dnone');
      this.stickersLayer.getElements('.sticker_search_icons .icon').each(function(el) {
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
    tabsShow: function(event) {
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
    updateBoard: function(index) {
      var left = 0;
      var count = this.stickersLayer.getElements('.stickers_tab_wapper .inline_row').length;
      this.stickersLayer.getElements('.stickers_tab_wapper .inline_row').each(function(el, key) {
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
    hideBox: function(event) {
      if (event) {
        var el = $(event.target);
        if (!el.hasClass('stickers_layer_hide') && el.getParent('.stickers_layer') || el.hasClass('stickers_wapper_target') || el.getParent('.stickers_wapper_target')) {
          return;
        }
      }
      this.stickersLayer.addClass('dnone');
      this.scroller = null;
      $(document).removeEvent('click', this.hideBox);
    },
    shouldShowOnBottom: function() {
      return $('global_footer').getPosition().y - (this.elements.activator.getPosition().y + 450) > 0;
    },
    showBox: function(event) {
      this.refresh();
      var el = $(event.target);
      if (!el.hasClass('stickers_wapper_target')) {
        el = el.getParent('.stickers_wapper_target');
      }
      $(document).addEvent('click', this.hideBox.bind(this));
      // dnone
      var lEl = this.stickersLayer;
      if (this.shouldShowOnBottom()) {
        this.stickersLayer.removeClass('stickers_layer_bottom');
      } else {
        this.stickersLayer.addClass('stickers_layer_bottom');
      }
      lEl.removeClass('dnone');

      if (!this.scroller) {
        this.scroller = new SEAOMooVerticalScroll(this.stickersLayer.getElement('.stickers_icon_wapper'), this.stickersLayer.getElement('.stickers_icon_content'), {});
      }
      this.stickersLayer.getElement('.str_search_text input').set('value', '');
      this.search('');
      this.updateBoard(0);
    },
    tabClickHandler: function(event) {
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
    doEditProcess: function () {
      if( this.options.attachment &&  this.options.attachment.type === 'sitereaction_sticker') {
        this.refresh();
        var iconEl = this.stickersLayer.getElement('[data-guid="'+ this.options.attachment.guid +'"]');
        this.doProcessResponse(iconEl);
      }
    },
    doProcessResponse: function(iconEl) {
      // Success
      this.activate();
      this.params.set('stikcer_guid', iconEl.get('data-guid'));
      if (this.shouldAutoSubmit) {
        this.makeFormInputs();
        this.getComposer().getForm().fireEvent('submit',  new Event(this.clickEvent));
        if ($('nested-compose-sticker-menu'))
            $('nested-compose-sticker-menu').style.display = 'none';
        return;
      }
      this.elements.preview = Asset.image(iconEl.get('data-img'), {
        'id': 'compose-sticker-preview-image',
        'class': 'compose-preview-image',
        'onload': this.doImageLoaded.bind(this)
      });
      if ($('nested-compose-sticker-menu'))
        $('nested-compose-sticker-menu').style.display = 'block';
    },
    doImageLoaded: function() {
      this.elements.preview.erase('width');
      this.elements.preview.erase('height');
      this.elements.preview.inject(this.elements.body);
      this.makeFormInputs();
    },
    makeFormInputs: function() {
      this.ready();
      this.parent({
        'stikcer_guid': this.params.stikcer_guid,
      });
    }

  });
})(); // END NAMESPACE
