// Load plugins
var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    cssnano = require('gulp-cssnano'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache'),
    livereload = require('gulp-livereload'),
    del = require('del');
    lib = require('bower-files')();

// Bower files
gulp.task('bower-files', function () {
  gulp.src(lib.ext('js').files)
    .pipe(concat('bower-scripts.js'))
    .pipe(gulp.dest('assets/scripts'))
    .pipe(notify({ message: 'Bower scripts complete' }));

  gulp.src(lib.ext('css').files)
    .pipe(concat('bower-styles.css'))
    .pipe(gulp.dest('assets/styles/css'))
    .pipe(notify({ message: 'Bower styles complete' }));
});

// Sass
gulp.task('sass', function() {
  return sass('assets/styles/sass/style.scss', { style: 'expanded' })
    .pipe(autoprefixer('last 2 version'))
    .pipe(gulp.dest('assets/styles/css'))
    .pipe(notify({ message: 'Sass task complete' }));
});

// Styles
gulp.task('styles', function() {
  return gulp.src('assets/styles/**/*.css')
    .pipe(concat('style.min.css'))
    .pipe(cssnano())
    .pipe(gulp.dest('styles'))
    .pipe(notify({ message: 'Styles task complete' }));
});

// Scripts
gulp.task('scripts', function() {
  return gulp.src('assets/scripts/**/*.js')
    .pipe(concat('scripts.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('scripts'))
    .pipe(notify({ message: 'Scripts task complete' }));
});

// Images
gulp.task('images', function() {
  return gulp.src('assets/images/**/*')
    // .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
    .pipe(gulp.dest('images'))
    .pipe(notify({ message: 'Images task complete' }));
});

// Fonts
gulp.task('fonts', function() {
    return gulp.src([
      'bower_components/components-font-awesome/fonts/**/*',
      'bower_components/bootstrap-sass/assets/fonts/bootstrap/**/*'
      ])
      .pipe(gulp.dest('fonts'))
      .pipe(notify({ message: 'Fonts task complete' }));
});

// Clean
gulp.task('clean', function() {
  return del(['styles', 'scripts', 'images']);
});

// Default task
gulp.task('default', ['clean', 'bower-files', 'sass'], function() {
  gulp.start('styles', 'scripts', 'images', 'fonts');
});

// Watch
gulp.task('watch', function() {

  // Watch .scss files
  gulp.watch('assets/styles/**/*.scss', ['sass']);

  // Watch .css files
  gulp.watch('assets/styles/**/*.css', ['styles']);

  // Watch .js files
  gulp.watch('assets/scripts/**/*.js', ['scripts']);

  // Watch image files
  gulp.watch('assets/images/**/*', ['images']);

  // Watch fonts files
  gulp.watch('assets/fonts/**/*', ['fonts']);

  // Create LiveReload server
  livereload.listen();

  // Watch any files in , reload on change
  gulp.watch(['**']).on('change', livereload.changed);

});