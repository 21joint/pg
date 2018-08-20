const {API_PROXY, OAUTH} = require('./package');

/**/
module.exports = {
  target: API_PROXY,
  pathRewrite: {
    '^/api/v1': ''
  }
};
/**/

//
// Replace it with following and save the file:
//
