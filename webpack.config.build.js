const pkg = require('./package');
const path = require('path');
const merge = require('webpack-merge');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const webpackConfig = require('./webpack.config');
const args = require('yargs').argv;


const publicPath = args.git ? '/' + pkg.name + '/' : '/';
const dist = args.git ? 'docs' : 'dist';

module.exports = merge(webpackConfig, {
    target: 'web',
    output: {
        path: path.join(__dirname, dist),
        filename: 'scripts/[name].[hash].js',
        publicPath: publicPath
    },
    plugins: [
        new CleanWebpackPlugin([dist])
    ],
    devtool: 'inline-source-map',
});