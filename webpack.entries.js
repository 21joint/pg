const {lstatSync, readdirSync} = require('fs');
const {resolve, basename, join} = require('path');
const {each} = require('lodash');
const isDirectory = source => lstatSync(source).isDirectory();

let entry = {};

const getDirectories = source => readdirSync(source).map(name => join(source, name)).filter(isDirectory);

let _modules = getDirectories('./application/themes/parentalguidance/modules/');

each(_modules, module => {
  console.log('Path:', module);
  console.log('Name:', basename(module));
  console.log('path:', resolve(__dirname, module));
  entry[basename(module)] = resolve(__dirname, module)
});

module.exports = entry;
