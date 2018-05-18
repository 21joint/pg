
/* $Id: composer_photo.js 9572 2011-12-27 23:41:06Z john $ */



(function () { // START NAMESPACE
  var $ = 'id' in document ? document.id : window.$;



  ComposerCheckin.Plugin.STCheckin = new Class({

    Extends: Composer.Plugin.Interface,

    name: 'checkin',

    options: {
      title: en4.core.language.translate('Share Location'),
      lang: {}
    },
    location: '',
    call_empty_suggest: false,
    add_location: false,
    navigator_location_shared: false,
    initialize: function (options) {
      this.elements = new Hash(this.elements);
      this.params = new Hash(this.params);
      this.parent(options);
    },
    makeActivator: function () {
      if (!this.elements.activator) {
        this.elements.activator = new Element('span', {
          'id': 'compose-' + this.getName() + '-activator',
          'class': 'adv-post-checkin compose-activator',
          'href': 'javascript:void(0);',
          'html': '<span>' + this._lang(this.options.title) + '</span>',
          'events': {
            'click': function () {
              this.activate();
              this.checkinToggle();
            }.bind(this)
          }
        }).inject(this.getComposer().getActivatorContent().getElement(".aaf_activaor_end"), "before");
        create_tooltip(this).inject(this.elements.activator);
        this.setActivatorPositions();
        this.elements.composerTrydisplay = new Element('span', {
          'id': 'compose-' + this.getName() + '-composer-display',
          'class': 'adv_post_container_tagged_cont',
          'events': {
            'click': this.checkinToggle.bind(this)
          }
        }).inject($('composer_preview_display_tray'), 'bottom');
      }
    },
    attach: function () {
      if (!this.elements.activator) {
        var composer = this.getComposer();
        this.makeActivator();
        var composer_tray = composer.getTray();
        this.elements.container = new Element('div', {
          'id': 'compose-' + this.getName() + '-container-checkin',
          'class': 'adv_post_container_checkin dnone',
          'title': this._lang('Where are you?'),
          'style': {
            dispaly: 'none'
          }
        });
        this.elements.divAt = new Element('div', {
          'class': 'compose-' + this.getName() + '-at',
          'html': en4.core.language.translate('At')
        }).inject(this.elements.container, 'top');
        this.elements.contentdisplay = new Element('div', {
          'id': 'compose-' + this.getName() + '-container-display',
          'class': '',
          'title': this._lang('Where are you?')
        }).inject(this.elements.container);
        this.elements.input = new Element('input', {
          'type': 'text',
          'id': 'compose-' + this.getName(),
          'name': 'compose-' + this.getName(),
          'class': 'compose-textarea'
        }).inject(this.elements.container);
        var self = this;
        this.elements.input.addEventListener('input', function () {
          if (self.elements.input.value.length == 0) {
            self.removeLocation();
          }
        });
        this.elements.container.inject(composer_tray, "before");


        this.elements.overText = new ComposerCheckin.OverText(this.elements.input, {
          textOverride: this._lang('Where are you?'),
          'element': 'label',
          'isPlainText': true,
          'positionOptions': {
            position: (en4.orientation == 'rtl' ? 'upperRight' : 'upperLeft'),
            edge: (en4.orientation == 'rtl' ? 'upperRight' : 'upperLeft'),
            offset: {
              x: (en4.orientation == 'rtl' ? -4 : 4),
              y: 2
            }
          }
        });

        // Submit
        composer.addEvent('editorSubmit', this.submit.bind(this));
        this.suggest = this.getSuggest();
        // After Submit
        composer.addEvent('editorSubmitAfter', this.submitAfter.bind(this));
        composer.addEvent('editorReset', this.resetcontent.bind(this));
      }

      return this;
    },

    detach: function () {
      //   this.parent();
      return this;
    },
    activate: function () {
    },
    deactivate: function () {
      if (this.elements.checkinCountContainer)
        this.elements.checkinCountContainer.destroy();
      this.elements.checkinCountContainer = null;
      this.resetMdashMdot(this.name);
    },

    poll: function () {

    },
    resetcontent: function () {
      if (this.elements.container && this.elements.container.hasClass("dblock")) {
        this.elements.container.removeClass("dblock").addClass("dnone");
      }
      if (this.add_location)
        this.removeLocation();
    },
    checkinToggle: function () {
      this.elements.container.toggleClass('dnone');
      this.elements.container.toggleClass('dblock');

      if (this.elements.container.hasClass("dblock")) {
        this.elements.input.focus();
        this.add_location = false;
        if (!this.navigator_location_shared) {
          this.getCurrentLocation();
        }
      } else if (this.elements.composerTrydisplay.getFirst('a')) {
        this.add_location = true;
      }

    },
    getCurrentLocation: function () {
      var locationTimeLimit = 12000;

      var self = this;
      var locationTimeout = window.setTimeout(function () {
        try {
          self.navigator_location_shared = false;
          if (self.watchID)
            navigator.geolocation.clearWatch(self.watchID);
        } catch (e) {
        }

      }, locationTimeLimit);

      try {

        self.watchID = navigator.geolocation.watchPosition(function (position) {
          self.navigator_location_shared = true;
          window.clearTimeout(locationTimeout);

          self.navigator_location_shared = true;
          var delimiter = (position.address && position.address.street != '' && position.address.city != '') ? ', ' : '';
          var data = {
            'accuracy': position.coords.accuracy,
            'latitude': position.coords.latitude,
            'longitude': position.coords.longitude,
            'label': (position.address) ? (position.address.street + delimiter + position.address.city) : '',
            'vicinity': (position.address) ? (position.address.street + delimiter + position.address.city) : ''
          };
          if (!position.address) {
            data.vicinity = self.getAddress(position.coords);
            self.location = data;
            self.suggest.setOptions({
              'postData': self.getLocation()
            });
          } else {
            if (!self.add_location)
              self.location = data;
            self.suggest.setOptions({
              'postData': self.getLocation()
            });
            self.getEmptySuggest();
          }


        }, function () {
          self.getEmptySuggest();
        });
        //}
      } catch (e) {
        self.getEmptySuggest();
      }
    },
    getAddress: function (location) {
      var self = this;
      var map = new google.maps.Map(new Element('div'), {
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: new google.maps.LatLng(location.latitude, location.longitude),
        zoom: 15
      });
      var service = new google.maps.places.PlacesService(map);
      var request = {
        location: new google.maps.LatLng(location.latitude, location.longitude),
        radius: 500
      };

      service.search(request, function (results, status) {
        if (status == 'OK') {
          self.location.vicinity = results[0].vicinity;

          self.suggest.setOptions({
            'postData': self.getLocation()
          });

          var index = 0;
          var radian = 3.141592653589793 / 180;
          var my_distance = 1000;
          var R = 6371; // km
          for (var i = 0; i < results.length; i++) {
            var lat2 = results[i].geometry.location.lat();
            var lon2 = results[i].geometry.location.lng();
            var dLat = (lat2 - location.latitude) * radian;
            var dLon = (lon2 - location.longitude) * radian;
            var lat1 = location.latitude * radian;
            lat2 = lat2 * radian;
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.sin(dLon / 2) * Math.sin(dLon / 2) * Math.cos(lat1) * Math.cos(lat2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var d = R * c;

            if (d < my_distance) {
              index = i;
              my_distance = d;
            }
          }

          self.getEmptySuggest();
          return results[index].vicinity;
        }

      });
    },
    getEmptySuggest: function () {
      if (this.call_empty_suggest)
        return;
      this.elements.input.focus();
      if (this.suggest && this.suggest.element.value == '') {
        this.suggest.queryValue = ' ';
        this.suggest.prefetch();
      }

      this.call_empty_suggest = true;
    },
    getSuggest: function () {
      if (!this.suggest) {
        var width = (this.getComposer().elements.body.getSize().x - 4);
        if (width < 0)
          width = 0;
        this.suggestContener = new Element('div', {
          'class': 'sitecheckin-autosuggest-contener',
          'styles': {
            'width': width + 'px',
            'display': 'none'
          }
        });

        this.choicesSliderArea = new Element('div', {
          'class': 'sitecheckin-autosuggest'
        });


        this.choices = new Element('ul', {
          'class': 'tag-autosuggest seaocore-autosuggest sitetagcheckin-autosuggestlist-feed',
          'styles': {
            'width': width + 'px'
          }
        }).inject(this.choicesSliderArea);

        this.choicesSliderArea.inject(this.suggestContener);
        new Element('div', {
          'class': 'clr'
        }).inject(this.suggestContener);
        this.suggestMap = new Element('div', {
          'class': 'sitecheckin-autosuggest-map',
          'styles': {
            'position': 'relative'
          }
        }).inject(this.suggestContener);

        this.suggestContener.inject(this.elements.input, 'after');
        this.scroller = new SEAOMooVerticalScroll(this.choicesSliderArea, this.choices, {});
        var self = this;
        var options = $merge(this.options.suggestOptions, {
          'cache': false,
          'selectMode': 'pick',
          'postVar': 'suggest',
          'minLength': 0,
          'className': 'searchbox_autosuggest',
          //    'autocompleteType': 'tag',
          //    'multiple': false,        
          'filterSubset': true,
          'tokenValueKey': 'label',
          'tokenFormat': 'object',
          'customChoices': this.choices,
          'maxChoices': 25,
          alwaysOpen: true,
          'postData': this.getLocation(),
          'indicatorClass': 'checkin-loading',
          'injectChoice': function (token) {
            if (token.type != "just_use") {
              var choice = new Element('li', {
                'class': 'autocompleter-choices',
                'value': this.markQueryValue(token.label),
                'html': token.photo || '',
                'id': token.id
              });
              var divEl = new Element('div', {
                'html': this.markQueryValue(token.label),
                'class': 'autocompleter-choice'
              });
              if (token.type != 'place') {
                new Element('div', {
                  'html': this.markQueryValue(token.category) + ' &#8226; ' + this.markQueryValue(token.vicinity)
                }).inject(divEl);
              }
              divEl.inject(choice);
              this.addChoiceEvents(choice).inject(this.choices);
            } else {
              var choice = new Element('li', {
                'class': 'autocompleter-choices',
                'value': "text",
                'html': token.photo || '',
                'id': "just_use_li"
              });
              var divEl = new Element('div', {
                'html': this.markQueryValue(token.li_html),
                'class': 'autocompleter-choice chekin_autosuggest_just_use'
              });

              divEl.inject(choice);
              this.addChoiceEvents(choice).inject(this.choices);
              choice.store('autocompleteJustUseChoice', true);
            }
            choice.store('autocompleteChoice', token);
            self.scroller.update();

          },
          'onShow': function () {
            if (self.add_location || self.elements.container.hasClass("dnone")) {
              this.hideChoices(true);
              return;
            }
            self.suggestContener.setStyles({
              'width': (self.getComposer().elements.body.getSize().x - 8),
              'display': "block"
            });
            (function () {
              self.scroller.update();
            }).delay(500);
          },
          'onHide': function () {
            self.suggestContener.setStyles({
              'display': "none"
            });
          },
          'onSelect': function (input, choice) {
            if (choice.retrieve('autocompleteJustUseChoice', false)) {
              self.suggestMap.style.display = "none";
            } else {
              self.suggestMap.style.display = "block";
              var data = choice.retrieve('autocompleteChoice');
              self.setMarker(data, choice);
            }
          },
          'onChoiceSelect': function (choice) {
            var data = choice.retrieve('autocompleteChoice');
            if (data.type == 'place') {
              var map = new google.maps.Map(new Element('div'), {
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                center: new google.maps.LatLng(0, 0),
                zoom: 15
              });
              var service = new google.maps.places.PlacesService(map);
              service.getDetails({
                'placeId': data.place_id
              }, function (place, status) {
                if (status == 'OK') {
                  data.name = place.name;
                  // data.google_id = place.id;
                  data.latitude = place.geometry.location.lat();
                  data.longitude = place.geometry.location.lng();
                  data.vicinity = (place.vicinity) ? place.vicinity : place.formatted_address;
                  data.icon = place.icon;
                  data.types = place.types.join(',');
                  data.prefixadd = data.types.indexOf('establishment') > -1 ? en4.core.language.translate('at') : en4.core.language.translate('in');
                  choice.store('autocompleteChoice', data);
                  // self.toggleLoader(false);
                  self.location = data;
                }
              });
            }

            self.setLocation(data);
          },
          'emptyChoices': function () {
            this.fireEvent('onHide', [this.element, this.choices]);
          },
          'onBlur': function () {

            var selfAuto = this;
            (function () {
              // selfAuto.hideChoices(true);
            }).delay(500);
          },
          'onFocus': function () {
            if (self.call_empty_suggest && !self.add_location)
              this.prefetch.delay(this.options.delay + 50, this);
          }
        });

        this.suggest = new Autocompleter.Request.JSON(this.elements.input, this.options.suggestOptions.url, options);
        this.suggest.options.alwaysOpen = false;
        this.elements.input
                .addEvent('focus', this.suggest.toggleFocus.create({bind: this.suggest, arguments: true, delay: 100}))
                .addEvent('blur', this.suggest.toggleFocus.create({bind: this.suggest, arguments: false, delay: 500}));
      }

      return this.suggest;
    },
    addJustUseLi: function () {

    },
    getLocation: function () {
      var location = {
        'latitude': 0,
        'longitude': 0,
        'location_detected': ''
      };

      if (this.isValidLocation(false, true)) {
        location.latitude = this.location.latitude;
        location.longitude = this.location.longitude;
        //location.location_detected = (this.location.vicinity) ? this.location.vicinity:this.location.label;
        location.location_detected = this.location.label;
      }

      return location;
    },
    getLocationHTML: function () {
      var location = this.location;
      //var content  = en4.core.language.translate(this.location.prefixadd)+' '+'<a href = "javascript:void(0)">'+((location.type == 'place' && location.vicinity)? ((location.name && location.name != location.vicinity) ?  location.vicinity : location.vicinity) : location.label)+'</a>';
      var content = en4.core.language.translate(this.location.prefixadd) + ' ' + '<a href = "javascript:void(0)">' + location.label + '</a>';
      return content;
    },
    setLocation: function (location) {
      this.location = location;
      if (this.isValidLocation(location)) {
        var checkin_hash = new Hash(location);
      }
      this.add_location = true;
      this.elements.contentdisplay.empty();
      this.elements.input.set('value', location.label);
      this.checkinToggle();
      this.getComposer().focus();
      var self = this;
      if (!$('composer_preview_display_tray').getFirst('span').hasClass('mdash')) {
        this.elements.spanMdash = new Element('span', {
          'class': 'mdash',
          'html': '&mdash;',
        }).inject($('composer_preview_display_tray'), 'top');
      }
      if (!$('composer_preview_display_tray').getLast('span').hasClass('dot')) {
        this.elements.spanDot = new Element('span', {
          'class': 'dot',
          'html': '.',
        }).inject($('composer_preview_display_tray'), 'bottom');
      }
      this.elements.composerTrydisplay.innerHTML = this.getLocationHTML();
      $('composer_preview_display_tray').removeClass('dnone');
      this.suggest.setOptions({
        'postData': this.getLocation()
      });
    },
    removeLocation: function () {
      this.elements.contentdisplay.empty();
      this.elements.composerTrydisplay.empty();
      var content = this.getComposer().elements.body.getParent().getParent().getLast('div');
      var removeContent = true;
      content.getElements('span').each(function (el) {
        if (el.get('class') != 'aaf_mdash' && el.get('class') != 'aaf_dot' && el.innerHTML != '') {
          removeContent = false;
        }
      });
      this.add_location = false;
      this.navigator_location_shared = false;

      this.suggest.setOptions({
        'postData': this.getLocation()
      });
      this.location = "";
      this.elements.input.value = '';
      var self = this;
      (function () {
        if (self.elements.container.hasClass("dblock"))
          self.elements.input.focus();
      }).delay(100);
      this.deactivate();
    },
    isValidLocation: function (location, checkin_params) {
      location = (location) ? location : this.location;
      return  (checkin_params)
              ? (location && location.latitude && this.location.longitude)
              : (location && location.label != undefined && location.label != '');
    },
    submit: function () {
      var checkinStr = '';
      if (this.add_location) {
        var checkinHash = new Hash(this.location);
        checkinStr = checkinHash.toQueryString();
        if (this.options.allowEmpty)
          this.getComposer().options.allowEmptyWithoutAttachment = true;
      }
      this.makeFormInputs({
        checkin: checkinStr
      });
    },
    submitAfter: function () {
      if (this.elements.container && this.elements.container.hasClass("dblock")) {
        this.elements.container.removeClass("dblock").addClass("dnone")
      }
      if (this.add_location)
        this.removeLocation();
      this.getComposer().options.allowEmptyWithoutAttachment = false;
    },
    setMarker: function (checkin, choice) {
      var self = this;
      // var map = this.suggestMap;

      if (checkin.latitude == undefined) {
        var map = new google.maps.Map(new Element('div'), {
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          center: new google.maps.LatLng(0, 0),
          zoom: 15
        });
        var service = new google.maps.places.PlacesService(map);
        service.getDetails({
          'placeId': checkin.place_id
        }, function (place, status) {
          if (status == 'OK') {
            //checkin.google_id = place.id;
            checkin.name = place.name;
            checkin.vicinity = (place.vicinity) ? place.vicinity : place.formatted_address;
            checkin.latitude = place.geometry.location.lat();
            checkin.longitude = place.geometry.location.lng();
            checkin.icon = place.icon;
            checkin.types = place.types.join(',');
            checkin.prefixadd = checkin.types.indexOf('establishment') > -1 ? en4.core.language.translate('at') : en4.core.language.translate('in');
            choice.store('autocompleteChoice', checkin);
            self.setMarker(checkin, choice);
          }
        });

        return;
      }

      var myLatlng = new google.maps.LatLng(checkin.latitude, checkin.longitude);
      var new_map = false;
      if (this.map == undefined || !this.suggestMap.getFirst()) {
        new_map = true;
        this.map = new google.maps.Map(this.suggestMap, {
          navigationControl: false,
          mapTypeControl: false,
          scaleControl: false,
          draggable: false,
          streetViewControl: false,
          zoomControl: false,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          center: myLatlng,
          zoom: 15
        });
      }

      if (new_map) {
        this.marker = new google.maps.Marker({
          position: myLatlng,
          map: this.map
        });
        this.map.setCenter(myLatlng);
      } else {
        this.marker = (this.marker == undefined) ? new google.maps.Marker({
          position: myLatlng,
          map: this.map
        }) : this.marker;
        this.marker.setPosition(myLatlng);
        this.map.panTo(myLatlng);
      }

    },
    makeFormInputs: function (data) {
      $H(data).each(function (value, key) {
        this.setFormInputValue(key, value);
      }.bind(this));
    },
    // make chekin hidden input and set value into composer form
    setFormInputValue: function (key, value) {
      var elName = 'aafComposerForm' + key.capitalize();
      var composerObj = this.getComposer();
      if (composerObj.elements.has(elName))
        composerObj.elements.get(elName).destroy();
      composerObj.elements.set(elName, new Element('input', {
        'type': 'hidden',
        'name': 'composer[' + key + ']',
        'value': value || ''
      }).inject(composerObj.getInputArea()));
      composerObj.elements.get(elName).value = value;
    },
    resetMdashMdot: function (name) {
      if ($('compose-feeling-composer-display') && $('compose-feeling-composer-display').innerHTML != "") {
        return;
      }
      if ($('compose-checkin-composer-display') && $('compose-checkin-composer-display').innerHTML != "") {
        return;
      }
      if ($('friendas_tag_body_aaf_content') && $('friendas_tag_body_aaf_content').innerHTML != "") {
        return;
      }
      if(!$('composer_preview_display_tray')){
          return;
      }
      if ($('composer_preview_display_tray').getFirst('span') && $('composer_preview_display_tray').getFirst('span').hasClass('mdash')) {
          $('composer_preview_display_tray').getFirst('span').destroy();
      }
      if ($('composer_preview_display_tray').getLast('span') && $('composer_preview_display_tray').getLast('span').hasClass('dot')) {
          $('composer_preview_display_tray').getLast('span').destroy();
      }
      $('composer_preview_display_tray').addClass('dnone');
    }
  });



})(); // END NAMESPACE
