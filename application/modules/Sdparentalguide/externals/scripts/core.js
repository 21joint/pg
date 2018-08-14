function hoverBoxImage() {

  $('.item_photo_user').each(function (i, element) {

    $(element).parent().parent().addClass('d-inline-block');

    let $elementHover = $(element).closest('.extfox-widgets');
    let $closeElement = $elementHover.find('.close a');
    $(element).parent().off('click').on('click', function (e) {

      e.stop();

      hideHoverBoxes();

      if ($elementHover.hasClass('active')) {
        $($elementHover).removeClass('active');
      } else {
        $($elementHover).addClass('active');
        // activateProfileItem('profile-hover');
      }

      // get close buttons
      $closeElement.off('click').on('click', function (e) {
        $($elementHover).removeClass('active');
      });

    });

  });
}

function hideHoverBoxes() {
  $('.extfox-widgets.active').each(function (i, element) {
    $(element).removeClass('active');
  });
}

function showEditContent(element) {

  let tabs = document.getElementsByClassName('layout_core_container_tabs');

  if ($(tabs[0]).hasClass('updateMode')) {
    $(tabs[0]).removeClass('updateMode');
    tabs[0].css('display', 'block');
    tabs[1].css('display', 'none');

    $(element).removeClass('btn-info');
    $(element).addClass('btn-success');
    $(element).text(en4.core.language.translate('Edit Profile'));

  } else {
    $(tabs[0]).addClass('updateMode');
    $(tabs[0]).css('display', 'none');
    $(tabs[1]).css('display', 'block');

    $(element).removeClass('btn-success');
    $(element).addClass('btn-info');
    $(element).text(en4.core.language.translate('Done Editing'));
  }

}

window.addEvent('load', function () {
  hoverBoxImage();
});

// AJAX ACTIONS
en4.gg = {

  ggAjax: function (type, subject_id, action, el) {

    (new Request.JSON({
      url: en4.core.baseUrl + 'gg/ajax/' + action,
      data: {
        format: 'json',
        type: type,
        id: subject_id,
      },
      onComplete: function (resp) {

        if (resp.status == false) en4.core.showError('Something went wrong. Please try again.');
        let content = el.textContent.replace(/\s/g, "");

        (content == 'MakePublic') ? el.textContent = 'Make Private' : el.textContent = 'Make Public';

      }
    })).send();

  },

  ggAjaxForm: function (form, action) {

    var errorElement = form.getParent().getElement('#errorForm');
    var successElement = form.getParent().getElement('#successForm');

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      // make specific checks for privacy
      if (action == 'privacy' || action == 'notifications' || action == 'preference') {

        var formValues = [];
        for (var i = 0; i < form.elements.length; i++) {
          if (form.elements[i].type == 'radio' || form.elements[i].type == 'checkbox') {
            if (form.elements[i].type == 'checkbox')
              formValues.push({
                key: form.elements[i].name,
                name: form.elements[i].value,
                value: form.elements[i].checked
              });
            else if (form.elements[i].checked === true)
              formValues.push({
                key: form.elements[i].name,
                name: form.elements[i].value,
                value: form.elements[i].checked
              });
          }
        }
      } else {
        var formValues = new Object();
        for (var i = 0; i < form.elements.length; i++) {
          formValues['' + form.elements[i].name + ''] = form.elements[i].value;
        }
      }

      // make ajax request to form URL
      (new Request.JSON({
        url: en4.core.baseUrl + 'gg/ajax/' + action,
        data: {
          format: 'json',
          values: formValues,
        },
        onComplete: function (resp) {
          if (resp.status == false) {
            errorElement.textContent = resp.message;
            successElement.textContent = '';
          }
          if (resp.status == true) {
            successElement.textContent = resp.message;
            errorElement.textContent = '';
            if (action == 'delete') {
              window.location.href = en4.core.staticBaseUrl;
            }
          }
        }
      })).send();

    });

  }

};

var tab_content_id_extfox = 0;

// build ajax content for tabs inside
en4.gg.ajaxTabContent = {
  click_elment_id: '',
  attachEvent: function (itemParams, params) {
    //what to sort?
    if (itemParams.page) {
      params.requestParams.page = itemParams.page;
    }
    if (itemParams.category) {
      params.requestParams.category_id = itemParams.category;
    }
    if (itemParams.type) {
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

    $('.tab_' + widget_id).each(function (el) {
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
        $('.tabs_parent').each(function (i, element) {
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

  sendReq: function (params) {

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

    var format = 'html';
    var method = 'post';
    try {
      if (params.requestParams.hasOwnProperty("format")) {
        format = params.requestParams.format;
      }
      if (params.requestParams.hasOwnProperty("method")) {
        method = params.requestParams.method;
      }
    } catch (e) {
    }

    if (format == 'json') {
      var request = new Request.JSON({
        url: url,
        method: 'get',
        data: $merge(params.requestParams, {
          format: 'json',
          subject: en4.core.subject.guid,
          is_ajax_load: true
        }),
        onSuccess: params.successHandler
      });
    } else {
      var request = new Request.HTML({
        url: url,
        method: 'get',
        data: $merge(params.requestParams, {
          format: 'html',
          subject: en4.core.subject.guid,
          is_ajax_load: true
        }),
        evalScripts: true,
        onSuccess: function (responseTree, responseElements, responseHTML, responseJavaScript) {
          params.responseContainer.each(function (container) {
            container.empty();
            Elements.from(responseHTML).inject(container);
            en4.core.runonce.trigger();
            Smoothbox.bind(container);
          });
        }
      });
    }

    // send request
    request.send();
  }
}


window.addEvent('load', function () {

  $('.tabs_parent').each(function (i, element) {
    $(element).find('ul > li').each(function () {
      if ($(this).hasClass('active')) {
        $(this).click();
      }
    });
  });

});

