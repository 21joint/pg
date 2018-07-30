const path = require("path");
const pkg = require("./package");
const Conf = require("./conf");
const args = require("yargs").argv;
const glob = require("glob");
const webpack = require("webpack");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const ExtractTextPlugin = require("extract-text-webpack-plugin");
// const CopyWebpackPlugin = require("copy-webpack-plugin");
const Modules = Conf.modules;


const IS_DEV = (process.env.NODE_ENV === "dev");

console.info("Project is running in " + (IS_DEV ? "development" : "production") + " mode");

/**
 * Webpack Configuration
 */

module.exports = {
  context: path.resolve(__dirname, "application/themes/parentalguidance"),
  entry: {
    index: "./index.js"
  },
  output: {
    filename: "scripts/[name].bundle.js",
    path: Modules.prg.dist
  },
  module: {
    rules: [
      // JS
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: [
          "babel-loader"
        ]
      },
      // SCSS
      {
        test: /\.(scss|css)$/,
        use: ExtractTextPlugin.extract({
          fallback: "style-loader",
          use: [
            {
              loader: "css-loader",
              options: {
                minimize: !IS_DEV,
                sourceMap: IS_DEV,
                publicPath: "/"
              }
            },
            {
              loader: "postcss-loader",
              options: {
                sourceMap: IS_DEV,
                publicPath: "/",
                plugins: [
                  require("postcss-flexbugs-fixes"),
                  require("autoprefixer")({
                    browsers: ["last 3 versions"]
                  })
                ]
              }
            },
            {
              loader: "sass-loader",
              options: {
                sourceMap: IS_DEV,
                data: "$prefix: " + require("./conf").prefix + ";"
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
            loader: "url-loader",
            options: {
              limit: 10000,
              name(file) {
                if (file.indexOf("fonts") > -1) {
                  return "fonts/[name].[ext]";
                }
                else {
                  return "images/[name].[ext]";
                }
              },
              fallback: "file-loader",
              outputPath: "./"
            },
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
          name: "vendors",
          enforce: true,
          chunks: "all"
        }
      }
    }
  },
  plugins: [
    new webpack.DefinePlugin({
      IS_DEV
    }),
    new webpack.ProvidePlugin({
      $: "jquery",
      jQuery: "jquery",
      "window.jQuery": "jquery"
    }),
    new ExtractTextPlugin({
      filename: "styles/[name].css"
    }),
    new HtmlWebpackPlugin({
      template: "./head.tpl",
      title: "Head",
      filename: "head.tpl"
    }),
  ],
  devtool: "inline-source-map"
};
