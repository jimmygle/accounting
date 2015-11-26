var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    var boostrapPath = 'node_modules/bootstrap-sass/assets';
    mix.sass('bootstrap.scss')
      .copy(boostrapPath + '/fonts', 'public/fonts')
      .copy(boostrapPath + '/javascripts/bootstrap.min.js', 'public/js');

    mix.sass('global.scss');

    var jqueryPath = 'bower_components/jquery/dist';
    mix.copy(jqueryPath + '/jquery.min.js', 'public/js');
    mix.copy(jqueryPath + '/jquery.min.map', 'public/js');
});
