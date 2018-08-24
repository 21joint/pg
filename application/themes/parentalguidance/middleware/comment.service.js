import {API_PROXY, OAUTH} from "../../../../package";

// Get comments

function getComments(params, callback) {
  const url = API_PROXY + '/comment?' + OAUTH + '&contentID=' + params.contentID + '&contentType=' + params.contentType;
  let _comments = [];

  jQuery.ajax({
    method: 'GET',
    url: url,
    dataType: 'json',
    success: function (res) {
      callback(res.body);
    },
    error: function (error) {
      console.error(error);
    },
    complete: function () {
      // $(opts.container).addClass('loaded');
    }
  })
}

export {getComments};
