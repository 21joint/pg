import './avatar.scss';

// Add options as parameter
function renderAvatar(author, options) {
  let _html = '';

  console.log(author);

  _html = `<div class="position-relative dropdown">
  <a href="#" role="button" id="avatar__${author.memberName}"
     data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
    <img class="rounded-circle" src="${author.avatarPhoto.photoURLProfile}"><b
    class="position-absolute d-flex justify-content-center align-items-center text-white bagde badge-primary rounded-circle ff-open--sans card-author--rank">${author.contributionLevel}</b></a>
  <div class="dropdown-menu" aria-labelledby="avatar__${author.memberName}">
    <div class="avatar-popup bg-white d-block">
      <div class="avatar-popup--header p-2 p-md-3 d-flex align-items-center">
        <div class="avatar-popup--content position-relative mr-3">
          <img class="rounded-circle" src="${author.avatarPhoto.photoURLProfile}" alt="${author.displayName}">
          <span class="position-absolute d-flex justify-content-center align-items-center text-white bagde badge-primary rounded-circle ff-open--sans card-author--rank">${author.contributionLevel}</span>
        </div>
        <div class="avatar-popup--info d-flex flex-column">
          <a href="/profile/${author.memberName}">
            <h6 class="avatar-author--displayName m-0">${author.displayName}</h6>
          </a>
          <div class="d-flex justify-content-start align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="14px"
                 height="14px">
              <use href="#prgBlueStarOnly"></use>
            </svg>
            <strong class="smaller ml-1 text-primary avatar-contribution">${author.contribution}</strong>
            <b class="mx-2">᛫</b>
            <span class="text-asphalt mx-2">Followers</span>
            <span class="text-primary">${author.followersCount}</span>
          </div>
        </div>
        <a role="button" class="avatar_close">
          <svg width="14px"
               xmlns="http://www.w3.org/2000/svg"
               viewBox="0 0 320 512"
               class="svg-inline--fa fa-times fa-w-10 fa-2x">
            <path fill="currentColor"
                  d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"
                  class=""></path>
          </svg>
        </a>
      </div>
      <div class="avatar-popup--body p-2 p-md-3">
        <ul class="prg-badges d-flex justify-content-center align-items-center p-0 m-0">
          <li class="prg-badge--single badge_bronze position-relative d-flex flex-column justify-content-center align-items-center mx-2 md-md-3">
            <img src="/images/Bronze.svg">
            <span class="number_badges position-absolute text-white font-weight-bold">${author.bronzeCount}</span>
            <span class="badge_name">Bronze</span>
          </li>
          <li class="prg-badge--single badge_silver position-relative d-flex flex-column justify-content-center align-items-center mx-2 md-md-3">
            <img src="/images/Silver.svg">
            <span class="number_badges position-absolute text-white font-weight-bold">${author.silverCount}</span>
            <span class="badge_name">Silver</span>
          </li>
          <li class="prg-badge--single badge_gold position-relative d-flex flex-column justify-content-center align-items-center mx-2 md-md-3">
            <img src="/images/Gold.svg"><span class="number_badges position-absolute text-white font-weight-bold">${author.goldCount}</span><span
            class="badge_name">Gold</span>
          </li>
          <li class="prg-badge--single badge_platinum position-relative d-flex flex-column justify-content-center align-items-center mx-2 md-md-3">
            <img src="/images/Platinum.svg">
            <span class="number_badges position-absolute text-white font-weight-bold">${author.platinumCount}</span>
            <span class="badge_name">Platinum</span>
          </li>
        </ul>
      </div>
      <div class="avatar-popup--footer p-2 p-md-3 d-flex justify-content-center align-items-center border-top text-asphalt">
        <div class="d-flex justify-content-center border-right">Reviews
          <span class="text-primary font-weight-bold ml-1">${author.reviewCount}</span></div>
        <div class="d-flex justify-content-center">Answers <span class="text-primary font-weight-bold ml-1">${author.answerCount}</span>
        </div>
      </div>
    </div>
  </div>
</div>`;

  return _html;
}

export {renderAvatar};
