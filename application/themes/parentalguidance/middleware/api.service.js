import {API_PROXY, OAUTH} from "../../../../package";


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
  let _reviews = [];

  jQuery.ajax({
    method: 'GET',
    url: url,
    dataType: 'json',
    success: function (res) {
      _reviews = res.body.Results;
      callback(_reviews);
      console.info('Got Reviews: ', _reviews);
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

// Get Leaders
function getLeaders(opts, callback) {
  const url = API_PROXY + '/ranking?' + OAUTH;

  let _leaders = [];

  jQuery.ajax({
    method: 'GET',
    dataType: 'json',
    url: url,
    success: function (res) {
      _leaders = res.body.Results;
      callback(_leaders);
    },
    error: function (error) {
      console.error(error);
    },
    complete: function () {
      $(opts.container).addClass('loaded');
    }
  });
}

// Get Member by id
function getMember(opts, callback) {
  const url = API_PROXY + '/member?' + (opts.memberID ? 'memberID=' + opts.memberID : '') + OAUTH;

  jQuery.ajax({
    method: 'GET',
    dataType: 'json',
    url: url,
    success: function (res) {
      callback(res);
    },
    error: function (error) {
      console.error(error);
    },
    complete: function () {
      $(opts.container).addClass('loaded');
    }
  });
}

export {getReviews, getReview, getGuides, getCategories, getLeaders, getMember};
