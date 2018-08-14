import './avatar.scss';

// Add options as parameter
function renderAvatar(author, options) {
  let _html = '';

  _html = `<div class="author-avatar position-relative">
    <a href="#" role="button" id="avatar__${new Date().getTime()}__${author.memberName}"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <img class="rounded-circle" src="${author.avatarPhoto.photoURLProfile}"><b
      class="position-absolute d-flex justify-content-center align-items-center text-white bagde badge-primary rounded-circle ff-open--sans card-author--rank">${author.contributionLevel}</b></a>
    <div class="dropdown-menu" aria-labelledby="avatar__${new Date().getTime()}__${author.memberName}">
      <div class="avatar_popup bg-white d-block">
        <div class="avatar_header d-flex align-items-center px-3 pt-3">
          <div class="author-avatar position-relative mr-2">
            <img class="rounded-circle" src="${author.avatarPhoto.photoURLProfile}" alt="${author.displayName}">
            <b class="position-absolute d-flex justify-content-center align-items-center text-white bagde badge-primary rounded-circle ff-open--sans card-author--rank">${author.contributionLevel}</b>
          </div>
          <div class="avatar_info d-flex flex-column">
            <a class="font-weight-bold" href="/profile/${author.displayName}">${author.displayName}</a>
            <div class="d-flex justify-content-start align-items-center">
              <svg xmlns="http://www.w3.org/2000/svg"
                   width="20px"
                   height="20px"
                   viewBox="0 0 42.03 39.91">
                <defs>
                  <linearGradient id="a" x1="26.26" y1="12.68" x2="40.67" y2="12.68" gradientUnits="userSpaceOnUse">
                    <stop offset="0" stop-color="#51b2b6"></stop>
                    <stop offset="1" stop-color="#5bc6cd"></stop>
                  </linearGradient>
                  <linearGradient id="b" y1="17.32" x2="17.39" y2="17.32" gradientUnits="userSpaceOnUse">
                    <stop offset="0" stop-color="#5bc6cd"></stop>
                    <stop offset="1" stop-color="#51b2b6"></stop>
                  </linearGradient>
                </defs>
                <title>star_pg</title>
                <path d="M40.23,8.55,32.7,18.46l-6.44-8.6L38.77,7C40.61,6.57,41.14,7.31,40.23,8.55Z"
                      fill="#5bc6cd"></path>
                <path d="M17.39,12,.93,16.13c-1,.24-1.28,1.35-.32,1.79l16.06,4.7Z" fill="#5bc6cd"></path>
                <path d="M15.31,38.4,17.42,1c0-1.06,1.1-1.31,1.76-.45L41.59,28.45c.83,1,.6,2.71-1.71,1.81L26.36,25.09l-8.44,14A1.36,1.36,0,0,1,15.31,38.4Z"
                      fill="#5bc6cd"></path>
              </svg>
              1703
            </div>
          </div>
          <a role="button" class="avatar_close"><svg width="14px" aria-hidden="true" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-times fa-w-10 fa-2x"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" class=""></path> </svg></a>
        </div>
        <div class="avatar_badges d-flex justify-content-around align-items-center my-2 px-3">
          <div
            class="avatar_badges_popup badge_bronze position-relative d-flex flex-column justify-content-center align-items-center">
            <img src="/images/Bronze.svg"><span
            class="number_badges position-absolute text-white font-weight-bold">4</span><span
            class="badge_name">Bronze</span></div>
          <div
            class="avatar_badges_popup badge_silver position-relative d-flex flex-column justify-content-center align-items-center">
            <img src="/images/Silver.svg"><span
            class="number_badges position-absolute text-white font-weight-bold">3</span><span
            class="badge_name">Silver</span></div>
          <div
            class="avatar_badges_popup badge_gold position-relative d-flex flex-column justify-content-center align-items-center">
            <img src="/images/Gold.svg"><span
            class="number_badges position-absolute text-white font-weight-bold">2</span><span
            class="badge_name">Gold</span></div>
          <div
            class="avatar_badges_popup badge_platinum position-relative d-flex flex-column justify-content-center align-items-center">
            <img src="/images/Platinum.svg"><span
            class="number_badges position-absolute text-white font-weight-bold">0</span><span
            class="badge_name">Platinum</span></div>
        </div>
        <div class="avatar_footer d-flex justify-content-center align-items-center border-top text-asphalt">
          <div class="d-flex justify-content-center p-3 border-right">Reviews <span
            class="text-primary font-weight-bold ml-1">187</span></div>
          <div class="d-flex justify-content-center p-3">Answers <span class="text-primary font-weight-bold ml-1">0</span>
          </div>
        </div>
      </div>
    </div>
  </div>`;

  return _html;
}

export {renderAvatar};
