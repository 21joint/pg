const merge = require('webpack-merge');
const webpack = require('webpack');
const webpackConfig = require('./webpack.config');

module.exports = merge(webpackConfig, {
    devServer: {
        hot: true,
        open: true,
        openPage: 'parentalguidance',
        port: 3000,
        proxy: {
            '/': {
                target: 'http://localhost:8888',
                secure: false,
            }
        }
    },
    plugins: [
        new webpack.NamedModulesPlugin(),
        new webpack.HotModuleReplacementPlugin()
    ],
    devtool: 'source-map'

});