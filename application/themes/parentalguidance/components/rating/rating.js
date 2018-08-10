import './rating.scss';

const renderRate = function (rating, maxRate) {
  let html = '<div class="prg-stars"><ul class="list-inline m-0">';
  for (let j = 0; j < maxRate; j++) {
    html += `<li class="list-inline-item align-middle">
        <svg style="opacity: ${ maxRate - rating >= j + 1 ? '0.4' : '1'}" class="card-star--icon">
          <use href="#prgRateStar"></use>
        </svg>
      </li>`
  }
  html += '</ul></div>';
  return html
};

const renderRateInput = function (maxRate) {
  const _token = new Date().getTime();
  return `<ul class="row no-gutters align-items-center">
              <li class="col-auto">
                <input type="radio" id="rateInput1" name="prgRateInput__${_token}" />
                <label for="rateInput1"><svg class="star-icon"><use href="#prgRateStar"></use></svg></label>
              </li>
              <li class="col-auto">
                <input type="radio" id="rateInput2" name="prgRateInput__${_token}" />
                <label for="rateInput2"><svg class="star-icon"><use href="#prgRateStar"></use></svg></label>
              </li>
              <li class="col-auto">
                <input type="radio" id="rateInput3" name="prgRateInput__${_token}" />
                <label for="rateInput3"><svg class="star-icon"><use href="#prgRateStar"></use></svg></label>
              </li>
              <li class="col-auto">
                <input type="radio" id="rateInput4" name="prgRateInput__${_token}" />
                <label for="rateInput4"><svg class="star-icon"><use href="#prgRateStar"></use></svg></label>
              </li>
              <li class="col-auto">
                <input type="radio" id="rateInput5" name="prgRateInput__${_token}" />
                <label for="rateInput5"><svg class="star-icon"><use href="#prgRateStar"></use></svg></label>
              </li>
          </ul>`;
};

export {renderRate, renderRateInput};
