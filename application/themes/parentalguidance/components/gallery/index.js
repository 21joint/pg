class Gallery {
  constructor(items) {
    this.items = items;
    this.render();
  }

  static render(items, options) {
    let _html = '';

    _html += `<ul class="d-flex flex-wrap list-unstyled m-0">`;

    for (let j = 0; j < items.length; ++j) {
      let _photo = items[j];
      _html += `<li class="col-4 p-2">
                  <div class="embed-responsive embed-responsive-1by1">
                    <img class="embed-responsive-item lazy" 
                          data-lazy-image="${this.buildItem(_photo)}" 
                          data-loader="asyncLoader" 
                          alt="">
                  </div>
                </li>`;
    }

    _html += `</ul>`;

    return _html;
  }

  static buildItem(photo) {
    return `<li class="col-4 p-2">
              <div class="embed-responsive embed-responsive-1by1">
                <img class="embed-responsive-item lazy" 
                      data-lazy-image="${photo.photoURL}" 
                      data-loader="asyncLoader" 
                      alt="Photo" />
              </div>
            </li>`;
  }
}

export {Gallery}

