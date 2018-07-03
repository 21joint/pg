const merge = require('webpack-merge');
const webpack = require('webpack');
const webpackConfig = require('./webpack.config');

module.exports = merge(webpackConfig, {
  devServer: {
    open: true,
    port: 3000,
    headers: {
      'Access-Control-Allow-Origin' : '*',
      'Access-Control-Allow-Methods': 'DELETE, HEAD, GET, OPTIONS, POST, PUT',
      'Access-Control-Allow-Headers': 'Content-Type, Content-Range, Content-Disposition, Content-Description'
    },
    proxy: {
      '*': {
        target: 'http://localhost:8888',
      },
    }
  },
  devtool: 'inline-source-map'

});