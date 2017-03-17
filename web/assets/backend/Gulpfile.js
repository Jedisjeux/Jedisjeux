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
var adminRootPath = rootPath + 'backend/compiled/';
var vendorPath = argv.vendorPath || '';
var vendorUiPath = '' === vendorPath ? '../../../vendor/sylius/ui-bundle/' : vendorPath + 'sylius/ui-bundle/';
var nodeModulesPath = argv.nodeModulesPath;
var bowerComponentsPath = rootPath + 'backend/bower_components/';

var paths = {
    admin: {
        js: [
            nodeModulesPath + 'jquery/dist/jquery.min.js',
            nodeModulesPath + 'semantic-ui-css/semantic.min.js',
            nodeModulesPath + 'select2/dist/js/select2.full.js',
            vendorUiPath + 'Resources/private/js/api-login.js',
            vendorUiPath + 'Resources/private/js/api-toggle.js',
            vendorUiPath + 'Resources/private/js/app.js',
            vendorUiPath + 'Resources/private/js/form-collection.js',
            vendorUiPath + 'Resources/private/js/grid-sorting.js',
            vendorUiPath + 'Resources/private/js/product-attributes.js',
            bowerComponentsPath + 'select2/dist/js/select2.js',
            'js/**'
        ],
        sass: [
            vendorUiPath + 'Resources/private/sass/**',
            'scss/**'
        ],
        css: [
            nodeModulesPath + 'semantic-ui-css/semantic.min.css',
            nodeModulesPath + 'select2/dist/css/select2.min.css',
            bowerComponentsPath + 'select2/dist/css/select2.css',
            vendorUiPath + 'Resources/private/css/**',
            'css/**'
        ],
        img: [
            vendorUiPath + 'Resources/private/img/**',
            'img/**'
        ]
    }
};

gulp.task('admin-js', function () {
    return gulp.src(paths.admin.js)
        .pipe(concat('app.js'))
        .pipe(gulpif(env === 'prod', uglify()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(adminRootPath + 'js/'))
    ;
});

gulp.task('admin-css', function() {
    gulp.src([nodeModulesPath+'semantic-ui-css/themes/**/*']).pipe(gulp.dest(adminRootPath + 'css/themes/'));

    var cssStream = gulp.src(paths.admin.css)
        .pipe(concat('css-files.css'))
    ;

    var sassStream = gulp.src(paths.admin.sass)
        .pipe(sass())
        .pipe(concat('sass-files.scss'))
    ;

    return merge(cssStream, sassStream)
        .pipe(order(['css-files.css', 'sass-files.scss']))
        .pipe(concat('style.css'))
        .pipe(gulpif(env === 'prod', uglifycss()))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(adminRootPath + 'css/'))
        .pipe(livereload())
    ;
});

gulp.task('admin-img', function() {
    return gulp.src(paths.admin.img)
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(adminRootPath + 'img/'))
    ;
});

gulp.task('admin-watch', function() {
    livereload.listen();

    gulp.watch(paths.admin.js, ['admin-js']);
    gulp.watch(paths.admin.sass, ['admin-css']);
    gulp.watch(paths.admin.css, ['admin-css']);
    gulp.watch(paths.admin.img, ['admin-img']);
});

gulp.task('default', ['admin-js', 'admin-css', 'admin-img']);
gulp.task('watch', ['default', 'admin-watch']);
