const gulp = require('gulp');
// const browserSync = require('browser-sync').create();
const sass = require('gulp-sass');
const postcss = require('gulp-postcss');
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('autoprefixer');


// Compile sass into CSS
gulp.task('sass', function () {
    return gulp.src(['src/scss/local.scss'])
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: 'compressed'}))
        .on('error', function (err) {
            console.log(err.toString());

            this.emit('end');
        })
        .pipe(postcss([autoprefixer()]))
        .pipe(sourcemaps.write('.'))

        .pipe(gulp.dest("public/css"));
});

// Move the javascript files into our /src/js folder
gulp.task('js', function () {
    const bootstrap = 'node_modules/bootstrap/dist/js/bootstrap.min.js',
        bootstrap_map = 'node_modules/bootstrap/dist/js/bootstrap.min.js.map',
        jquery = 'node_modules/jquery/jquery.min.js',
        jquery_map = 'node_modules/jquery/jquery.min.map',
        popper = 'node_modules/popper.js/dist/umd/popper.min.js',
        popper_map = 'node_modules/popper.js/dist/umd/popper.min.js.map ';
    gulp.src([bootstrap, bootstrap_map, jquery, jquery_map, popper, popper_map])
        .pipe(gulp.dest("public/js"));
});

// Static Server + watching scss/html files
gulp.task('watch', ['sass'], function () {
    gulp.watch(['node_modules/bootstrap/scss/bootstrap.scss', 'src/scss/*.scss'], ['sass']);
});

gulp.task('default', ['js', 'watch']);