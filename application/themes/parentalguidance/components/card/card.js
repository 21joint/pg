import './card.scss';
import {renderProfileBox} from '../profile-box/profile-box';
import {renderRate} from '../rating/rating';

function renderCard(review, options) {
  let _html = '';

  let createdDt = new Date(review.createdDateTime).toString().split(' ');
  let createdMonth = createdDt[1];
  let createdDay = createdDt[2].charAt(0) == 0 ? createdDt[2].split('')[1] : createdDt[2];

  _html = `<div class="col-6 col-lg-4 p-2">
  <!--single card-->
  <div class="card card-${options.type} h-100 d-flex flex-column" data-id="${review.reviewID}">
    <div class="card-header p-0 overflow-hidden">
      <div class="card-actions">
        <ul class="row list-unstyled no-gutters mb-0">
          <li class="col"><a class="d-flex justify-content-center align-items-center text-white"
                             role="button"><span class="far fa-heart"></span></a></li>
          <li class="col"><a class="d-flex justify-content-center align-items-center text-white"
                             role="button"><span class="fas fa-clipboard"></span></a></li>
          <li class="col"><a class="d-flex justify-content-center align-items-center text-white"
                             role="button"><span class="fab fa-facebook-f"></span></a></li>
          <li class="col"><a class="d-flex justify-content-center align-items-center text-white"
                             role="button"><span class="fab fa-twitter"></span></a></li>
          <li class="col"><a class="d-flex justify-content-center align-items-center text-white"
                             role="button"><span class="fab fa-pinterest"></span></a></li>
          <li class="col"><a class="d-flex justify-content-center align-items-center text-white"
                             role="button"><span class="fas fa-link"></span></a></li>
        </ul>
      </div>
      <a href="/reviews/view/${review.reviewID}"
          class="d-block card-img--wrapper lazy"
          data-loader="asyncLoader" data-lazy-image="${review.coverPhoto.photoURL}">
      </a>
      ${options.review == 'review' ? renderRate(review.averageReviewRating, 5) : ''}
      ${options.review == 'guide' ? `<div class="prg-card--thumbs">
                                       <ul class="list-inline m-0 d-flex no-gutters justify-content-between">
                                        <li class="col-auto"><img class="" src="${review.coverPhoto.photoURL}" alt="${review.title}"/></li>
                                        <li class="col-auto"><img class="" src="${review.coverPhoto.photoURL}" alt="${review.title}"/></li>
                                        <li class="col-auto"><img class="" src="${review.coverPhoto.photoURL}" alt="${review.title}"/></li>
                                        <li class="col-auto"><img class="" src="${review.coverPhoto.photoURL}" alt="${review.title}"/></li>
                                        <li class="col-auto"><img class="" src="${review.coverPhoto.photoURL}" alt="${review.title}"/></li>
                                        <li class="col-auto"><img class="" src="${review.coverPhoto.photoURL}" alt="${review.title}"/></li>
                                       </ul>
                                    </div>` : ''}
    </div>
    <div class="card-content d-flex flex-column h-100">
      <div class="card-body">
        <h6 class="card-subtitle my-1 text-primary font-weight-bold">${review.reviewCategorization.category}</h6>
        <a href="/reviews/view/${review.reviewID}"><h5 class="card-title font-weight-bold">
          ${review.title}</h5></a>
        <p class="card-text mb-0 d-none d-sm-block">${review.shortDescription}</p>
      </div>
      <div class="card-footer bg-white">
        <div class="row align-items-center justify-content-between flex-nowrap">
          <div class="col-auto col-sm">
            <div class="card-author">
              <div class="row no-gutters flex-nowrap align-items-center">
                <div class="col-auto d-none d-sm-block">
                  <div class="card-author--avatar">${renderProfileBox(review.author, {width: 50,height:50})}</div>
                </div>
                <div class="col">
                  <a href="/profile/${review.author.memberName}">
                    <h6 class="card-author--title mb-0"><b>${review.author.displayName}</b></h6>
                  </a>
                  <div class="card-date">
                    <span class="ff-open--sans text-asphalt small">${createdMonth} ${createdDay}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-auto text-right d-none d-sm-block">
            <ul class="list-inline my-0">
              <li class="flex-row align-items-center list-inline-item">
                <span class="fas fa-heart"></span>
                <span class="text-asphalt">${review.likesCount}</span>
              </li>
              <li class="flex-row align-items-center list-inline-item">
                <span class="fas fa-comments"></span>
                <span class="text-asphalt">${review.commentsCount}</span>
              </li>
            </ul>
          </div>
          <div class="col-auto text-center d-sm-none">
            <button class="btn bg-transparent btn-card--details">
              <i class="fa fa-ellipsis-h mr-0"></i>
            </button>
          </div>
        </div>
      </div>
  </div>
  </div>
</div>`;

  return _html;
}

export {renderCard};
