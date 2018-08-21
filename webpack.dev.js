const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const {API_PROXY} = require('./package');
const webpackConfig = require('./webpack.config');
webpackConfig.entry = require('./webpack.entries');

let config = merge(webpackConfig, {
  devServer: {
    proxy: {
      '*': {
        target: 'http://localhost:8888',
        secure: false
      },
      '/api/v1': {
        target: API_PROXY,
        changeOrigin: true,
        pathRewrite: {
          '^/api/v1': ''
        }
      }
    },
    contentBase: path.join(__dirname, 'dist'),
    publicPath: '/',
    port: 2121,
    open: true,
    headers: {
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'DELETE, HEAD, GET, OPTIONS, POST, PUT',
      'Access-Control-Allow-Headers': 'Content-Type, Content-Range, Content-Disposition, Content-Description'
    }
  }
});

module.exports = config;
