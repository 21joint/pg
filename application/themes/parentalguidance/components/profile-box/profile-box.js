import './profile-box.scss'
import { renderBadges } from '../badges/badges.js'

function component (author, options) {
  const _defaults = {
    width: 40,
    height: 40,
  }

  const _options = $.extend(_defaults, options, {})

  let element = document.createElement('div')

  let _html = ''

  // console.log(author);

  // language=HTML
  _html = `<div class="position-relative dropdown">
              <a href="#"
                  class="${author.mvp ? 'mvp' : 'mvp'}"
                  role="button" 
                  id="avatar__${author.memberID}"
                  data-toggle="dropdown"
                  data-baundary="window"
                  aria-expanded="false" 
                  aria-haspopup="true"><span style="width: ${_options.width}px;height:${_options.height}px" class="d-block rounded-circle lazy" data-lazy-image="${author.avatarPhoto.photoURLProfile}" data-loader="asyncLoader"></span><b class="bagde badge-primary text-white d-flex justify-content-center align-items-center rounded-circle ff-open--sans position-absolute profile-box--level">${author.contributionLevel}</b></a>
              <div class="dropdown-menu" aria-labelledby="avatar__${author.memberID}">
                <div class="profile-popup bg-white text-asphalt">
                  <div class="profile-popup--header">
                    <div class="container-fluid">
                      <div class="row align-items-center">
                        <div class="col-auto py-2 py-md-3">
                         <div class="profile-popup--author position-relative">
                              <span style="width: ${_options.width}px;height:${_options.height}px" data-loader="asyncLoader" class="d-block rounded-circle lazy" data-lazy-image="${author.avatarPhoto.photoURLProfile}"></span><b class="bagde badge-primary text-white d-flex justify-content-center align-items-center rounded-circle ff-open--sans position-absolute profile-box--level">${author.contributionLevel}</b>                
                          </div>
                        </div>
                        <div class="col py-2 py-md-3 pl-0">
                          <div class="row">
                            <div class="col-12">
                              <a href="/profile/${author.memberName}">
                                <h6 class="avatar-author--displayName mb-0">${author.displayName}</h6>
                              </a>
                            </div>
                          </div>
                          <div class="row align-items-center">
                            <div class="col-auto">
                              <svg xmlns="http://www.w3.org/2000/svg"
                                   width="14px"
                                   height="14px">
                                <use href="#prgBlueStarOnly"></use>
                              </svg>
                              <strong class="avatar-contribution align-middle smaller ml-1 text-primary">${author.contribution}</strong>
                            </div>
                            <div class="col-auto pl-0">Followers <span class="ml-1 text-primary">${author.followersCount}</span>
                            </div>
                          </div>
                        </div>
                        <div class="col-auto align-self-start p-0">
                        <a role="button" class="profile-popup--close">
                          <svg width="14px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="svg-inline--fa fa-times fa-w-10 fa-2x"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"></path></svg>
                        </a>
</div>
                      </div>
                    </div>
                  </div>
                  <div class="profile-popup--body py-2 py-md-3">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-12">
                          ${renderBadges(author)}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="profile-popup--footer">
                    <div class="row no-gutters border-top text-asphalt">
                      <div class="col p-2 p-md-3 text-center">Reviews <b class="text-primary ml-1">${author.reviewCount}</b></div>
                      <div class="col p-2 p-md-3 text-center">Answers <b class="text-primary ml-1">${author.answerCount}</b></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>`

  element.innerHTML = _html

  return options.element ? element : _html
}

// Add options as parameter
function renderProfileBox (author, options) {

  return component(author, options)
}

export { renderProfileBox }
