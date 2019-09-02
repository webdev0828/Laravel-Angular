var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {

  //Admin js and css files
    mix.less('app.less','public/css/all.css');
    mix.styles([
      'pace-master/pace-theme-flash.css',
      'jplayer.blue.monday.css',
      'uniform/uniform.default.css',
      'bootstrap/bootstrap.css',
      'bootstrap-toggle.css',
      'fontawesome/font-awesome.css',
      'line-icons/simple-line-icons.css',
      'waves/waves.css',
      'switchery/switchery.css',
      '3d-bold-navigation/style.css',
      'slidepushmenus/component.css',
      'toastr/toastr.min.css',
      'datatables/jquery.datatables.css',
      'bootstrap-datepicker/datepicker.css',
      'modern.css',
      'custom.css',
      'Tokenize/jquery.tokenize.css',
      'select2/select2.css',
      'multi-select/multi-select.css'
    ], 'public/css/all.css');

    mix.scripts([
      'jquery/jquery-2.1.4.min.js',
      '3d-bold-navigation/modernizr.js',
      '../plugins/jquery/jquery.jplayer.min.js',
      '3d-bold-navigation/main.js',
      'jquery-ui/jquery-ui.js',
      'jquery-validation/jquery.validate.js',
      'pace-master/pace.js',
      'jquery-blockui/jquery.blockui.js',
      'bootstrap/bootstrap.js',
      'bootstrap-toggle.js',
      'jquery-slimscroll/jquery.slimscroll.js',
      'switchery/switchery.js',
      'uniform/jquery.uniform.js',
      'waves/waves.js',
      '3d-bold-navigation/main.js',
      'waypoints/jquery.waypoints.js',
      'jquery-counterup/jquery.counterup.js',
      'toastr/toastr.min.js',
      'datatables/jquery.datatables.js',
      'appsetting.js',
      'bootstrap-datepicker/bootstrap-datepicker.js',
      'select2/select2.js',
      'modern.min.js',
      'datatable.js',
      'app_settings.js',
      'dashboard.js',
      'admin.js',
      'competition.js',
      'news.js',
      'faq.js',
      'emailNotification.js',
      'navigation.js',
      'discover.js',
      'drip-feed.js',
      'remix.js',
      'quality-control.js',
      'repost.js',
      'music-video.js',
      'remix-competition.js',
      'music_video_release.js',
      'gating.js',
      'genres.js',
      'moods.js',
      'video_template.js',
      'Tokenize/jquery.tokenize.js',
      'toneden/toneden.loader.js',
      'multi-select/jquery.quicksearch.js',
      'multi-select/jquery.multi-select.js',
      'ytplayer.js'
    ],'public/js/all.js');

   /* FRONT WEB CSS AND JS */
   mix.styles([
        // common css
        'plugins/angular-charts/angular-chart.min.css',
        'plugins/angular/bootstrap-tour/bootstrap-tour-standalone.css',
        'plugins/angular/ui-select/select.css',
        'plugins/angular/sweetalert/sweetalert.css',
        'plugins/angular/ngTinyScrollbar/ng-tiny-scrollbar.css',
        'plugins/jquery/croppie.css',
        'frontweb/assets/css/toastr.css',
        'frontweb/assets/css/style.min.css',
        'frontweb/assets/css/custom_style.css',
        'frontweb/assets/css/mobile.css'
    ],'public/frontweb/css/stm-frontweb.css', 'resources/assets');

    mix.scripts([
      // 'plugins/jquery/jquery.min.js',
      'plugins/jquery/jquery.jplayer.min.js',
      'plugins/jquery/croppie.js',
      'plugins/angular/angular.min.js',
      'plugins/angular-charts/Chart.js',
      'plugins/angular-charts/angular-chart.min.js',
      'plugins/angular/angular-ui-bootstrap.js',
      'plugins/angular/ui-bootstrap-tpls-1.1.1.js',
      'plugins/angular/angular-ui-router.min.js',
      'plugins/angular/angular-resource.js',
      'plugins/angular/bootstrap-tour/bootstrap-tour-standalone.js',
      'plugins/angular/angularjs-dropdown-multiselect.js',
      'plugins/angular/lodash.min.js',
      'plugins/angular/ngInfiniteScroll/ng-infinite-scroll.min.js',
      'plugins/angular/ocLazyLoad.js',
      'plugins/angular/Checklist-model.js',
      'plugins/angular/country-picker.js',
      'plugins/angular/toastr.js',
      'plugins/angular/angular-animate.js',
      'plugins/angular/angular-sanitize.min.js',
      'plugins/angular/ngTinyScrollbar/ng-tiny-scrollbar.js',
      'plugins/angular/spin/spin.min.js',
      'plugins/angular/spin/angular-loader.js',
      'plugins/angular/ui-select/select.js',
      'plugins/angular/sweetalert/sweetalert.min.js',
      'plugins/angular/moment.min.js',
      'plugins/angular/angular-moment.min.js',
      'plugins/angular/angular-counter.js',
      'plugins/angular/angular-read-more.js',
      'plugins/angular/angular-ellipsis.js'


    ], 'public/frontweb/js/stm-frontweb-plugins.js', 'resources/assets');

    mix.scripts([
      'assets/js/scripts.js',
      '../plugins/jquery/jquery.filedownload.js',
      '../plugins/jquery/waypoints.min.js',
      '../plugins/jquery/jquery.counterup.js',

      // 'soundcloud-sdk-3.1.2.js',
      'facebook-sdk.js',
      // 'apis.google.platform.js',
      // 'twitter-widgets.js',
      'custom-lib.js',
      'app.js',
      'app.routes.js',
      'modules/**/*.js',
      'config/**/*.js',
      'directives/**/*.js',
      'filters/**/*.js',
      'services/**/*.js',
      'controllers/**/*.js'
    ], 'public/frontweb/js/stm-frontweb.js', 'resources/assets/frontweb');

    mix.styles([
      'frontweb/assets/css/custom_dev.css'
    ], 'public/frontweb/css/stm-dev.css', 'resources/assets');
});