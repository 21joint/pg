const pkg = require('./package');
const path = require('path');
const merge = require('webpack-merge');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const webpackConfig = require('./webpack.config');
const args = require('yargs').argv;
const Modules = require('./conf').modules;


module.exports = merge(webpackConfig, {
  target: 'web',
  output: {
    filename: 'scripts/[name].bundle.js'
  },
  plugins: [
    new CleanWebpackPlugin([Modules.prg.dist]),
    new ExtractTextPlugin({
      filename: 'styles/[name].bundle.css'
    })
  ]
});