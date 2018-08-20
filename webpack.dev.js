const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const {API_PROXY} = require('./package');
const webpackConfig = require('./webpack.config');
webpackConfig.entry = require('./webpack.entries');


const fs = require('fs');
// our setup function adds behind-the-scenes bits to the config that all of our
// examples need
const {setup} = require('util');
const proxyConfig = require('./proxy-config');

let proxyOptions = [
  {
    context: '*',
    target: 'http://localhost:8888/',
  },
  {
    context: '/api/v1',
    target: proxyConfig.target,
    pathRewrite: proxyConfig.pathRewrite,
    changeOrigin: true
  }
];

fs.watch('./proxy-config.js', () => {
  delete require.cache[require.resolve('./proxy-config')];
  try {
    const newProxyConfig = require('./proxy-config');
    if (proxyOptions.target !== newProxyConfig.target) {
      console.log('Proxy target changed:', newProxyConfig.target);
      proxyOptions = {
        context: '/api/v1',
        target: newProxyConfig.target,
        pathRewrite: newProxyConfig.pathRewrite,
        changeOrigin: true
      };
    }
  } catch (e) {
    // eslint-disable-line
  }
});


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
    contentBase: path.resolve(__dirname, 'application/themes/parentalguidance/dist'),
    publicPath: '/',
    port: 3000,
    open: true,
    headers: {
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'DELETE, HEAD, GET, OPTIONS, POST, PUT',
      'Access-Control-Allow-Headers': 'Content-Type, Content-Range, Content-Disposition, Content-Description'
    }
  }
});


module.exports = config;
