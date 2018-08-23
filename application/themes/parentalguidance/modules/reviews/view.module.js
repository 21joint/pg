import { join } from 'lodash'
import '../../components/card/card.scss'
import { getReview } from '../../middleware/api.service'
import 'jquery-lazy'
import { renderProfileBox } from '../../components/profile-box/profile-box'
import { Gallery } from '../../components/gallery'
import { Rating } from '../../components/rating'

let component = function (review) {
  return `<div class="prg-review--single">${join(
    [hero(review), content(review)], '')}</div>`
}

let hero = function (review) {

  let heroImage = `
  <!-- SINGLE REVIEW HERO IMAGE -->
    <div class="prg-review--single---heroimage" style="background-color: #eeeeee;background-image: url(${review.coverPhoto.photoURL});">
      <img class="img-fluid w-100 invisible"
           src="${review.coverPhoto.photoURL}"
           alt="">
    </div>
  <!-- SINGLE REVIEW HERO IMAGE ///-->`
  let _rating = new Rating(review.averageReviewRating)
  let heroInner = `<!-- SINGLE REVIEW HERO INNER -->
    <div class="prg-review--single---heroinner">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
           ${_rating.render()}
          </div>
        </div>
      </div>
    </div>
    <!-- SINGLE REVIEW HERO INNER /// -->`

  return `<!-- SINGLE REVIEW HERO -->
  <div class="page-hero prg-review--single---hero position-relative pt-0">
    ${heroImage}
    ${heroInner}
  </div>
  <!--  SINGLE REVIEW HERO ///-->`

}

let content = function (review) {
  let gallery = new Gallery(review.reviewPhotos)

  return `<!--  SINGLE REVIEW CONTENT -->
<div class="prg-review--single---content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-8 col-xl-9">
        <div class="row">
          <div class="col-12">
            <div class="content-box">
              <!-- CARD ACTION  -->
              <div class="dropdown prg-review--single---action">
                <button class="btn btn-block bg-white rounded-circle p-0"
                        type="button"
                        id="dropdownMenuButton"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                  <i class="fa fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <a class="dropdown-item" href="#">Something else here</a>
                </div>
              </div>
              <!-- CARD ACTION -->
              <!--  BREADCRUMB-->
              <div class="prg-review--single---breadcrumb">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12">
                      <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent border-0 px-0">
                          <li class="breadcrumb-item align-middle"><a
                            href="#">Home</a></li>
                          <li class="breadcrumb-item align-middle"><a
                            href="#">Library</a>
                          </li>
                          <li class="breadcrumb-item align-middle active"
                              aria-current="page">Data
                          </li>
                        </ol>
                      </nav>
                    </div>
                  </div>
                </div>
              </div>
              <!--  BREADCRUMB ///-->
              <!-- TITLE -->
              <header class="prg-review--single---header">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-12">
                      <h2 class="prg-review--single---title text-primary font-weight-bold mb-3 mb-md-4 mb-lg-5">
                        ${review.title}</h2>
                    </div>
                  </div>
                </div>
              </header>
              <div class="prg-review--single---descr">
                <p>${review.longDescription}</p>
              </div>
              <!-- REVIEW GALLERY-->
              ${gallery.render()}
              <!-- REVIEW GALLERY ///-->
            </div>
          </div>
          <div class="col-12">
            <div class="row">
              <div class="col-6 py-3 border-bottom">
                <h6 class="font-weight-bold small py-3">Rate This Review</h6>
                <ul
                  class="prg-stars list-inline my-0 bg-transparent position-static px-0">
                  <li class="list-inline-item align-middle">
                    <a href="#">
                      <svg xmlns="http://www.w3.org/2000/svg">
                        <use href="#prgRateStar"></use>
                      </svg>
                    </a>
                  </li>
                  <li class="list-inline-item align-middle">
                    <a href="#">
                      <svg xmlns="http://www.w3.org/2000/svg">
                        <use href="#prgRateStar"></use>
                      </svg>
                    </a>
                  </li>
                  <li class="list-inline-item align-middle">
                    <a href="#">
                      <svg xmlns="http://www.w3.org/2000/svg">
                        <use href="#prgRateStar"></use>
                      </svg>
                    </a>
                  </li>
                  <li class="list-inline-item align-middle">
                    <a href="#">
                      <svg xmlns="http://www.w3.org/2000/svg">
                        <use href="#prgRateStar"></use>
                      </svg>
                    </a>
                  </li>
                  <li class="list-inline-item align-middle">
                    <a href="#">
                      <svg xmlns="http://www.w3.org/2000/svg">
                        <use href="#prgRateStar"></use>
                      </svg>
                    </a>
                  </li>
                </ul>
              </div>
              <div class="col-6 py-3 border-bottom">
                <h6 class="font-weight-bold small py-3">Rate This Product</h6>
                <ul
                  class="prg-stars list-inline my-0 bg-transparent position-static px-0">
                  <li class="list-inline-item align-middle">
                    <a href="#">
                      <svg xmlns="http://www.w3.org/2000/svg">
                        <use href="#prgRateStar"></use>
                      </svg>
                    </a>
                  </li>
                  <li class="list-inline-item align-middle">
                    <a href="#">
                      <svg xmlns="http://www.w3.org/2000/svg">
                        <use href="#prgRateStar"></use>
                      </svg>
                    </a>
                  </li>
                  <li class="list-inline-item align-middle">
                    <a href="#">
                      <svg xmlns="http://www.w3.org/2000/svg">
                        <use href="#prgRateStar"></use>
                      </svg>
                    </a>
                  </li>
                  <li class="list-inline-item align-middle">
                    <a href="#">
                      <svg xmlns="http://www.w3.org/2000/svg">
                        <use href="#prgRateStar"></use>
                      </svg>
                    </a>
                  </li>
                  <li class="list-inline-item align-middle">
                    <a href="#">
                      <svg xmlns="http://www.w3.org/2000/svg">
                        <use href="#prgRateStar"></use>
                      </svg>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-12 py-3 border-bottom">
            <div class="row justify-content-between">
              <div class="col-auto">
                <span>2 Comments</span>
              </div>
              <div class="col-auto">
                <div class="dropdown">Sorted By:
                  <a role="button"
                     id="commentsSortedBy"
                     class="text-asphalt font-weight-bold bg-transparent border-0 align-middle"
                     type="button"
                     aria-haspopup="true"
                     aria-expanded="false"
                     data-toggle="dropdown">Newest <i
                    class="fa fa-caret-down text-primary ml-2"></i>
                  </a>
                  <div style="overflow: hidden"
                       class="dropdown-menu dropdown-menu-right"
                       aria-labelledby="commentsSortedBy">
                    <ul class="list-unstyled p-0 m-0">
                      <li class="dropdown-item"><a role="button"
                                                   class="dropdown-link"
                                                   href="#">Newest</a></li>
                      <li class="dropdown-item"><a role="button"
                                                   class="dropdown-link"
                                                   href="#">Oldest</a></li>
                      <li class="dropdown-item"><a role="button"
                                                   class="dropdown-link"
                                                   href="#">Most Liked</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 py-3">
            <div class="prg-comments">
            <div class="row">
                <div class="col-12 py-4">
                 
            <div class="row">
              <div class="col-auto pr-0">
                  <div class="card-author--avatar">${renderProfileBox(
    review.author, {width: 50, height: 50})}</div>
              </div>
              <div class="col pl-0">
                <textarea title="<?= $this->translate('Join Discussion') ?>"
                          class="form-control border bg-white py-2 px-3"
                          id="prgJoinDiscussion" rows="3"
                          placeholder="Join the discussion..."></textarea>
              </div>
            </div>
          
                </div>
                <div class="col-12 py-4">
                  <ul class="pl-2">
                <li class="row prg-comment--single">
                  <div class="col-12">
                    <div class="prg-comment--renderer media border-bottom">
                      <div class="card-author--avatar">${renderProfileBox(
    review.author, {width: 50, height: 50})}</div>
                      <div class="media-body">
                        <h6 class="card-author--title mb-2"><b>Mandy Hall</b></h6>
                        <p class="prg-comment--text">I have loved my TMS bags since we got them almost two years
                          ago!</p>
                      </div>
                      <div class="dropdown prg-review--single---action">
                <button class="btn btn-block bg-white rounded-circle p-0"
                        type="button"
                        id="dropdownMenuButton1"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                  <i class="fa fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                  <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <a class="dropdown-item" href="#">Something else here</a>
                </div>
              </div>
                    </div>
                    <div class="prg-comment--replies---renderer media mt-3">

                      <div class="card-author--avatar">${renderProfileBox(
    review.author, {width: 50, height: 50})}</div>

                      <div class="media-body">
                        <h6 class="card-author--title mb-2"><b>Katie E.</b></h6>
                        <p class="prg-comment--text">This is such a beautiful bag! I love using mine when we go out even
                          when I don't have any of my kiddos with me!</p>

                      </div>
                    </div>
                  </div>
                </li>
              </ul>
                </div>
            </div>
              
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-xl-3">
        <aside class="prg-aside">
          <div style="min-height: auto;" class="content-box">
            <h6 class="pb-2 m-0 border-bottom">Related Reviews</h6>
            <ul class="list-unstyled pl-0 pt-3 m-0">
              <li>
                <a href="#">
                  <div class="card card-aside row no-gutters flex-row">
                    <div class="col-auto">
                      <div class="card-aside--img">
                        <img class="img-fluid"
                             src="https://via.placeholder.com/100x70" alt=" ">
                      </div>
                    </div>
                    <div class="col px-2 py-1">
                      <h6
                        class="card-aside--title font-weight-bold mb-0 small">
                        Gummee Molar Mallet</h6>
                      <p
                        class="card-aside--category small m-0 text-primary font-weight-bold">
                        Category</p>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <a href="#">
                  <div class="card card-aside row no-gutters flex-row">
                    <div class="col-auto">
                      <div class="card-aside--img">
                        <img class="img-fluid"
                             src="https://via.placeholder.com/100x70" alt=" ">
                      </div>
                    </div>
                    <div class="col px-2 py-1">
                      <h6
                        class="card-aside--title font-weight-bold mb-0 small">
                        Gummee Molar Mallet</h6>
                      <p
                        class="card-aside--category small m-0 text-primary font-weight-bold">
                        Category</p>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <a href="#">
                  <div class="card card-aside row no-gutters flex-row">
                    <div class="col-auto">
                      <div class="card-aside--img">
                        <img class="img-fluid"
                             src="https://via.placeholder.com/100x70" alt=" ">
                      </div>
                    </div>
                    <div class="col px-2 py-1">
                      <h6
                        class="card-aside--title font-weight-bold mb-0 small">
                        Gummee Molar Mallet</h6>
                      <p
                        class="card-aside--category small m-0 text-primary font-weight-bold">
                        Category</p>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <a href="#">
                  <div class="card card-aside row no-gutters flex-row">
                    <div class="col-auto">
                      <div class="card-aside--img">
                        <img class="img-fluid"
                             src="https://via.placeholder.com/100x70" alt=" ">
                      </div>
                    </div>
                    <div class="col px-2 py-1">
                      <h6
                        class="card-aside--title font-weight-bold mb-0 small">
                        Gummee Molar Mallet</h6>
                      <p
                        class="card-aside--category small m-0 text-primary font-weight-bold">
                        Category</p>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <a href="#">
                  <div class="card card-aside row no-gutters flex-row">
                    <div class="col-auto">
                      <div class="card-aside--img">
                        <img class="img-fluid"
                             src="https://via.placeholder.com/100x70" alt=" ">
                      </div>
                    </div>
                    <div class="col px-2 py-1">
                      <h6
                        class="card-aside--title font-weight-bold mb-0 small">
                        Gummee Molar Mallet</h6>
                      <p
                        class="card-aside--category small m-0 text-primary font-weight-bold">
                        Category</p>
                    </div>
                  </div>
                </a>
              </li>
            </ul>
          </div>
        </aside>
      </div>
    </div>
  </div>
</div>
<!--  SINGLE REVIEW CONTENT ///-->`
}

function init () {
  let revId = window.location.pathname.slice(
    window.location.pathname.lastIndexOf('/') + 1)
  getReview({
    id: revId,
    container: '[data-view="reviews-view"]',
  }, function (review) {

    console.info('got the review:', review)

    $('[data-view="reviews-view"]').html(component(review)).addClass('loaded')
    $('[data-lazy-image]').Lazy({
      effect: 'fadeIn',
      visibleOnly: false,
      asyncLoader: function (element, response) {
        setTimeout(function () {
          element.css({
            'background-image': 'url(' + element.data('lazyImage') + ')',
          })
          response(true)
        }, 300)
      },
    })
  })

}

$(document).ready(function () {
  init()
})

if (module.hot) {
  module.hot.accept()
}
