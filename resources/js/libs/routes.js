var routesL = require('~/datas/routes.json');

module.exports = function() {
    
    var args = Array.prototype.slice.call(arguments);
    var name = args.shift();

    if (routesL[name] === undefined) {
        console.error('Unknown route ', name);
    } else {
        return '/' + routesL[name]
            .split('/')
            .map(s => s[0] == '{' ? args.shift() : s)
            .join('/');
    }

};