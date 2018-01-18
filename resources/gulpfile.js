var gulp = require('gulp');
var sass = require('gulp-sass');
var cleancss = require('gulp-clean-css');
var csscomb = require('gulp-csscomb');
var rename = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');

var paths = {
    source: "./scss/*.scss",
    dest: "./dist/css"
};

gulp.task('watch', function() {
  gulp.watch('./scss/*.scss', ['build']);
});

gulp.task('build', function() {
  gulp.src(paths.source)
    .pipe(sass({outputStyle: 'compact', precision: 10})
      .on('error', sass.logError)
    )
    .pipe(autoprefixer())
    .pipe(csscomb())
    .pipe(gulp.dest(paths.dest))
    .pipe(cleancss())
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(gulp.dest(paths.dest));
});

gulp.task('default', ['build']);