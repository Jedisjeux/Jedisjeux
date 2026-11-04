import concat from 'gulp-concat';
import gulp from 'gulp';
import gulpif from 'gulp-if';
import livereload from 'gulp-livereload';
import gulpSass from 'gulp-sass';
import dartSass from 'sass';
import sourcemaps from 'gulp-sourcemaps';
import uglifycss from 'gulp-uglifycss';
import upath from 'upath';
import yargs from 'yargs';

const sass = gulpSass(dartSass);

const { argv } = yargs
  .options({
    rootPath: {
      description: '<path> path to web assets directory',
      type: 'string',
      requiresArg: true,
      required: true,
    },
    nodeModulesPath: {
      description: '<path> path to node_modules directory',
      type: 'string',
      requiresArg: true,
      required: true,
    },
  });

const env = process.env.GULP_ENV;
const options = {
  minify: env === 'prod',
  sourcemaps: env !== 'prod',
};

const rootPath = upath.normalizeSafe(argv.rootPath);
const adminRootPath = upath.joinSafe(rootPath, 'backend');
const adminPath = rootPath === '.' ? '.' : upath.joinSafe(rootPath, '../../assets/backend');
const nodeModulesPath = upath.normalizeSafe(argv.nodeModulesPath);

// The Bootstrap Admin UI bundle ships the admin theme, these assets only add the Jedisjeux specific parts
const paths = {
  admin: {
    js: [
      upath.joinSafe(nodeModulesPath, 'apexcharts/dist/apexcharts.min.js'),
      upath.joinSafe(adminPath, 'js/**'),
    ],
    sass: [upath.joinSafe(adminPath, 'sass/**')],
  },
};

export const buildAdminJs = function buildAdminJs() {
  return gulp.src(paths.admin.js)
    .pipe(gulpif(options.sourcemaps, sourcemaps.init()))
    .pipe(concat('app.js'))
    .pipe(gulpif(options.sourcemaps, sourcemaps.write('./')))
    .pipe(gulp.dest(upath.joinSafe(adminRootPath, 'js')))
    .pipe(livereload());
};
buildAdminJs.description = 'Build admin js assets.';

export const buildAdminCss = function buildAdminCss() {
  return gulp.src(upath.joinSafe(adminPath, 'sass/main.scss'))
    .pipe(gulpif(options.sourcemaps, sourcemaps.init()))
    .pipe(sass())
    .pipe(concat('app.css'))
    .pipe(gulpif(options.minify, uglifycss()))
    .pipe(gulpif(options.sourcemaps, sourcemaps.write('./')))
    .pipe(gulp.dest(upath.joinSafe(adminRootPath, 'css')))
    .pipe(livereload());
};
buildAdminCss.description = 'Build admin css assets.';

export const watchAdmin = function watchAdmin() {
  livereload.listen();

  gulp.watch(paths.admin.js, buildAdminJs);
  gulp.watch(paths.admin.sass, buildAdminCss);
};
watchAdmin.description = 'Watch admin asset sources and rebuild on changes.';

export const build = gulp.parallel(buildAdminJs, buildAdminCss);
build.description = 'Build assets.';

export const watch = gulp.parallel(build, watchAdmin);
watch.description = 'Watch asset sources and rebuild on changes.';

gulp.task('admin-js', buildAdminJs);
gulp.task('admin-css', buildAdminCss);
gulp.task('admin-watch', watchAdmin);

export default build;
