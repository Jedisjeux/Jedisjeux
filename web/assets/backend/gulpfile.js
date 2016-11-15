var autoprefixer = require('gulp-autoprefixer');
var browserSync = require('browser-sync');
var watchify = require('watchify');
var browserify = require('browserify');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var gulp = require('gulp');
var gulpif = require('gulp-if');
var gutil = require('gulp-util');
var gulpSequence = require('gulp-sequence');
var merge = require('merge-stream');
var order = require('gulp-order');
var sass = require('gulp-sass');

var watch = require('gulp-watch');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var streamify = require('gulp-streamify');
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');
var babel = require('gulp-babel');
var prod = gutil.env.prod;
var env = process.env.GULP_ENV;

var onError = function (err) {
  console.log(err.message);
  this.emit('end');
};

// bundling js with browserify and watchify
var b = watchify(browserify('./js/main', {
  cache: {},
  packageCache: {},
  fullPaths: true
}).transform("babelify", {presets: ["es2015", "react"]}));

var paths = {
  admin: {
    js: [
      'node_modules/jquery/dist/jquery.min.js',
      'node_modules/semantic-ui-css/semantic.min.js',
      '../../vendor/sylius/ui-bundle/Resources/private/js/**',
      'js/**'
    ],
    sass: [
      './scss/**/*.scss',
    ],
    css: [
      'node_modules/semantic-ui-css/semantic.min.css'
    ],
    img: []
  }
};

//gulp.task('js', bundle);
b.on('update', bundle);
b.on('log', gutil.log);

function bundle() {
  return b.bundle()
    .on('error', onError)
    .pipe(buffer())
    .pipe(sourcemaps.init())
    .pipe(gulp.src(paths.admin.js))
    .pipe(concat('bundle.js'))
    .pipe(!prod ? sourcemaps.write('.') : gutil.noop())
    .pipe(prod ? streamify(uglify()) : gutil.noop())
    .pipe(gulp.dest('./compiled'))
    .pipe(browserSync.stream());
}

gulp.task('admin-js', bundle);

gulp.task('admin-css', function () {
  gulp.src(['node_modules/semantic-ui-css/themes/**/*']).pipe(gulp.dest('./compiled/' + 'themes/'));

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
    .pipe(gulp.dest('./compiled'))
    .pipe(browserSync.stream())
    ;
});

var files = [
  '**/*.php',
  'assets/images/**/*.{png,jpg,gif}',
];

// browser sync server for live reload
gulp.task('watch', function () {
  browserSync.init(files, {
    // Proxy address
    proxy: 'http://alceane.dev/app_dev.php'
  });

  gulp.watch(paths.admin.sass, ['admin-css']);
  gulp.watch(paths.admin.js, ['admin-js']);
});

// use gulp-sequence to finish building html, sass and js before first page load
gulp.task('default', gulpSequence(['admin-css', 'admin-js'], 'watch'));