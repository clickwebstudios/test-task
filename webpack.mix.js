const mix = require('laravel-mix');
const path = require('path');
let webpack = require('webpack');

mix.webpackConfig({
  plugins: [
    
  ],
  resolve: {
    alias: {
      '~': path.resolve(__dirname, 'resources/js'),
    }
  },
  module: {
    
  }
});

mix
  .js('resources/js/app.js', 'public/res/js/')
  .sass('resources/sass/app.scss', 'public/res/css')
  .vue({ version: 2 })
  .version();
  