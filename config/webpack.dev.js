const {API_PROXY, OAUTH} = require('../package');
const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const webpackConfig = require('../webpack.config');
// const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

const webpackDevMiddleware = require('webpack-dev-middleware');


const config = merge(webpackConfig, {
  devServer: {
    proxy: {
      '*': {
        target: 'http://localhost:8888'
      },
      '/api/v1': {
        target: API_PROXY,
        changeOrigin: true,
        pathRewrite: {
          '^/api/v1': ''
        }
      }
    },
    contentBase: path.resolve(__dirname, '../dist'),
    publicPath: '/',
    port: 3030,
    open: true,
    headers: {
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'DELETE, HEAD, GET, OPTIONS, POST, PUT',
      'Access-Control-Allow-Headers': 'Content-Type, Content-Range, Content-Disposition, Content-Description'
    },
    before: (app) => {
      app.use(require("webpack-dev-middleware")(compiler, {
        noInfo: true, publicPath: webpackConfig.output.publicPath
      }));
      app.use(require("webpack-hot-middleware")(compiler));
    },
  }
});


const compiler = webpack(config);

console.log(__dirname);

module.exports = config;
