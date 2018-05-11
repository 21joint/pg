/* $Id: composer_hashtag.js 2016-01-25 00:00:00Z SocialEngineAddOns Copyright 2016-2017 BigStep Technologies Pvt.Ltd. $ */

Composer.Plugin.Sitehashtag = new Class({
  Extends: Composer.Plugin.Interface,
  name: 'hashtag',
  hashRegExp: /\B(#[^\s[!\"\#$%&'()*+,\-.\/\\:;<=>?@\[\]\^`{|}~]+)/g,
  options: {
    'enabled': false
  },
  initialize: function(options) {
    this.params = new Hash(this.params);
    this.parent(options);
  },
  attach: function() {
    if (!this.options.enabled)
      return;
    this.parent();
    this.getComposer().addEvent('editorHighlighter', this.highlight.bind(this));
    return this;
  },
  detach: function() {
    if (!this.options.enabled)
      return;
    this.parent();
    this.getComposer().removeEvent('editorHighlighter', this.highlight.bind(this));
    return this;
  },
  highlight: function() {
    if (typeof activity_type != 'undefined' && activity_type != 1)
      return;
    var content = this.getComposer().highlighterText;
    content = content.replace(/\<span\>\<\/span\>/ig, '');
    var matches = content.match(this.hashRegExp);
    if (!matches) {
      return;
    }
    var hashSplit = content.split(this.hashRegExp);
    var newcontent = '';
    var i = 0;
    var noIgnore = true;
    for (i; i < hashSplit.length; i++) {
      var subset = hashSplit[i] || '';
      if (subset.indexOf('#') === 0 && noIgnore) {
        newcontent += this.getHashTagString(subset);
        noIgnore = false;
        continue;
      }
      noIgnore = true;
      newcontent += subset;
    }

    this.getComposer().highlighterText = newcontent;
  },
  getHashTagString: function(text) {
    return this.getComposer().getHighlightString(text);
  }
});
