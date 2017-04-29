const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/assets/js/app.js', 'public/js')
//   .sass('resources/assets/sass/app.scss', 'public/css');
var publicFontDirectory  = 'public/fonts',
    resourcesDirectory   = 'resources/assets/plugins/',
    publicImageDirectory = 'public/images',
    directories          = {
        'resources/assets/plugins/bootstrap/fonts': publicFontDirectory,
        'resources/assets/plugins/font-awesome/fonts': publicFontDirectory,
        'resources/assets/plugins/materialize-css/fonts/': publicFontDirectory + '/roboto',
        'resources/assets/plugins/material-design-iconic-font/fonts': publicFontDirectory,
        'resources/assets/fonts': publicFontDirectory,
        'resources/assets/images': publicImageDirectory,
        'resources/assets/plugins/jquery-datatable/skin/bootstrap/images/sort_asc.png': publicImageDirectory,
        'resources/assets/plugins/jquery-datatable/skin/bootstrap/images/sort_asc_disabled.png': publicImageDirectory,
        'resources/assets/plugins/jquery-datatable/skin/bootstrap/images/sort_both.png': publicImageDirectory,
        'resources/assets/plugins/jquery-datatable/skin/bootstrap/images/sort_desc.png': publicImageDirectory,
        'resources/assets/plugins/jquery-datatable/skin/bootstrap/images/sort_desc_disabled.png': publicImageDirectory
    };

mix.sass('resources/assets/sass/style.scss', 'public/css/app.css')
    .js('resources/assets/js/admin.js', 'public/js/app.js')
    .styles([
        resourcesDirectory + 'bootstrap/css/bootstrap.css',
        resourcesDirectory + 'node-waves/waves.css',
        resourcesDirectory + 'animate-css/animate.css',
        resourcesDirectory + 'multi-select/css/multi-select.css',
        resourcesDirectory + 'sweetalert/sweetalert.css',
        resourcesDirectory + 'jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css',
        resourcesDirectory + 'morrisjs/morris.css',
        resourcesDirectory + 'ckeditor/content.css',
        resourcesDirectory + 'font-awesome/css/font-awesome.css',
        resourcesDirectory + 'material-design-iconic-font/css/material-design-iconic-font.css',
        resourcesDirectory + 'materialize-css/css/materialize.css',
    ], 'public/css/all.css')
    .scripts([
        resourcesDirectory + 'jquery/jquery.min.js',
        resourcesDirectory + 'bootstrap/js/bootstrap.min.js',
        resourcesDirectory + 'bootstrap-select/js/bootstrap-select.js',
        resourcesDirectory + 'ckeditor/ckeditor.js',
        resourcesDirectory + 'jquery-slimscroll/jquery.slimscroll.js',
        resourcesDirectory + 'jquery-datatable/jquery.dataTables.js',
        resourcesDirectory + 'jquery-datatable/datatables.js',
        resourcesDirectory + 'jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js',
        resourcesDirectory + 'jquery-validation/jquery.validate.js',
        resourcesDirectory + 'jquery-validation/additional-methods.js',
        resourcesDirectory + 'jquery-validation/localization/messages_es.js',
        resourcesDirectory + 'materialize-css/js/materialize.min.js',
        resourcesDirectory + 'multi-select/js/jquery.multi-select.js',
        resourcesDirectory + 'sweetalert/sweetalert.min.js',
        resourcesDirectory + 'node-waves/waves.js',
        resourcesDirectory + 'raphael/raphael.min.js',
        resourcesDirectory + 'morrisjs/morris.min.js',
        'resources/assets/pages/ui/tooltips-popovers.js'
    ], 'public/js/all.js')
    .scripts('resources/assets/js/cuentas/crear_cuenta.js', 'public/js/cuentas/crear_cuenta.js');

    for (var directory in directories) {
        mix.copy(directory, directories[directory]);
    }