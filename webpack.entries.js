const {lstatSync, readdirSync} = require('fs');
const {join, basename, resolve} = require('path');
const {each} = require('lodash');

const isDirectory = source => lstatSync(source).isDirectory();

let entry = {};

const getDirectories = source =>
  readdirSync(source).map(name => join(source, name)).filter(isDirectory);

let _modules = getDirectories('./application/themes/parentalguidance/modules/');

each(_modules, module => entry[basename(module)] = resolve(__dirname, module));

module.exports = entry;
