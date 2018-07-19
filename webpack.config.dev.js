const pkg = require('./package');
const merge = require('webpack-merge');
const webpack = require('webpack');
const proxyMiddleware = require('http-proxy-middleware');
const cors = require('cors');
const webpackConfig = require('./webpack.config');

module.exports = merge(webpackConfig, {
  devServer: {
    port: 2121,
    headers: {
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'DELETE, HEAD, GET, OPTIONS, POST, PUT',
      'Access-Control-Allow-Headers': 'Content-Type, Content-Range, Content-Disposition, Content-Description'
    },
    before: function (app) {

      app.use('^/parentalguidance/api', proxyMiddleware({
        target: pkg.config.API_PROXY,
        changeOrigin: true,
        pathRewrite: {
          '^/parentalguidance/api': '/api'
        },
        logLevel: 'debug'
      }));
      app.use('^/parentalguidance', proxyMiddleware({
        target: 'http://localhost:8888/',
        logLevel: 'debug'
      }));
    }
  },
  plugins: [
    new webpack.HotModuleReplacementPlugin({})
  ]
});