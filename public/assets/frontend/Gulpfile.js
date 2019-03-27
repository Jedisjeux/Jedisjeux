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
var vendorUiPath = '' === vendorPath ? '../../../vendor/sylius/ui-bundle/' : vendorPath + 'sylius/ui-bundle/';
var nodeModulesPath = argv.nodeModulesPath;
var bundlesPath = rootPath + '../bundles/';

var paths = {
    app: {
        js: [
            nodeModulesPath + 'jquery/dist/jquery.min.js',
            nodeModulesPath + 'jquery-ui-dist/jquery-ui.min.js',
            nodeModulesPath + 'bootstrap/dist/js/bootstrap.bundle.js',
            nodeModulesPath + 'rater-jquery/rater.js',
            nodeModulesPath + 'cropperjs/dist/cropper.js',
            vendorUiPath + 'Resources/private/js/sylius-form-collection.js',
            'plugins/bootstrap-notify/bootstrap-notify.js',
            'plugins/isotope/imagesloaded.pkgd.min.js',
            'plugins/isotope/isotope.pkgd.min.js',
            'plugins/magnific-popup/jquery.magnific-popup.min.js',
            'plugins/waypoints/jquery.waypoints.min.js',
            'plugins/waypoints/sticky.min.js',
            'plugins/countdown/jquery.countdown.js',
            'plugins/countTo/jquery.countTo.js',
            'plugins/slick/slick.min.js',
            'js/**.js'
        ],
        sass: [
            'sass/style.scss',
            'sass/typography-default.scss',
            'sass/skins/light_blue.scss'
        ],
        css: [
            nodeModulesPath + 'bootstrap/dist/css/bootstrap.css',
            nodeModulesPath + 'font-awesome/css/font-awesome.css',
            nodeModulesPath + 'cropperjs/dist/cropper.css',
            'plugins/magnific-popup/magnific-popup.css',
            'css/animations.css',
            'plugins/slick/slick.css'
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
    gulp.src([bundlesPath + 'bmatznerjqueryui/css/base/images/*']).pipe(gulp.dest(appRootPath + 'css/images/'));

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
