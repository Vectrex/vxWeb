let gulp = require('gulp');
let sass = require('gulp-sass');
let cleancss = require('gulp-clean-css');
let csscomb = require('gulp-csscomb');
let rename = require('gulp-rename');
let autoprefixer = require('gulp-autoprefixer');
let uglify = require('gulp-uglify');
let concat = require('gulp-concat');
let vueComponent = require('gulp-vue-single-file-component');

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

var jsPaths = {
    src: "./js/",
    dest: "./dist/js"
};

gulp.task('jsBuild', () => {
    return gulp.src([
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

gulp.task('vue', () => {
   return gulp.src("./vue/**/*.vue")
       .pipe(vueComponent())
       .pipe(rename({ extname: ".js" }))
       .pipe(gulp.dest("./dist/js/vue"));
});