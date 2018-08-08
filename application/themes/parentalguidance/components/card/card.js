import './card.scss';
import Rating from '../rating/rating';

export default {
  render: function (review, options) {

    const _self = this;

    let createdDt = new Date(review.createdDateTime).toString().split(' ');
    let createdMonth = createdDt[1];
    let createdDay = createdDt[2].charAt(0) == 0 ? createdDt[2].split('')[1] : createdDt[2];

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
      '                          <ul class="list-inline m-0">\n' + Rating.build(parseInt(review.averageReviewRating, 10), 5) +
      '                          </ul>\n' +
      '                        </div>\n' +
      '                      </div>\n' +
      '                      <div class="card-body">\n' +
      '                        <h6 class="card-subtitle my-1 text-primary font-weight-bold">' + review.reviewCategorization.category + '</h6>\n' +
      '                        <a href="' + en4.core.baseUrl + 'reviews/view?reviewID=' + review.reviewID + '"><h5 class="card-title font-weight-bold">' + review.title + '</h5></a>\n' +
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
      '                                         src="' + review.author.avatarPhoto.photoURLProfile + '"\n' +
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
};
