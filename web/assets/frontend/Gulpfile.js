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
var bundlesPath = rootPath + '../bundles/';
var bowerComponentsPath = rootPath + 'frontend/bower_components/';

var paths = {
    shop: {
        js: [
            nodeModulesPath + 'jquery/dist/jquery.min.js',
            bundlesPath + 'bmatznerjqueryui/js/minified/jquery-ui.min.js',
            bundlesPath + 'mopabootstrap/bootstrap-sass/assets/javascripts/bootstrap/tooltip.js',
            bundlesPath + 'mopabootstrap/bootstrap-sass/assets/javascripts/bootstrap/*.js',
            bundlesPath + 'mopabootstrap/js/mopabootstrap-collection.js',
            bundlesPath + 'mopabootstrap/js/mopabootstrap-subnav.js',
            bowerComponentsPath + 'bootstrap-star-rating/js/star-rating.min.js',
            bowerComponentsPath + 'fancybox/lib/jquery.mousewheel-3.0.6.pack.js',
            bowerComponentsPath + 'fancybox/source/jquery.fancybox.pack.js',
            bowerComponentsPath + 'fancybox/source/helpers/jquery.fancybox-buttons.js',
            bowerComponentsPath + 'fancybox/source/helpers/jquery.fancybox-media.js',
            bowerComponentsPath + 'fancybox/source/helpers/jquery.fancybox-thumbs.js',
            bowerComponentsPath + 'multiple-select/jquery.multiple.select.js',
            bowerComponentsPath + 'magnific-popup/dist/jquery.magnific-popup.min.js',
            bowerComponentsPath + 'moment/min/moment.min.js',
            bowerComponentsPath + 'eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
            bowerComponentsPath + 'owlcarousel/owl-carousel/owl.carousel.js',
            bowerComponentsPath + 'select2/dist/js/select2.js',
            bowerComponentsPath + 'jquery-countTo/jquery.countTo.js',
            bowerComponentsPath + 'rater/rater.js',
            bowerComponentsPath + 'bootstrap-switch/dist/js/bootstrap-switch.min.js',
            'js/**'
        ],
        sass: [
            nodeModulesPath + 'eonasdan-bootstrap-datetimepicker/src/sass/bootstrap-datetimepicker-build.scss',
            'sass/**'
        ],
        css: [
            'css/magnific-popup.css',
            bundlesPath + 'bmatznerfontawesome/css/font-awesome.css',
            bundlesPath + 'bmatznerjqueryui/css/smoothness/jquery-ui.css',
            bowerComponentsPath + 'bootstrap-star-rating/css/star-rating.min.css',
            bowerComponentsPath + 'fancybox/source/jquery.fancybox.css',
            bowerComponentsPath + 'fancybox/source/helpers/jquery.fancybox-buttons.css',
            bowerComponentsPath + 'fancybox/source/helpers/jquery.fancybox-thumbs.css',
            bowerComponentsPath + 'multiple-select/multiple-select.css',
            bowerComponentsPath + 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
            bowerComponentsPath + 'owlcarousel/owl-carousel/owl.carousel.css',
            bowerComponentsPath + 'select2/dist/css/select2.css',
            bowerComponentsPath + 'bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css'
        ],
        img: [
            vendorUiPath + 'Resources/private/img/**',
            'img/**'
        ],
        css_img: [
            bowerComponentsPath + 'fancybox/source/helpers/*.png'
        ],
        css_images: [
            bundlesPath + 'bmatznerjqueryui/css/base/images/*'
        ],
        css_images2: [
            bowerComponentsPath + 'fancybox/source/*.png',
            bowerComponentsPath + 'fancybox/source/*.gif'
        ],
        font: [
            nodeModulesPath + 'font-awesome/fonts/**',
            bundlesPath + 'mopabootstrap/fonts/**'
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


gulp.task('shop-css-img', function() {
    return gulp.src(paths.shop.css_img)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(shopRootPath + 'css/'))
        ;
});

gulp.task('shop-css-images', function() {
    return gulp.src(paths.shop.css_images)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(shopRootPath + 'css/images/'))
        ;
});

gulp.task('shop-css-images2', function() {
    return gulp.src(paths.shop.css_images2)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(shopRootPath + 'css/'))
        ;
});

gulp.task('shop-font', function() {
  return gulp.src(paths.shop.font)
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest(shopRootPath + 'fonts/'))
    ;
});

gulp.task('shop-watch', function() {
    livereload.listen();

    gulp.watch(paths.shop.js, ['shop-js']);
    gulp.watch(paths.shop.sass, ['shop-css']);
    gulp.watch(paths.shop.css, ['shop-css']);
    gulp.watch(paths.shop.img, ['shop-img']);
    gulp.watch(paths.shop.img, ['shop-css-img']);
    gulp.watch(paths.shop.img, ['shop-css-images']);
    gulp.watch(paths.shop.img, ['shop-css-images2']);
    gulp.watch(paths.shop.font, ['shop-font']);
});

gulp.task('default', ['shop-js', 'shop-css', 'shop-img', 'shop-css-img', 'shop-css-images', 'shop-css-images2', 'shop-font']);
gulp.task('watch', ['default', 'shop-watch']);
