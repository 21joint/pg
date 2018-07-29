import pkg from '../../../../package';
import jQuery from "jquery";

const Reviews = (function ($) {
  let Reviews;

  Reviews = function () {

    let _self = this;

    const url = pkg.config.API_PROXY + '/review?' + pkg.config.OAUTH;

    this.get = get;
    this.buildCard = buildCard;
    this.buildRating = buildRating;
    this.loadSingle = loadSingle;

    function get(options, callback) {
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
              $html: $(_self.buildCard(_reviews.data[j]))
            });
            _reviews.html += _self.buildCard(_reviews.data[j]);
          }
          callback(_reviews);
        },
        error: function (error) {
          console.error(error.message);
        },
        complete: function () {
          $(options.container).addClass('loaded');
        }
      })
    }

    function buildRating(rating, max) {
      var html = '';
      for (var i = 0; i < rating; i++) {
        html +=
          '<li class="list-inline-item align-middle">\n' +
          '<span class="card-star--icon"></span>\n' +
          '</li>'
      }
      if (max - rating > 0) {
        for (var j = 0; j < max - rating; j++) {
          html +=
            '<li class="list-inline-item align-middle">\n' +
            '<span style="opacity: .4" class="card-star--icon"></span>\n' +
            '</li>'
        }
      }
      return html;
    }

    function buildCard(review, options) {

      var createdDt = new Date(review.createdDateTime).toString().split(' ');
      var createdMonth = createdDt[1];
      var createdDay = createdDt[2].charAt(0) == 0 ? createdDt[2].split('')[1] : createdDt[2];

      return '<div class="col-6 col-lg-4 p-2">\n' +
        '                    <!--single review card-->\n' +
        '                    <div class="card card-review h-100 d-flex flex-column" data-id="' + review.reviewID + '">\n' +
        '                      <div class="card-header p-0 overflow-hidden">\n' +
        '                        <div class="card-actions">\n' +
        '                          <ul class="row list-unstyled no-gutters mb-0">\n' +
        '                            <li class="col"><a class="d-flex justify-content-center align-items-center text-white"\n' +
        '                                               role="button"><span class="far fa-heart"></span></a></li>\n' +
        '                            <li class="col"><a class="d-flex justify-content-center align-items-center text-white"\n' +
        '                                               role="button"><span class="fas fa-clipboard"></span></a></li>\n' +
        '                            <li class="col"><a class="d-flex justify-content-center align-items-center text-white"\n' +
        '                                               role="button"><span class="fab fa-facebook-f"></span></a></li>\n' +
        '                            <li class="col"><a class="d-flex justify-content-center align-items-center text-white"\n' +
        '                                               role="button"><span class="fab fa-twitter"></span></a></li>\n' +
        '                            <li class="col"><a class="d-flex justify-content-center align-items-center text-white"\n' +
        '                                               role="button"><span class="fab fa-pinterest"></span></a></li>\n' +
        '                            <li class="col"><a class="d-flex justify-content-center align-items-center text-white"\n' +
        '                                               role="button"><span class="fas fa-link"></span></a></li>\n' +
        '                          </ul>\n' +
        '                        </div>\n' +
        '                        <a\n' +
        '                           href="' + en4.core.baseUrl + 'reviews/view?reviewID=' + review.reviewID + '"\n' +
        '                           class="d-block card-img--wrapper"\n' +
        '                             style="background-image: url(' + review.coverPhoto.photoURL + ')">\n' +
        '                          <img class="invisible w-100 h-100 position-absolute" src="' + review.coverPhoto.photoURL + '" alt="' + review.title + '"/>\n' +
        '                        </a>\n' +
        '                        <div class="prg-stars">\n' +
        '                          <ul class="list-inline m-0">\n' + _self.buildRating(review.authorRating, 5) +
        '                          </ul>\n' +
        '                        </div>\n' +
        '                      </div>\n' +
        '                      <div class="card-body">\n' +
        '                        <h6 class="card-subtitle my-1 text-primary font-weight-bold">' + review.reviewCategorization.category + '</h6>\n' +
        '                        <h5 class="card-title font-weight-bold">' + review.title + '</h5>\n' +
        '                        <p class="card-text mb-0 d-none d-sm-block">' + review.shortDescription + '</p>\n' +
        '                      </div>\n' +
        '                      <div class="card-footer bg-white">\n' +
        '                        <div class="row align-items-center justify-content-between flex-nowrap">\n' +
        '                          <div class="col-auto col-sm">\n' +
        '                            <div class="card-author">\n' +
        '                              <div class="row no-gutters flex-nowrap align-items-center">\n' +
        '                                <div class="col-auto d-none d-sm-block">\n' +
        '                                  <div class="position-relative card-author--thumbnail">\n' +
        '                                    <img class="rounded-circle"\n' +
        '                                         src="'+ review.author.avatarPhoto.photoURLProfile +'"\n' +
        '                                         alt="Generic placeholder image">\n' +
        '                                    <b class="position-absolute d-flex justify-content-center align-items-center text-white bagde badge-primary rounded-circle ff-open--sans card-author--rank">' + review.author.contributionLevel + '</b>\n' +
        '                                  </div>\n' +
        '                                </div>\n' +
        '                                <div class="col">\n' +
        '                                  <a href="' + en4.core.baseUrl + 'profile/' + review.author.memberName + '">\n' +
        '                                     <h6 class="card-author--title mb-0"><b>' + review.author.displayName + '</b></h6>\n' +
        '                                  </a>\n' +
        '                                  <div class="card-date">\n' +
        '                                    <span class="ff-open--sans text-asphalt small">' + createdMonth + ' ' + createdDay + '</span>\n' +
        '                                  </div>\n' +
        '                                 </div>\n' +
        '                              </div>\n' +
        '                            </div>\n' +
        '                          </div>\n' +
        '                          <div class="col-auto text-right d-none d-sm-block">\n' +
        '                            <ul class="list-inline my-0">\n' +
        '                              <li class="flex-row align-items-center list-inline-item">\n' +
        '                                <span class="fas fa-heart"></span>\n' +
        '                                <span class="text-asphalt">' + review.likesCount + '</span>\n' +
        '                              </li>\n' +
        '                              <li class="flex-row align-items-center list-inline-item">\n' +
        '                                <span class="fas fa-comments"></span>\n' +
        '                                <span class="text-asphalt">' + review.commentsCount + '</span>\n' +
        '                              </li>\n' +
        '                            </ul>\n' +
        '                          </div>\n' +
        '                          <div class="col-auto text-center d-sm-none">\n' +
        '                          <button class="btn bg-transparent btn-card--details">\n' +
        '                            <i class="fa fa-ellipsis-h mr-0"></i>\n' +
        '                          </button>\n' +
        '                        </div>\n' +
        '                        </div>\n' +

        '                      </div>\n' +
        '                    </div>\n' +
        '                  </div>';
    }

    function loadSingle(options, callback) {
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

    return this;
  };

  return new Reviews();

})(jQuery);



export default Reviews;