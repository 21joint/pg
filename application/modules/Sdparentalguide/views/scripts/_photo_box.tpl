<?php $subject = $this->subject; ?>

<div class="dropdown-menu" aria-labelledby="prgProfilePopup">
  <div class="profile-popup bg-white text-asphalt">
    <div class="profile-popup--header">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-auto py-2 py-md-3">
            <div class="profile-popup--author position-relative">
              <span style="width:50px;height:50px"
                    data-loader="asyncLoader"
                    class="d-block rounded-circle lazy"
                    data-lazy-image="<?= $subject->getPhotoUrl(); ?>"></span><b class="bagde badge-primary text-white d-flex justify-content-center align-items-center rounded-circle ff-open--sans position-absolute profile-box--level"><?= $subject->gg_contribution_level; ?></b>
            </div>
          </div>
          <div class="col py-2 py-md-3 pl-0">
            <div class="row">
              <div class="col-12">
                <a href="<?= $subject->getHref() ?>">
                  <h6 class="avatar-author--displayName mb-1"><?= $subject->getTitle() ?></h6>
                </a>
              </div>
            </div>
            <div class="row align-items-center">
              <div class="col-auto">
                <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px">
                  <use href="#prgBlueStarOnly"></use>
                </svg>
                <strong class="avatar-contribution align-middle smaller ml-1 text-primary"><?= $subject->gg_contribution; ?></strong>
              </div>
              <div class="col-auto pl-0">Followers
                <span class="ml-1 text-primary"><?= $subject->gg_followers_count; ?></span>
              </div>
            </div>
          </div>
          <div class="col-auto align-self-start p-0">
            <a role="button" class="profile-popup--close">
              <svg width="14px"
                   xmlns="http://www.w3.org/2000/svg"
                   viewBox="0 0 320 512"
                   class="svg-inline--fa fa-times fa-w-10 fa-2x">
                <path fill="currentColor"
                      d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"></path>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="profile-popup--body py-2 py-md-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <ul class="prg-badges d-flex justify-content-center align-items-center p-0 m-0">
              <li class="position-relative d-flex flex-column justify-content-center align-items-center mx-2 mx-md-3">
                <div class="d-flex align-items-center justify-content-center prg-badge prg-badge--bronze mb-2">
                  <span class="number_badges position-absolute text-white font-weight-bold"> <?= $subject->gg_bronze_count; ?></span>
                </div>
                <div class="prg-badge--title text-asphalt"><?= $this->translate('Bronze') ?></div>
              </li>
              <li class="position-relative d-flex flex-column justify-content-center align-items-center mx-2 mx-md-3">
                <div class="d-flex align-items-center justify-content-center prg-badge prg-badge--silver mb-2">
                  <span class="number_badges position-absolute text-white font-weight-bold"> <?= $subject->gg_silver_count; ?></span>
                </div>
                <div class="prg-badge--title text-asphalt"><?= $this->translate('Silver') ?></div>
              </li>
              <li class="position-relative d-flex flex-column justify-content-center align-items-center mx-2 mx-md-3">
                <div class="d-flex align-items-center justify-content-center prg-badge prg-badge--gold mb-2">
                  <span class="number_badges position-absolute text-white font-weight-bold"> <?= $subject->gg_gold_count; ?></span>
                </div>
                <div class="prg-badge--title text-asphalt"><?= $this->translate('Gold') ?></div>
              </li>
              <li class="position-relative d-flex flex-column justify-content-center align-items-center mx-2 mx-md-3">
                <div class="d-flex align-items-center justify-content-center prg-badge prg-badge--platinum mb-2">
                  <span class="number_badges position-absolute text-white font-weight-bold"> <?= $subject->gg_platinum_count; ?></span>
                </div>
                <div class="prg-badge--title text-asphalt"><?= $this->translate('Platinum') ?></div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="profile-popup--footer">
      <div class="row no-gutters border-top text-asphalt">
        <div class="col p-2 p-md-3 text-center">Reviews
          <b class="text-primary ml-1"><?= $subject->gg_review_count; ?></b></div>
        <div class="col p-2 p-md-3 text-center">Answers
          <b class="text-primary ml-1"><?= $subject->gg_answer_count; ?></b></div>
      </div>
    </div>
  </div>
</div>
