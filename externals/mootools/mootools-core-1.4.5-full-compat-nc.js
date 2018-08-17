/*
---
MooTools: the javascript framework

web build:
 - http://mootools.net/core/76bf47062d6c1983d66ce47ad66aa0e0

packager build:
 - packager build Core/Core Core/Array Core/String Core/Number Core/Function Core/Object Core/Event Core/Browser Core/Class Core/Class.Extras Core/Slick.Parser Core/Slick.Finder Core/Element Core/Element.Style Core/Element.Event Core/Element.Delegation Core/Element.Dimensions Core/Fx Core/Fx.CSS Core/Fx.Tween Core/Fx.Morph Core/Fx.Transitions Core/Request Core/Request.HTML Core/Request.JSON Core/Cookie Core/JSON Core/DOMReady Core/Swiff

...
*/

/*
---

name: Core

description: The heart of MooTools.

license: MIT-style license.

copyright: Copyright (c) 2006-2012 [Valerio Proietti](http://mad4milk.net/).

authors: The MooTools production team (http://mootools.net/developers/)

inspiration:
  - Class implementation inspired by [Base.js](http://dean.edwards.name/weblog/2006/03/base/) Copyright (c) 2006 Dean Edwards, [GNU Lesser General Public License](http://opensource.org/licenses/lgpl-license.php)
  - Some functionality inspired by [Prototype.js](http://prototypejs.org) Copyright (c) 2005-2007 Sam Stephenson, [MIT License](http://opensource.org/licenses/mit-license.php)

provides: [Core, MooTools, Type, typeOf, instanceOf, Native]

...
*/

const Type = function (name, object) {
  if (name) {
    var lower = name.toLowerCase();
    var typeCheck = function (item) {
      return (typeOf(item) == lower);
    };

    Type['is' + name] = typeCheck;
    if (object != null) {
      object.prototype.$family = (function () {
        return lower;
      });
      $(object.prototype.$family).hide();
      //<1.2compat>
      object.type = typeCheck;
      //</1.2compat>
    }
  }

  if (object == null) return null;

  $.extend(object, this);
  object.$constructor = Type;
  object.prototype.$constructor = object;

  return object;
};
const Class = new Type('Class', function (params) {
  if (instanceOf(params, Function)) params = {initialize: params};

  var newClass = function () {
    reset(this);
    if (newClass.$prototyping) return this;
    this.$caller = null;
    var value = (this.initialize) ? this.initialize.apply(this, arguments) : this;
    this.$caller = this.caller = null;
    return $.extend(value, this).implement(params);
  }.extend(this).implement(params);

  newClass.$constructor = Class;
  newClass.prototype.$constructor = newClass;
  newClass.prototype.parent = parent;

  return newClass;
});

export default this;
