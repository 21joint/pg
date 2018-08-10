import {API_PROXY, OAUTH} from "../../../../package";
import {renderCard} from '../components/card/card';


function getCategories(opts, callback) {
  const url = API_PROXY + '/categorization?' + (!!opts.type && opts.type ? 'typeID=' + opts.type : '') + OAUTH;
  // let _reviews = {};

  jQuery.ajax({
    method: 'GET',
    url: url,
    dataType: 'json',
    success: function (res) {
      callback(res.body.Results);
    },
    error: function (error) {
      console.error(error);
    }
  })
}

// Get all reviews
function getReviews(opts, callback) {
  const url = API_PROXY + '/review?' + OAUTH;
  let _reviews = {};

  jQuery.ajax({
    method: 'GET',
    url: url,
    dataType: 'json',
    success: function (res) {
      console.log(res);
      _reviews.data = res.body.Results;
      _reviews.html = '';
      _reviews.cards = [];
      for (let j = 0; j < _reviews.data.length; j++) {
        _reviews.cards.push({
          $html: $(renderCard(_reviews.data[j], {
              type: 'review'
            })
          )
        });
        _reviews.html += renderCard(_reviews.data[j], {
          type: 'review'
        });
      }
      callback(_reviews);
    },
    error: function (error) {
      console.error(error);
    },
    complete: function () {
      $(opts.container).addClass('loaded');
    }
  })
}

// Get all guides
function getGuides(opts, callback) {
  // @TODO should be guide
  const url = API_PROXY + '/review?' + OAUTH;
  let _guides = {};

  jQuery.ajax({
    method: 'GET',
    url: url,
    dataType: 'json',
    success: function (res) {
      console.log(res);
      _guides.data = res.body.Results;
      _guides.html = '';
      _guides.cards = [];
      for (let j = 0; j < _guides.data.length; j++) {
        _guides.cards.push({
          $html: $(renderCard(_guides.data[j], {
              type: 'guide'
            })
          )
        });
        _guides.html += renderCard(_guides.data[j], {
          type: 'guide'
        });
      }
      callback(_guides);
    },
    error: function (error) {
      console.error(error);
    },
    complete: function () {
      $(opts.container).addClass('loaded');
    }
  })
}

// Get single review
function getReview(opts, callback) {
  const url = API_PROXY + '/review?' + OAUTH;

  if (opts.id) {
    jQuery.ajax({
      method: 'GET',
      dataType: 'json',
      url: url + '&reviewID=' + opts.id,
      success: function (res) {
        callback(res.body.Results[0]);
      },
      error: function (error) {
        console.error(error);
      },
      complete: function () {
        $(opts.container).addClass('loaded');
      }
    });
  }
}


export {getReviews, getReview, getGuides, getCategories};
