import './rating.scss';

export class Rating {
  constructor(rating) {
    this.rating = parseFloat(rating);
  }

  addStar() {
    let html = '';
    for (let j = 0; j < this.rating; j++) {
      html += `<li class="col">
              <svg class="card-star--icon">
                <use href="#prgRateStar"></use>
              </svg>
            </li>`
    }
    return html;
  }

  render() {
    return `<div class="prg-stars">
                  <ul class="row no-gutters align-items-center flex-nowrap p-0 m-0">
                  ${this.addStar(this.rating)}
                  </ul>
                </div>`;
  };

  renderRateInput() {
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
}
