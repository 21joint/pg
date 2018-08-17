import './leader.scss';

// Add options as parameter
function renderLeader(leader, options) {
  let _html = '';

  _html = `<div class="leader leader-${options.type ? options.type : ''}"><h1>${leader.memberName}</h1></div>`;

  return _html;
}

export {renderLeader};
