let gulp = require('gulp');
let sass = require('gulp-sass');
let cleancss = require('gulp-clean-css');
let csscomb = require('gulp-csscomb');
let rename = require('gulp-rename');
let autoprefixer = require('gulp-autoprefixer');

let scssPaths = {
    src: "./scss/*.scss",
    dest: "./dist/css"
};

gulp.task('scssWatch', () => {
    return gulp.watch('./scss/*.scss', gulp.series('scssBuild'));
});

gulp.task('scssBuild', () => {
    return gulp.src(scssPaths.src)
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

gulp.task('default', gulp.series('scssBuild'));
