/**
 * VERBENA FRAMEWORK
 * 2015 - MIT LICENSE.
 */

// Modules.
var gulp        = require('gulp'),
    plumber     = require('gulp-plumber'),
    sass        = require('gulp-sass'),
    minifycss   = require('gulp-minify-css'),
    uglify      = require('gulp-uglifyjs'),
    jshint      = require('gulp-jshint'),
    stripDebug  = require('gulp-strip-debug'),
    notify      = require('gulp-notify');

// Assets paths.
var path_stylesheets    = './assets/stylesheets/',
    path_javascripts    = './assets/javascripts/',
    path_fonts          = './assets/fonts/',
    path_fontawesome    = './assets/vendor/font-awesome/fonts/';


// Sets default task.
gulp.task('default', ['copy', 'sass', 'uglify', 'watch']);

// Plumber error handler, that prevent exit 
// from script when a pipe gets an error.
var onError = function (error) {
    console.log('PipeError: ' + error);
};


// SCSS task (merge, compress styles).
gulp.task('sass', function () {
    return gulp.src(path_stylesheets + 'sass/*.scss')
        .pipe(plumber({
            errorHandler: onError
        }))
        .pipe(sass())
        .pipe(minifycss())
        .pipe(gulp.dest(path_stylesheets))
        //.pipe(notify({ message: 'SASS task complete' }));
});

// JS task (merge, compress, strip-debug scripts).
gulp.task('uglify', function () {
    return gulp.src(path_javascripts + 'sources/*.js')
        .pipe(plumber({
            errorHandler: onError
        }))
        // Disable the debugger (like console.log).
        .pipe(stripDebug())
        .pipe(uglify('app.min.js', {
            outSourceMap: true
        }))
        .pipe(gulp.dest(path_javascripts))
        //.pipe(notify({ message: 'JS Uglify task complete' }));
});

// JS task (hints errors, mistakes in scripts).
gulp.task('jshint', function() {
    return gulp.src(path_javascripts + 'sources/app.js')
        .pipe(plumber({
            errorHandler: onError
        }))
        .pipe(jshint())
        .pipe(jshint.reporter('jshint-stylish'))
        //.pipe(notify({ message: 'JS Hinting task complete' }));
});

// Tasks watcher (live-listener for changes).
gulp.task('watch', function () {
    gulp.watch(path_stylesheets + 'sass/*.scss', ['sass']);

    gulp.watch(path_javascripts + 'sources/*.js', ['uglify']);
    //gulp.watch(path_javascripts + 'app.js', ['jshint']);
});

// Copies font-awesome resources to fonts/ 
// assets public directory.
return gulp.src(path_fontawesome + '*')
    .pipe(gulp.dest(path_fonts));