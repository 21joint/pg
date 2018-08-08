import {API_PROXY, OAUTH} from "../../../../package";
import Card from '../components/card/card';

const url = API_PROXY + '/review?' + OAUTH;


let Api = {};

// Reviews
Api.getReviews = getReviews;
Api.getReview = getReview;


// Get 50 reviews : @params: (options: { container: @selector }, callback : @function)
function getReviews(options, callback) {
  let _reviews = {};

  $.ajax({
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
          $html: $(Card.render(_reviews.data[j]))
        });
        _reviews.html += Card.render(_reviews.data[j]);
      }
      callback(_reviews);
    },
    error: function (error) {
      console.error(error);
    },
    complete: function () {
      $(options.container).addClass('loaded');
    }
  })
}

// Get single review
function getReview(options, callback) {

  if (options.id) {
    $.ajax({
      method: 'GET',
      dataType: 'json',
      url: url + '&reviewID=' + options.id,
      success: function (res) {
        callback(res.body.Results[0]);
      },
      error: function (error) {
        console.error(error);
      },
      complete: function () {
        $(options.container).addClass('loaded');
      }
    });
  }
}


export default Api;
