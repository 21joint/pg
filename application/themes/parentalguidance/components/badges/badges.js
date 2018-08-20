import './badges.scss';

function renderBadges(user) {
  return `<ul class="prg-badges d-flex justify-content-center align-items-center p-0 m-0">
            <li class="position-relative d-flex flex-column justify-content-center align-items-center mx-2 mx-md-3">
              <div class="d-flex align-items-center justify-content-center prg-badge prg-badge--bronze mb-2">
                <span class="number_badges position-absolute text-white font-weight-bold">${user.bronzeCount}</span>
              </div>
              <div class="prg-badge--title text-asphalt">Bronze</div>
            </li>
            <li class="position-relative d-flex flex-column justify-content-center align-items-center mx-2 mx-md-3">
              <div class="d-flex align-items-center justify-content-center prg-badge prg-badge--silver mb-2">
                <span class="number_badges position-absolute text-white font-weight-bold">${user.silverCount}</span>
              </div>
              <div class="prg-badge--title text-asphalt">Silver</div>
            </li>
            <li class="position-relative d-flex flex-column justify-content-center align-items-center mx-2 mx-md-3">
              <div class="d-flex align-items-center justify-content-center prg-badge prg-badge--gold mb-2">
                <span class="number_badges position-absolute text-white font-weight-bold">${user.goldCount}</span>
              </div>
              <div class="prg-badge--title text-asphalt">Gold</div>
            </li>
            <li class="position-relative d-flex flex-column justify-content-center align-items-center mx-2 mx-md-3">
              <div class="d-flex align-items-center justify-content-center prg-badge prg-badge--platinum mb-2">
                <span class="number_badges position-absolute text-white font-weight-bold">${user.platinumCount}</span>
              </div>
              <div class="prg-badge--title text-asphalt">Platinum</div>
            </li>
          </ul>`
}

export default {renderBadges}
