const {lstatSync, readdirSync} = require("fs");
const path = require("path");
const {each} = require("lodash");
const isDirectory = source => lstatSync(source).isDirectory();
const glob = require("glob");

const IS_DEV = process.env.NODE_ENV === 'dev';
let entry = {};

const getModules = source => (IS_DEV ?
  readdirSync(source).map(name => path.join(source, name)).filter(isDirectory) :
  source => glob.sync("./application/themes/parentalguidance/**/*.module.js").map(function (module) {
    let _entryPath = path.join(__dirname, module);
  }));
let _modules = getModules("./application/themes/parentalguidance/modules/");

each(_modules, module => {
  let _entryPath = path.join(__dirname, module);
  if (IS_DEV) {
    entry[path.basename(module)] = _entryPath;
    console.log(path.basename(module));
  }
  else {
    console.log(path.basename(module));
    entry[path.basename(module).replace(/\.module\.js$/, '')] = _entryPath
  }

});

module.exports = entry;
