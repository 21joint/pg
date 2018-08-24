const {lstatSync, readdirSync} = require("fs");
const path = require("path");
const {each, replace} = require("lodash");
const isDirectory = source => lstatSync(source).isDirectory();
const glob = require("glob");

const IS_DEV = process.env.NODE_ENV === 'dev';
let entry = {};

const getModules = () => {
  const source = "./application/themes/parentalguidance/modules/**/*.module.js";
  let _modules = [];

  glob.sync(source).map(src => {
    let obj = {};

    obj.name = src.split('/modules/')[1].slice(0, src.split('/modules/')[1].lastIndexOf('/')) == replace(path.basename(src), /\.module\.js/, '') ?
      path.basename(src).replace(/\.module\.js/, '') :
      src.split('/modules/')[1].slice(0, src.split('/modules/')[1].lastIndexOf('/')) + '_' + replace(path.basename(src), /\.module\.js/, '');
    obj.source = [src].concat('./extra.js');
    _modules.push(obj);
  });

  return _modules;

};


let _modules = getModules();

each(_modules, module => {
  entry[module.name] = module.source ? module.source : module.name
});

module.exports = entry;
