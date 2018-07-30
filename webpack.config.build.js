const pkg = require('./package');
const path = require('path');
const merge = require('webpack-merge');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const webpackConfig = require('./webpack.config');
const args = require('yargs').argv;
const Modules = require('./conf').modules;


module.exports = merge(webpackConfig, {
  output: {
    filename: 'scripts/[name].[contenthash].js',
    path: Modules.prg.dist
  },
  plugins: [
    new CleanWebpackPlugin([Modules.prg.dist]),
  ],
  devtool: 'source-map'
});