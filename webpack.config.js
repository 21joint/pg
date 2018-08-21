const path = require('path');
const UglifyJSPlugin = require("uglifyjs-webpack-plugin");
const Conf = require('./conf');
// const glob = require('glob');
const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
// const CopyWebpackPlugin = require('copy-webpack-plugin');

let IS_DEV = process.env.NODE_ENV === 'dev';
let APP_PREFIX = Conf.prefix;

/**
 * Webpack Configuration
 *
 **/

let config = {
  output: {
    filename: 'scripts/[name].bundle.js',
    path: path.join(__dirname, 'dist')
  },
  module: {
    rules: [
      // JS
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        loader: 'babel-loader'
      },
      // SCSS / CSS
      {
        test: /\.(s)?css$/,
        use: ['css-hot-loader'].concat(ExtractTextPlugin.extract({
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
                data: '$prefix: ' + APP_PREFIX + ';'
              }
            }
          ]
        })),
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
              publicPath: '/'
            }
          }
        ]
      }
    ]
  },
  optimization: {
    splitChunks: {
      cacheGroups: {
        vendors: {
          test: /[\\/]node_modules[\\/]/,
          priority: -10,
          name: 'vendor'
        }
      }
    },
    minimizer: [
      new UglifyJSPlugin({
        uglifyOptions: {
          exclude: /node_modules/,
          sourceMap: IS_DEV,
          compress: {
            warnings: false
          }
        }
      }),
    ],

  },
  plugins: [
    new webpack.DefinePlugin({
      IS_DEV: IS_DEV,
      APP_PREFIX: APP_PREFIX
    }),
    new webpack.ProvidePlugin({
      _: 'lodash',
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery'
    }),
    new ExtractTextPlugin({
      filename: 'styles/[name].bundle.css'
    })
  ]
};
let compiler = webpack(config);

compiler.plugin("compilation", compilation => {
  compilation.contextDependencies.push(path.resolve(__dirname, "application/themes/parentalguidance/modules/"));
});

config.devtool = IS_DEV ? 'inline-source-map' : 'source-map'; // source-map

module.exports = config;
