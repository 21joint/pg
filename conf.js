const Pkg = require('./package');
const path = require('path');

module.exports = {
    title: Pkg.name,
    description: Pkg.description,
    modules: {
        parental_guidance: {
            prefix: 'prntl',
            path: path.resolve(__dirname, 'application/modules/Sdparentalguide'),
            main: 'index.js'
        }
    },
};