const {API_PROXY, OAUTH} = require('./package');
const conf = require('./conf');
const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const webpackConfig = require('./webpack.config');
const proxyMiddleware = require('http-proxy-middleware');

console.log(__dirname);

module.exports = merge(webpackConfig, {
  devServer: {
    contentBase: './dist',
    proxy: {
      '*': {
        target: 'http://127.0.0.1:8888',
        secure: false,
        changeOrigin: true
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
    port: 2121,
    open: true,
    hot: true,
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
  },
  plugins: [
    new webpack.HotModuleReplacementPlugin()
  ]
});