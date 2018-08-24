const path = require('path');
const webpack = require('webpack');
const merge = require('webpack-merge');
const {API_PROXY, OAUTH} = require('./package');
const webpackConfig = require('./webpack.config');
const entry = require('./webpack.entries');

let config = merge(webpackConfig, {
  devServer: {
    proxy: {
      '*': {
        target: 'http://localhost:9000'
      },
      '/api/v1': {
        target: API_PROXY,
        changeOrigin: true,
        pathRewrite: {
          '^/api/v1': ''
        }
      }
    },
    contentBase: [
      path.join(__dirname, 'application'),
      path.join(__dirname, 'dist')
    ],
    publicPath: '/',
    port: 2121,
    open: true,
    hot: true
  },
  plugins: [
    new webpack.NamedModulesPlugin(),
    new webpack.HotModuleReplacementPlugin()
  ]
});


console.log(config.devServer.headers);

config.entry = entry;
module.exports = config;
