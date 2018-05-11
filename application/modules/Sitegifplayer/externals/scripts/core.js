/* $Id: core.js 2017-05-15 00:00:00Z SocialEngineAddOns Copyright 2017-2018 BigStep Technologies Pvt.Ltd. $ */
var GifPlayerScops = Array();
var GifPlayer = new Class({
  Implements: [Events, Options],
  previewElement: null,
  options: {
    wait: false,
  },
  duration: 60000,
  durationLoaded: false,
  activeTime: 0,
  animationLoaded: false,
  isImgTag: true,
  gifSrc: '',
  src: '',
  scrollPlay: false,
  isActive: false,
  initialize: function (element, options) {
    this.setOptions(options);
    this.previewElement = element;
    this.isImgTag = this.previewElement.get('tag') === 'img';
    this.options = options;
  },
  activate: function () {
    var self = this;
    if (this.previewElement.offsetWidth === 0) {
      setTimeout(function () {
        self.activate();
      }, 100);
    } else {
      self.gifSrc = self.previewElement.getAttribute('data-gif-src');
      self.src = self.previewElement.getAttribute('data-src');
      self.duration = self.previewElement.getAttribute('data-duration');
      self.scrollPlay = self.previewElement.getAttribute('data-gif-scroll-play') == 1;
      self.wrap();
      self.addControl();
      self.addEvents();
      if (self.scrollPlay) {
        self.scrollHandler();
      }
    }
  },
  wrap: function () {
    this.previewElement.getParent().addClass('sitegif_player_wapper');
    this.previewElement.setStyle('cursor', 'pointer');
  },
  addControl: function () {
    this.playElement = new Element('ins', {
      html: 'GIF',
      class: 'sitegifplayer_play',
    }).inject(this.previewElement, 'after');
    this.stopElement = new Element('ins', {
      html: 'GIF',
      class: 'sitegifplayer_stop',
    }).inject(this.previewElement, 'after');

  },
  addEvents: function () {
    if (this.previewElement.getAttribute('data-action') === 'click') {
      this.previewElement.addClass('sitegif_player_button_action');
      this.playElement.addEvent('click', function (e) {
        this.loadAnimation();
        e.preventDefault();
        e.stopPropagation();
      }.bind(this));
      this.stopElement.addEvent('click', function (e) {
        this.stopGif();
        e.preventDefault();
        e.stopPropagation();
      }.bind(this));
    } else {
      this.previewElement.addEvent('mouseover', function (e) {
        this.loadAnimation();
        e.preventDefault();
        e.stopPropagation();
      }.bind(this));
    }
    if (this.scrollPlay) {
      window.addEvent('scroll', this.scrollHandler.bind(this));
    }
  },
  loadAnimation: function () {
    if (this.isActive) {
      return;
    }
    this.isActive = true;
    this.playElement.hide();
    this.stopElement.addClass('sitegifplayer_spinner');
    if (!this.animationLoaded) {
      this.loadGif();
    } else {
      this.showGif();
    }
  },
  downloadFile: function (url, success) {
    var xhr = new XMLHttpRequest();
    if (url.indexOf('http://') === 0 || url.indexOf('https://') === 0)
    {
      url = en4.core.baseUrl + 'gif.php?u=' + encodeURIComponent(url);
    }
    xhr.open('GET', url, true);
    xhr.responseType = "arraybuffer";
    xhr.onreadystatechange = function () {
      if (xhr.readyState == 4) {
        success(xhr.response);
      }
    };
    xhr.send(null);
  },
  loadGif: function () {
    var img = new Image();
    img.onload = function () {
      this.animationLoaded = true;
      this.showGif();
    }.bind(this);
    img.src = this.gifSrc;
    if (this.durationLoaded) {
      return;
    }
    this.activeTime = (new Date()).getTime();
    this.downloadFile(this.gifSrc, function (data) {
      var d = new Uint8Array(data);
      var bin = ''
      var duration = 0
      for (var i = 0; i < d.length; i++) {
        //bin += String.fromCharCode( d[i] )
        bin += String.fromCharCode(d[i])
        // Find a Graphic Control Extension hex(21F904__ ____ __00)
        if (d[i] == 0x21
                && d[i + 1] == 0xF9
                && d[i + 2] == 0x04
                && d[i + 7] == 0x00) {
          // Swap 5th and 6th bytes to get the delay per frame
          var delay = (d[i + 5] << 8) | (d[i + 4] & 0xFF)

          // Should be aware browsers have a minimum frame delay 
          // e.g. 6ms for IE, 2ms modern browsers (50fps)
          duration += delay < 2 ? 10 : delay
        }
      }
      duration = (duration / 100.0) * 1000;
      var temp = this.duration;
      this.duration = duration > this.duration ? duration : this.duration;
      if (((new Date()).getTime() - this.activeTime) < temp) {
        setTimeout(this.stopGif.bind(this), temp - ((new Date()).getTime() - this.activeTime));
      }
    }.bind(this));
  },
  showGif: function () {
    if (!this.isActive) {
      return;
    }
    this.stopElement.removeClass('sitegifplayer_spinner');
    this.setSrc(this.gifSrc);
    this.activeTime = (new Date()).getTime();
    if (this.durationLoaded) {
      setTimeout(this.stopGif.bind(this), this.duration);
    }
    this.previewElement.addClass('sitegif_player_active');
  },
  stopGif: function () {
    this.setSrc(this.src);
    this.stopElement.removeClass('sitegifplayer_spinner');
    this.previewElement.removeClass('sitegif_player_active');
    this.playElement.show();
    this.isActive = false;
  },
  setSrc: function (src) {
    if (this.isImgTag) {
      this.previewElement.src = src;
    } else {
      this.previewElement.setStyle('backgroundImage', 'url("' + src + '")')
    }
  },
  scrollHandler: function () {
    if (this.isElementInView()) {
      this.loadAnimation();
    } else if (this.isActive) {
      this.stopGif();
    }
  },
  isElementInView: function () {
    var pageTop = window.getScroll().y;
    var pageBottom = pageTop + window.getSize().y - 100;
    var coordinates = this.previewElement.getCoordinates();
    var elementTop = coordinates.top;
    var elementBottom = coordinates.bottom;
    var fullyInView = true;
    if (fullyInView === true) {
      return ((pageTop < elementTop) && (pageBottom > elementBottom));
    } else {
      return ((elementTop <= pageBottom) && (elementBottom >= pageTop));
    }
  }
});

var gifFunction = function () {
  $$('.sitegif_player_init').each(function (el) {
    GifPlayerScops[el] = new GifPlayer(el, {});
    GifPlayerScops[el].activate();
    el.removeClass('sitegif_player_init');
  });

  setTimeout(function () {
    gifFunction();
  }, 500);

};
en4.core.runonce.add(gifFunction);
