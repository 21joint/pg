const {API_PROXY, OAUTH} = require('./package');
const webpack = require('webpack');
const merge = require('webpack-merge');
const webpackConfig = require('./webpack.config');

console.log(__dirname);

module.exports = merge(webpackConfig, {
  devServer: {
    proxy: {
      '*': {
        target: 'http://0.0.0.0:8888',
        secure: false,
        changeOrigin: false
      },
      '/api/v1': {
        target: API_PROXY,
        changeOrigin: true,
        pathRewrite: {
          '^/api/v1': ''
        }
      }
    },
    historyApiFallback: true,
    port: 2112,
    open: true,
    host: '0.0.0.0',
    headers: {
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'DELETE, HEAD, GET, OPTIONS, POST, PUT',
      'Access-Control-Allow-Headers': 'Content-Type, Content-Range, Content-Disposition, Content-Description'
    },
    // before: function (app) {
    //
    //   app
    //     .use('^/api/v1', proxyMiddleware({
    //       target: pkg.config.API_PROXY,
    //       changeOrigin: true,
    //       pathRewrite: {
    //         '^/api/v1': '/'
    //       }
    //     }))
    //     .use('**', proxyMiddleware({
    //       target: 'http://localhost:8888',
    //       changeOrigin: false
    //     }))
    // }
  }
});
