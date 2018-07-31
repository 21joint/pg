const path = require('path');
const pkg = require('./package');
const Conf = require('./conf');
const args = require('yargs').argv;
const glob = require('glob');
const webpack = require('webpack');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
// const CopyWebpackPlugin = require('copy-webpack-plugin');
const Modules = Conf.modules;


let environment = process.env.NODE_ENV === 'dev' ? 'development' : 'production';

let BUILD_DIR = path.resolve(__dirname, './public');
let APP_DIR = path.resolve(__dirname, 'application');

/**
 * Webpack Configuration
 */

const config = {
  entry: {
    index: APP_DIR + '/themes/parentalguidance/index.js'
  },
  output: {
    filename: 'scripts/[name].build.js',
    path: __dirname + '/application/themes/parentalguidance'
  },
  module: {
    rules: [
      // JS
      {
        test: /\.(js|jsx)$/,
        exclude: /(node_modules|bower_components)/,
        loader: 'babel-loader'
      },
      // IMAGES
      {
        test: /\.(png|jpg|gif|svg|ico)$/,
        loader: 'file-loader',
        options: {
          name: '[name].[ext]?[hash]'
        }
      },
      // SCSS
      {
        test: /\.(scss|css)$/,
        use: ExtractTextPlugin.extract({
          fallback: 'style-loader',
          use: [
            {
              loader: 'css-loader',
              options: {
                minimize: !environment,
                sourceMap: environment,
                publicPath: '/'
              }
            },
            {
              loader: 'postcss-loader',
              options: {
                sourceMap: environment,
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
                sourceMap: environment,
                data: '$prefix: ' + require('./conf').prefix + ';'
              }
            }
          ]
        })
      },
      {
        test: /\.(svg|ttf|woff|woff2|eot)$/,
        loader: 'url-loader?limit=5000',
        options: {
          name: 'fonts/[name].[ext]?[hash]'
        }
      }
    ]
  },
  // optimization: {
  //   splitChunks: {
  //     cacheGroups: {
  //       vendors: {
  //         test: /[\\/]node_modules[\\/]/,
  //         name: 'vendors',
  //         enforce: true,
  //         chunks: 'all'
  //       }
  //     }
  //   }
  // },
  plugins: [
    new webpack.DefinePlugin({
      IS_DEV: environment,
      'process.env': {
        NODE_ENV: JSON.stringify(environment)
      }
    }),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery'
    }),
    new ExtractTextPlugin({
      filename: '/styles/[name].css'
    }),
    new HtmlWebpackPlugin({
      template: __dirname + '/application/themes/parentalguidance/head.tpl',
      title: 'Head',
      filename: 'head.tpl'
    }),
  ]
};

if (environment === 'production') {
  config.plugins.push(new webpack.optimize.UglifyJsPlugin());
  config.devtool = 'cheap-module-source-map'; // source-map
} else {
  config.devtool = 'source-map'; // cheap-module-source-map
}
console.dir('Running: ' + environment + ' mode.');

module.exports = config;