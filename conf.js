const pkg = require('./package');
const path = require('path');

module.exports = {
  title: pkg.name,
  prefix: 'prg',
  description: pkg.description,
  modules: {
    prg: {
      path: path.resolve(__dirname, 'application/themes/parentalguidance'),
      dist: path.resolve(__dirname, 'application/themes/parentalguidance/dist')
    }
  },
};