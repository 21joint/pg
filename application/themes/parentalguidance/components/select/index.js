import 'select2';

export class Select {

  constructor(options) {
    this.options = options;
  }

  render() {
    return `<select style="width:100%">
              ${ this.renderOptions() }
            </select>`
  }

  renderOptions() {
    let _options = `<option>Select Something</option>`;
    $.each(this.options, (i, option) => {
      console.log(i, option);
      _options += `<option value="${option.value}">${option.title}</option>`
    })
  }
}
