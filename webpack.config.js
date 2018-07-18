const path = require('path');
const pkg = require('./package');
const Conf = require('./conf');
const args = require('yargs').argv;
const glob = require('glob');
const webpack = require('webpack');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
// const CopyWebpackPlugin = require("copy-webpack-plugin");
const Modules = Conf.modules;


const IS_DEV = (process.env.NODE_ENV === 'dev');

/**
 * Webpack Configuration
 */

module.exports = {
  entry: {
    prg: [
      './application/modules/Sdparentalguide/index.js'
    ]
  },
  output: {
    filename: 'scripts/[name].bundle.js',
    publicPath: '/',
    path: Modules.prg.dist
  },
  module: {
    rules: [
      // JS
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: [
          'babel-loader'
        ]
      },

      // SCSS
      {
        test: /\.s?css$/,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          use: [
            {
              loader: 'css-loader',
              options: {
                minimize: !IS_DEV,
                sourceMap: IS_DEV,
                publicPath: '/'
              }
            },
            {
              loader: 'postcss-loader',
              options: {
                sourceMap: IS_DEV,
                publicPath: '/',
                plugins: [
                  require('postcss-flexbugs-fixes'),
                  require('autoprefixer')({
                    browsers: ['last 3 versions']
                  })
                ]
              }
            },
            {
              loader: 'sass-loader',
              options: {
                sourceMap: IS_DEV,
                data: "$prefix: " + require('./conf').prefix + ";"
              }
            }
          ]
        })
      },

      // FONTS/IMAGES
      {
        test: /\.(woff|woff2|ttf|eot|otf|svg|gif|png|jpe?g)$/i,
        use: [
          {
            loader: 'url-loader',
            options: {
              limit: 1024,
              name(file) {
                if (file.indexOf('fonts') > -1) {
                  return 'fonts/[name].[ext]';
                }
                else {
                  return 'images/[name].[ext]';
                }
              },
              fallback: 'file-loader',
              outputPath: './'
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new webpack.DefinePlugin({
      IS_DEV
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery'
    }),
    new HtmlWebpackPlugin({
      template: './application/modules/Sdparentalguide/widgets/header/index.tpl'
    }),
    // new HtmlWebpackPlugin({
    //   template: './application/modules/Sdparentalguide/widgets/reviews-view/index.tpl'
    // }),
    // new HtmlWebpackPlugin({
    //   template: './application/modules/Sdparentalguide/widgets/reviews-create/index.tpl'
    // }),
    // new HtmlWebpackPlugin({
    //   template: './application/modules/Sdparentalguide/widgets/featured-reviews/index.tpl'
    // }),
    new ExtractTextPlugin({
      filename: 'styles/[name].bundle.css'
    })
  ]
};
