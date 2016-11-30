var gulp = require('gulp');
var chug = require('gulp-chug');
var argv = require('yargs').argv;

config = [
    '--rootPath',
    argv.rootPath || '../../../web/assets/',
    '--nodeModulesPath',
    argv.nodeModulesPath || '../../../node_modules/',
    '--vendorPath',
    argv.vendorPath || '../../../vendor/'
];

gulp.task('admin', function() {
    gulp.src('web/assets/backend2/Gulpfile.js', { read: false })
        .pipe(chug({ args: config }))
    ;
});

gulp.task('shop', function() {
    gulp.src('src/Sylius/Bundle/ShopBundle/Gulpfile.js', { read: false })
        .pipe(chug({ args: config }))
    ;
});

gulp.task('default', ['admin', 'shop']);
