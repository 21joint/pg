const webpackMerge = require('webpack-merge');
const hotMiddlewareScript = 'webpack-hot-middleware/client?path=/__webpack_hmr&timeout=20000&reload=true';


module.exports = webpackMerge(require('./config/webpack.common'), {
  entry: {
    core: [
      __dirname + '/externals/mootools/mootools-core-1.4.5-full-compat-nc',
      __dirname + '/externals/mootools/mootools-more-1.4.0.1-full-compat-nc',
      __dirname + '/externals/chootools/chootools',
      __dirname + '/application/modules/Core/externals/scripts/core',
      __dirname + '/application/modules/User/externals/scripts/core',
      __dirname + '/externals/smoothbox/smoothbox4',
      __dirname + '/externals/scrollbars/scrollbars.min',
      hotMiddlewareScript
    ],
    header: ['./application/themes/parentalguidance/modules/header/header.module.js', hotMiddlewareScript],
    auth: ['./application/themes/parentalguidance/modules/auth/auth.module.js', hotMiddlewareScript],
    reviews_home: ['./application/themes/parentalguidance/modules/reviews/home.module.js', hotMiddlewareScript],
    reviews_view: ['./application/themes/parentalguidance/modules/reviews/view.module.js', hotMiddlewareScript],
    reviews_create: ['./application/themes/parentalguidance/modules/reviews/create.module.js', hotMiddlewareScript],
    footer: ['./application/themes/parentalguidance/modules/footer/footer.module.js', hotMiddlewareScript],
    guides_home: ['./application/themes/parentalguidance/modules/guides/home.module.js', hotMiddlewareScript],
    browse_listing: ['./application/themes/parentalguidance/modules/browse-listing/home.module.js', hotMiddlewareScript],
    community_leaderboard: ['./application/themes/parentalguidance/modules/community/leaderboard.module.js', hotMiddlewareScript],
  }
});
