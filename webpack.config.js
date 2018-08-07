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


let IS_DEV = (process.env.NODE_ENV === 'dev' ? true : false);

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
    path: __dirname + '/dist'
  },
  module: {
    rules: [
      // JS
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        loader: 'babel-loader'
      },
      // IMAGES
      {
        test: /\.(png|jpg|jpeg|gif|svg|ico)$/,
        loader: 'file-loader',
        options: {
          name: '[name].[ext]?[hash]'
        }
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
      {
        test: /\.(svg|ttf|woff|woff2|eot)$/,
        loader: 'url-loader?limit=5000',
        options: {
          name: 'fonts/[name].[ext]?[hash]'
        }
      }
    ]
  },
  optimization: {
    minimize: !IS_DEV,
    // splitChunks: {
    //   cacheGroups: {
    //     vendors: {
    //       test: /[\\/]node_modules[\\/]/,
    //       name: 'vendors',
    //       enforce: true,
    //       chunks: 'all'
    //     }
    //   }
    // }
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
      filename: 'styles/[name].css'
    })
  ]
};

if (IS_DEV) {
  config.devtool = 'cheap-module-source-map'; // source-map
} else {
  config.devtool = 'source-map'; // cheap-module-source-map
}
console.dir('Running: ' + IS_DEV + ' mode.');

module.exports = config;
