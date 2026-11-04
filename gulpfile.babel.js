import chug from 'gulp-chug';
import gulp from 'gulp';
import yargs from 'yargs';

const { argv } = yargs
    .options({
      rootPath: {
        description: '<path> path to public assets directory',
        type: 'string',
        requiresArg: true,
        required: false,
      },
      nodeModulesPath: {
        description: '<path> path to node_modules directory',
        type: 'string',
        requiresArg: true,
        required: false,
      },
    });

const config = [
  '--rootPath',
  argv.rootPath || '../../public/assets',
  '--nodeModulesPath',
  argv.nodeModulesPath || '../../node_modules',
];

export const buildApp = function buildApp() {
  return gulp.src('assets/frontend/gulpfile.babel.js', { read: false })
      .pipe(chug({ args: config, tasks: 'build' }));
};
buildApp.description = 'Build app assets.';

export const watchApp = function watchApp() {
  return gulp.src('assets/frontend/gulpfile.babel.js', { read: false })
      .pipe(chug({ args: config, tasks: 'watch' }));
};
watchApp.description = 'Watch app asset sources and rebuild on changes.';

export const build = buildApp;
build.description = 'Build assets.';

gulp.task('app', buildApp);
gulp.task('app-watch', watchApp);

export default build;
