const pkg = require('./package');
const merge = require('webpack-merge');
const webpack = require('webpack');
const webpackConfig = require('./webpack.config');
const proxyMiddleware = require('http-proxy-middleware');

module.exports = merge(webpackConfig, {
  devServer: {
    open: true,
    openPage: 'parentalguidance',
    port: 3000,
    headers: {
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE',
      'Access-Control-Allow-Headers': 'Content-Type, Content-Range, Content-Disposition, Content-Description'
    },
    before: function (app) {
      app.use('/parentalguidance/api', proxyMiddleware({
        target: pkg.config.API_PROXY,
        changeOrigin: true,
        pathRewrite: {
          '/parentalguidance/api': '/api'
        }
      }));
      app.use('/parentalguidance', proxyMiddleware({
        target: 'http://localhost:8888'
      }));
    }
  },
  devtool: 'inline-source-map'

});