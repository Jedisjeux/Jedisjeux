var concat = require('gulp-concat');
var env = process.env.GULP_ENV;
var gulp = require('gulp');
var gulpif = require('gulp-if');
var livereload = require('gulp-livereload');
var merge = require('merge-stream');
var order = require('gulp-order');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var argv = require('yargs').argv;

var rootPath = typeof argv.rootPath === 'undefined' ? '../' : argv.rootPath;
var appRootPath = rootPath + 'frontend/compiled/';
var vendorPath = argv.vendorPath || '';
var nodeModulesPath = argv.nodeModulesPath;
var bundlesPath = rootPath + '../bundles/';

var paths = {
    app: {
        js: [
            nodeModulesPath + 'jquery/dist/jquery.min.js',
            bundlesPath + 'bmatznerjqueryui/js/minified/jquery-ui.min.js',
            bundlesPath + 'mopabootstrap/bootstrap-sass/assets/javascripts/bootstrap/tooltip.js',
            bundlesPath + 'mopabootstrap/bootstrap-sass/assets/javascripts/bootstrap/*.js',
            bundlesPath + 'mopabootstrap/js/mopabootstrap-collection.js',
            bundlesPath + 'mopabootstrap/js/mopabootstrap-subnav.js',
            nodeModulesPath + 'moment/min/moment.min.js',
            nodeModulesPath + 'eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
            nodeModulesPath + 'jquery-fancybox/lib/jquery.mousewheel-3.0.6.pack.js',
            nodeModulesPath + 'jquery-fancybox/source/js/jquery.fancybox.pack.js',
            nodeModulesPath + 'jquery-fancybox/source/helpers/jquery.fancybox-buttons.js',
            nodeModulesPath + 'jquery-fancybox/source/helpers/jquery.fancybox-media.js',
            nodeModulesPath + 'jquery-fancybox/source/helpers/jquery.fancybox-thumbs.js',
            nodeModulesPath + 'magnific-popup/dist/jquery.magnific-popup.min.js',
            nodeModulesPath + 'bootstrap-switch/dist/js/bootstrap-switch.min.js',
            nodeModulesPath + 'owl.carousel/dist/owl.carousel.js',
            nodeModulesPath + 'select2/dist/js/select2.js',
            nodeModulesPath + 'rater-jquery/rater.js',
            'js/**'
        ],
        sass: [
            'scss/**'
        ],
        css: [
            nodeModulesPath + 'jquery-fancybox/source/jquery.fancybox.css',
            nodeModulesPath + 'jquery-fancybox/source/helpers/jquery.fancybox-buttons.css',
            nodeModulesPath + 'jquery-fancybox/source/helpers/jquery.fancybox-thumbs.css',
            'css/**'
        ],
        img: [
            'img/**'
        ],
        font: [
            nodeModulesPath + 'font-awesome/fonts/**',
            bundlesPath + 'mopabootstrap/fonts/**'
        ]
    }
};

gulp.task('app-js', function () {
    return gulp.src(paths.app.js)
        .pipe(concat('app.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'js/'))
        ;
});

gulp.task('app-css', function() {
    gulp.src([nodeModulesPath+'jquery-fancybox/source/img/*']).pipe(gulp.dest(appRootPath + 'css/'));
    gulp.src([nodeModulesPath+'jquery-fancybox/source/helpers/*.png']).pipe(gulp.dest(appRootPath + 'css/'));

    var cssStream = gulp.src(paths.app.css)
        .pipe(concat('css-files.css'))
    ;

    var sassStream = gulp.src(paths.app.sass)
        .pipe(sass())
        .pipe(concat('sass-files.scss'))
    ;

    return merge(cssStream, sassStream)
        .pipe(order(['css-files.css', 'sass-files.scss']))
        .pipe(concat('style.css'))
        .pipe(gulpif(env === 'prod', uglifycss()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'css/'))
        .pipe(livereload())
        ;
});

gulp.task('app-img', function() {
    return gulp.src(paths.app.img)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'img/'))
        ;
});

gulp.task('app-font', function() {
    return gulp.src(paths.app.font)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(appRootPath + 'fonts/'))
        ;
});

gulp.task('app-watch', function() {
    livereload.listen();

    gulp.watch(paths.app.js, ['app-js']);
    gulp.watch(paths.app.sass, ['app-css']);
    gulp.watch(paths.app.css, ['app-css']);
    gulp.watch(paths.app.img, ['app-img']);
});

gulp.task('default', ['app-js', 'app-css', 'app-img', 'app-font']);
gulp.task('watch', ['default', 'app-watch']);
