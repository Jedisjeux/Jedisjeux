var gulp = require('gulp');
var chug = require('gulp-chug');
var argv = require('yargs').argv;

config = [
    '--rootPath',
    argv.rootPath || '../../../public/assets/',
    '--nodeModulesPath',
    argv.nodeModulesPath || '../../../node_modules/',
    '--vendorPath',
    argv.vendorPath || '../../../vendor/'
];

gulp.task('admin', function() {
    gulp.src('public/assets/backend/Gulpfile.js', { read: false })
        .pipe(chug({ args: config }))
    ;
});

gulp.task('admin-watch', function() {
    gulp.src('public/assets/backend/Gulpfile.js', { read: false })
        .pipe(chug({ args: config, tasks: 'watch' }))
    ;
});

gulp.task('app', function() {
    gulp.src('public/assets/frontend/Gulpfile.js', { read: false })
        .pipe(chug({ args: config }))
    ;
});

gulp.task('app-watch', function() {
    gulp.src('public/assets/frontend/Gulpfile.js', { read: false })
        .pipe(chug({ args: config, tasks: 'watch' }))
    ;
});

gulp.task('default', ['admin', 'app']);
