const elixir = require('laravel-elixir');

//require('laravel-elixir-vue');

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

var paths = {
    'jquery': './node_modules/jquery/',
    'jqueryui': './node_modules/jquery-ui/',
    'bootstrap': './node_modules/bootstrap-sass/',
    'fontawesome': './node_modules/font-awesome/',
    'typeahead': './node_modules/typeahead.js/',
    'orgchart': './node_modules/orgchart/'
}

elixir(mix => {
    mix.sass([
        'app.scss',
        'variables.scss'
    ])
    .styles(
        [
            //paths.jqueryui + 'themes/base',
            paths.orgchart + 'dist/css'
        ]
    )
    //.webpack('vue.js')
    .scripts(
        [
            paths.jquery + 'dist/jquery.min.js',
            //paths.jqueryui + 'ui',
            paths.bootstrap + 'assets/javascripts/bootstrap.min.js',
            //paths.typeahead + 'dist/typeahead.jquery.min.js',
            paths.orgchart + 'dist/js/jquery.orgchart.js'
        ],
        'public/js/vendor.js'
    )
    .scripts(
        [
            'app'
        ],
        'public/js/app.js'
    )
    .copy(paths.bootstrap + 'assets/fonts/bootstrap', 'public/fonts/bootstrap')
    .copy(paths.jqueryui + 'themes/base/images', 'public/css/images')
    .copy(paths.fontawesome + 'fonts', 'public/fonts');
});
