import './card.scss';
import {renderAvatar} from '../avatar/avatar';
import {renderRate} from '../rating/rating';

function renderCard(type, options) {
  let _html = '';

  let createdDt = new Date(type.createdDateTime).toString().split(' ');
  let createdMonth = createdDt[1];
  let createdDay = createdDt[2].charAt(0) == 0 ? createdDt[2].split('')[1] : createdDt[2];

  _html = `<div class="col-6 col-lg-4 p-2">
  <!--single card-->
  <div class="card card-${options.type} h-100 d-flex flex-column" data-id="${type.reviewID}">
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
      <a href="${en4.core.baseUrl}reviews/view?reviewID=${type.reviewID}"
          class="d-block card-img--wrapper lazy"
          data-loader="asyncLoader" data-lazy-image="${type.coverPhoto.photoURL}">
      </a>
      ${options.type == 'review' ? renderRate(type.averageReviewRating, 5) : ''}
      ${options.type == 'guide' ? `<div class="prg-card--thumbs">
                                       <ul class="list-inline m-0 d-flex no-gutters justify-content-between">
                                        <li class="col-auto"><img class="" src="${type.coverPhoto.photoURL}" alt="${type.title}"/></li>
                                        <li class="col-auto"><img class="" src="${type.coverPhoto.photoURL}" alt="${type.title}"/></li>
                                        <li class="col-auto"><img class="" src="${type.coverPhoto.photoURL}" alt="${type.title}"/></li>
                                        <li class="col-auto"><img class="" src="${type.coverPhoto.photoURL}" alt="${type.title}"/></li>
                                        <li class="col-auto"><img class="" src="${type.coverPhoto.photoURL}" alt="${type.title}"/></li>
                                        <li class="col-auto"><img class="" src="${type.coverPhoto.photoURL}" alt="${type.title}"/></li>
                                       </ul>
                                    </div>` : ''}
    </div>
    <div class="card-body">
      <h6 class="card-subtitle my-1 text-primary font-weight-bold">${type.reviewCategorization.category}</h6>
      <a href="${en4.core.baseUrl}reviews/view?reviewID=${type.reviewID}"><h5 class="card-title font-weight-bold">
        ${type.title}</h5></a>
      <p class="card-text mb-0 d-none d-sm-block">${type.shortDescription}</p>
    </div>
    <div class="card-footer bg-white">
      <div class="row align-items-center justify-content-between flex-nowrap">
        <div class="col-auto col-sm">
          <div class="card-author">
            <div class="row no-gutters flex-nowrap align-items-center">
              <div class="col-auto d-none d-sm-block">
                <div class="card-author--avatar">${renderAvatar(type.author)}</div>
              </div>
              <div class="col">
                <a href="${en4.core.baseUrl}profile/${type.author.memberName}">
                  <h6 class="card-author--title mb-0"><b>${type.author.displayName}</b></h6>
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
              <span class="text-asphalt">${type.likesCount}</span>
            </li>
            <li class="flex-row align-items-center list-inline-item">
              <span class="fas fa-comments"></span>
              <span class="text-asphalt">${type.commentsCount}</span>
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
</div>`;

  return _html;
}

export {renderCard};
