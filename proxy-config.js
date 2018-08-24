const {API_PROXY, OAUTH} = require('./package');


module.exports = {
  target: API_PROXY,
  pathRewrite: {
    '^/api/v1': ''
  }
};
