const merge = require('webpack-merge');
const webpack = require('webpack');
const webpackConfig = require('./webpack.config');
var proxy = require('http-proxy-middleware');

module.exports = merge(webpackConfig, {
  devServer: {
    open: true,
    port: 3000,
    headers: {

    },
    proxy: {
      '/': {
        target: 'http://localhost:8888',
      },
    }
  },
  devtool: 'inline-source-map'

});