function hoverBoxImage() {

  $$('.item_photo_user').each( function (element) {

      element.getParent().getParent().classList.add('d-inline-block')
      let elementHover = element.getParent().getParent().getParent().getElement('.extfox-widgets');
      let closeElement = elementHover.getElement('.close a');

      element.getParent().removeEvents('click').addEvent('click', function(e) {
        e.stop();

        hideHoverBoxes();
        
        if( elementHover.classList.contains('active') ) {
          elementHover.classList.remove('active');
        } else {
          elementHover.classList.add('active');
        }

        // get close buttons
        closeElement.removeEvents('click').addEvent('click', function(e) {
          elementHover.classList.remove('active');
        });

      });

  });
}

function hideHoverBoxes() {
  $$('.extfox-widgets.active').each( function (element) {
    element.classList.remove('active');
  });
}

function showEditContent() {
   
   let tabs = document.getElementsByClassName('layout_core_container_tabs');

   if(tabs[0].classList.contains('updateMode') == true) {
     tabs[0].classList.remove('updateMode');
     tabs[0].style = 'display: block;';
     tabs[1].style = 'display: none;';
   } else {
    tabs[0].classList.add('updateMode');
    tabs[0].style = 'display: none;';
    tabs[1].style = 'display: block;';
   }
   

   
}


window.addEvent('load', function () {
  hoverBoxImage();
});

// AJAX ACTIONS
en4.gg = {

  ggAjax : function(type, subject_id, action, el) {

      (new Request.JSON({
          url : en4.core.baseUrl + 'gg/ajax/' + action,
          data : {
              format : 'json',
              type : type,
              id : subject_id,
          },
          onComplete: function(resp) {
              
            if(resp.status == false) en4.core.showError('Something went wrong. Please try again.');
            let content = el.textContent.replace(/\s/g, "");
            
            (content == 'MakePublic') ? el.textContent = 'Make Private' : el.textContent = 'Make Public';

          }
      })).send();

  },

  ggAjaxForm: function(form, action) {
    
    var errorElement = form.getParent().getElement('#errorForm');
    var successElement = form.getParent().getElement('#successForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        var formValues = new Object();
        for(var i = 0; i < form.elements.length; i++) {
            formValues['' + form.elements[i].name + ''] = form.elements[i].value;
        }

        // make ajax request to form URL
        (new Request.JSON({
            url : en4.core.baseUrl + 'gg/ajax/' + action,
            data : {
                format : 'json',
                values: formValues,
            },
            onComplete: function(resp) {
                if(resp.status == false) {
                    errorElement.textContent = resp.message;
                    successElement.textContent = '';
                }
                if(resp.status == true) {
                    successElement.textContent = resp.message;
                    errorElement.textContent = '';
                }
            }
        })).send();

    });

  }
  
}

var tab_content_id_extfox = 0;

// build ajax content for tabs inside
en4.gg.ajaxTabContent = {
  click_elment_id: '',
  attachEvent: function(itemParams, params) {
      //what to sort?
      if(itemParams.page) {
          params.requestParams.page = itemParams.page;
      }
      if(itemParams.category) {
          params.requestParams.category_id = itemParams.category;
      }
      if(itemParams.type) {
          params.requestParams.type = itemParams.type;
      }

      en4.gg.ajaxTab.sendReq(params);
  }
}


// ajax tabs
en4.gg.ajaxTab = {
  click_elment_id: '',
  attachEvent: function (widget_id, params) {
      
      params.requestParams.content_id = widget_id;
      var element;

      $$('.tab_' + widget_id).each(function (el) {
          if (el.get('tag') == 'li') {
              element = el;
              return;
          }
      });
      var onloadAdd = true;

      if (element) {
          if (element.retrieve('addClickEvent', false))
              return;
          element.addEvent('click', function () {
              if (en4.gg.ajaxTab.click_elment_id == widget_id)
                  return;
              en4.gg.ajaxTab.click_elment_id = widget_id;
              en4.gg.ajaxTab.sendReq(params);
          });
          element.store('addClickEvent', true);
          var attachOnLoadEvent = false;
          if (tab_content_id_extfox == widget_id) {
              attachOnLoadEvent = true;
          } else {
              $$('.tabs_parent').each(function (element) {
                  var addActiveTab = true;
                  element.getElements('ul > li').each(function (el) {
                      if (el.hasClass('active')) {
                          addActiveTab = false;
                          return;
                      }
                  });
                  element.getElementById('main_tabs').getElements('li:first-child').each(function (el) {
                      if (el.getParent('div') && el.getParent('div').hasClass('tab_pulldown_contents'))
                          return;
                      el.get('class').split(' ').each(function (className) {
                          className = className.trim();
                          if (className.match(/^tab_[0-9]+$/) && className == "tab_" + widget_id) {
                              attachOnLoadEvent = true;
                              if (addActiveTab) {
                                  element.getElementById('main_tabs').getElements('ul > li').removeClass('active');
                                  el.addClass('active');
                                  element.getParent().getChildren('div.' + className).setStyle('display', null);
                              }
                              return;
                          }
                      });
                  });
              });
          }
          if (!attachOnLoadEvent)
              return;
          onloadAdd = false;

      }

  },

  sendReq: function(params) {
      
      params.responseContainer.each(function (element) {
          if ((typeof params.loading) == 'undefined' || params.loading == true) {
              element.empty();
              new Element('div', {
                  'class': 'container col-1 m-auto',
                  'html': '<svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-ball2"><g ng-attr-transform="translate(0,{{config.dy}})" transform="translate(0,-7.5)"><circle cx="50" ng-attr-cy="{{config.cy}}" r="6.25293" ng-attr-fill="{{config.c1}}" cy="41" fill="#5CC7CE" transform="rotate(282 50 50)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform><animate attributeName="r" calcMode="spline" values="0;15;0" keyTimes="0;0.5;1" dur="1" keySplines="0.2 0 0.8 1;0.2 0 0.8 1" begin="0s" repeatCount="indefinite"></animate></circle><circle cx="50" ng-attr-cy="{{config.cy}}" r="8.74707" ng-attr-fill="{{config.c2}}" cy="41" fill="#8AE693" transform="rotate(462 50 50)"><animateTransform attributeName="transform" type="rotate" calcMode="linear" values="180 50 50;540 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform><animate attributeName="r" calcMode="spline" values="15;0;15" keyTimes="0;0.5;1" dur="1" keySplines="0.2 0 0.8 1;0.2 0 0.8 1" begin="0s" repeatCount="indefinite"></animate></circle></g></svg>'
              }).inject(element);

          }
      });

      var url = en4.core.baseUrl + 'widget';
      var staticUrl = en4.core.staticBaseUrl;
      if (params.requestUrl)
          url = params.requestUrl;
      
      var request = new Request.HTML({
          url: url,
          data: $merge(params.requestParams, {
              format: 'html',
              subject: en4.core.subject.guid,
              is_ajax_load: true
          }),
          evalScripts: true,
          onSuccess: function( responseTree, responseElements, responseHTML, responseJavaScript ) {
              params.responseContainer.each(function (container) {
                  container.empty();
                  Elements.from(responseHTML).inject(container);
                  en4.core.runonce.trigger();
                  Smoothbox.bind(container);
              });
          }
      });
      
      // send request
      request.send();
  }
}

window.addEvent('load', function () {
  $$('.tabs_parent').each(function (element) {
      element.getElements('ul > li').each(function (el) {
          if (el.hasClass('active')) {
              el.click();
          }
      });
  });
});