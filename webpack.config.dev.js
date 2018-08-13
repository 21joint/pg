const {API_PROXY, OAUTH} = require('./package');
const webpack = require('webpack');
const merge = require('webpack-merge');
const webpackConfig = require('./webpack.config');

console.log(__dirname);

module.exports = merge(webpackConfig, {
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
    historyApiFallback: true,
    publicPath: 'http://localhost:8888/',
    port: 2121,
    hot: true,
    open: true,
  },
  plugins: [
    new webpack.HotModuleReplacementPlugin()
  ]
});
