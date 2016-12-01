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

var rootPath = argv.rootPath || '../';
var shopRootPath = rootPath + 'frontend/compiled/';
var vendorPath = argv.vendorPath || '';
var vendorUiPath = '' === vendorPath ? '../../../vendor/sylius/ui-bundle/' : vendorPath + 'sylius/ui-bundle/';
var nodeModulesPath = argv.nodeModulesPath;

var paths = {
    shop: {
        js: [
            nodeModulesPath + 'jquery/dist/jquery.min.js',
            nodeModulesPath + 'semantic-ui-css/semantic.min.js',
            vendorUiPath + 'Resources/private/js/**',
            'js/**'
        ],
        sass: [
            nodeModulesPath + 'eonasdan-bootstrap-datetimepicker/src/sass/bootstrap-datetimepicker-build.scss',
            'sass/**'
        ],
        css: [
            nodeModulesPath + 'font-awesome/css/font-awesome.min.css',
            'css/magnific-popup.css',
            nodeModulesPath + 'jquery-ui/themes/base/all.css',
            nodeModulesPath + 'bootstrap-star-rating/css/star-rating.min.css',
            nodeModulesPath + 'fancybox/dist/css/jquery.fancybox.css',
            nodeModulesPath + 'fancybox/dist/helpers/css/jquery.fancybox-buttons.css',
            nodeModulesPath + 'fancybox/dist/helpers/css/jquery.fancybox-thumbs.css',
            nodeModulesPath + 'multiple-select/multiple-select.css',
            nodeModulesPath + 'owl.carousel/dist/assets/owl.carousel.css',
            nodeModulesPath + 'select2/dist/css/select2.css',
            nodeModulesPath + 'bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css'
        ],
        img: [
            vendorUiPath + 'Resources/private/img/**',
            'img/**'
        ]
    }
};

gulp.task('shop-js', function () {
    return gulp.src(paths.shop.js)
        .pipe(concat('app.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(shopRootPath + 'js/'))
    ;
});

gulp.task('shop-css', function() {
    gulp.src([nodeModulesPath+'semantic-ui-css/themes/**/*']).pipe(gulp.dest(shopRootPath + 'css/themes/'));

    var cssStream = gulp.src(paths.shop.css)
        .pipe(concat('css-files.css'))
    ;

    var sassStream = gulp.src(paths.shop.sass)
        .pipe(sass())
        .pipe(concat('sass-files.scss'))
    ;

    return merge(cssStream, sassStream)
        .pipe(order(['css-files.css', 'sass-files.scss']))
        .pipe(concat('style.css'))
        .pipe(gulpif(env === 'prod', uglifycss()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(shopRootPath + 'css/'))
        .pipe(livereload())
    ;
});

gulp.task('shop-img', function() {
    return gulp.src(paths.shop.img)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(shopRootPath + 'img/'))
    ;
});

gulp.task('shop-watch', function() {
    livereload.listen();

    gulp.watch(paths.shop.js, ['shop-js']);
    gulp.watch(paths.shop.sass, ['shop-css']);
    gulp.watch(paths.shop.css, ['shop-css']);
    gulp.watch(paths.shop.img, ['shop-img']);
});

gulp.task('default', ['shop-js', 'shop-css', 'shop-img']);
gulp.task('watch', ['default', 'shop-watch']);
