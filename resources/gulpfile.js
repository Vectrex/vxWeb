var gulp = require('gulp');
var sass = require('gulp-sass');
var cleancss = require('gulp-clean-css');
var csscomb = require('gulp-csscomb');
var rename = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');


var scssPaths = {
    src: "./scss/*.scss",
    dest: "./dist/css"
};

gulp.task('scssWatch', function() {
  gulp.watch('./scss/*.scss', ['scssBuild']);
});

gulp.task('scssBuild', function() {
  gulp.src(scssPaths.src)
    .pipe(sass({outputStyle: 'compact', precision: 10})
      .on('error', sass.logError)
    )
    .pipe(autoprefixer())
    .pipe(csscomb())
    .pipe(gulp.dest(scssPaths.dest))
    .pipe(cleancss())
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest(scssPaths.dest));
});

gulp.task('default', ['scssBuild']);

var jsPaths = {
    src: "./js/",
    dest: "./dist/js"
};

gulp.task('jsBuild', function() {
    gulp.src([
        "./js/core.js",
        "./js/xhr.js",
        "./js/widgets/xhrform.js",
        "./js/widgets/calendar.js",
        "./js/widgets/sortable.js",
        "./js/widgets/simpletabs.js"
    ])
        .pipe(concat("vxjs.js"))
        .pipe(uglify())
        .pipe(gulp.dest(jsPaths.dest));
});