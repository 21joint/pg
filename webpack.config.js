const path = require('path');
// const pkg = require('./package');
// const Conf = require('./conf');
const args = require('yargs').argv;
const glob = require('glob');
const webpack = require('webpack');
// const HtmlWebpackPlugin = require('html-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
// const CopyWebpackPlugin = require('copy-webpack-plugin');
const APP_DIR = path.resolve(__dirname, 'application');

let IS_DEV = process.env.NODE_ENV === 'dev';

/**
 * Webpack Configuration
 *
 **/

let config = {
  entry: {

  }
};


const getEntries = () =>
  glob.sync('application/themes/parentalguidance/**/*.module.js')
    .map(dir => new Promise(resolve => {
      resolve({
        dir.indexOf('/modules/') > -1 ?
        path.basename(dir).split('.')[0] :
        dir.slice(dir.lastIndexOf('/'))+ '_' + path.basename(dir).split('.')[0] : dir
      })
    }));


config.entry = getEntries();

config = {
  output: {
    filename: 'scripts/[name].bundle.js?[hash]',
    path: __dirname + '/dist'
  },
  module: {
    rules: [
      // JS
      {
        test: /\.(js|jsx)$/,
        include: /.module.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader'
      },
      // SCSS
      {
        test: /\.scss$/,
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
                data: '$prefix: ' + require('./conf').prefix + ';'
              }
            }
          ]
        })
      },
      // FONTS/IMAGES
      {
        test: /\.(woff|woff2|ttf|eot|otf|svg|gif|png|jp(e?)g)$/i,
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
              outputPath: './',
              publicPath: args.git ? '/gs-webpack/' : '/'
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new webpack.DefinePlugin({
      IS_DEV: IS_DEV
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery'
    }),
    new ExtractTextPlugin({
      filename: 'styles/[name].bundle.css?[hash]'
    })
  ]
};

if (!IS_DEV) {
  config.devtool = 'inline-source-map'; // source-map
} else {
  config.devtool = 'source-map'; // cheap-module-source-map
}
console.dir('Running: ' + IS_DEV + ' mode.');

module.exports = config;
