

import gulp from 'gulp';
import yargs from 'yargs';
import plugins from 'gulp-load-plugins';
import source from 'vinyl-source-stream';
import buffer from 'vinyl-buffer';
import yaml from 'js-yaml';
import browserify from 'browserify';
import babelify from 'babelify';
import fs from 'fs';
import image from 'gulp-image';
import watch from 'gulp-watch';
import livereload from 'gulp-livereload';

// Load all Gulp plugins into one variable
const $ = plugins();
// Check for --production flag
// const PRODUCTION = !!(yargs.argv.production);
const PRODUCTION = true;
// Load settings from settings.yml
const {
  COMPATIBILITY,
  PORT,
  PATHS,
} = loadConfig();

function loadConfig() {
  const ymlFile = fs.readFileSync('config.yml', 'utf8');
  return yaml.load(ymlFile);
}

// Build all the assets
gulp.task('build', ['build-css', 'build-js', 'compress-img']);

// Compress all the images
gulp.task('compress-img', () =>
  gulp.src(`${PATHS.src}/img/**/*`)
    .pipe($.image())
    .pipe(gulp.dest(`${PATHS.build}/img`)));

// Compile and minify the sass files
gulp.task('compile-scss', () =>
  gulp.src(`${PATHS.src}/scss/100x100.scss`)
    .pipe($.sass().on('error', $.sass.logError))
    .pipe(gulp.dest(`${PATHS.src}/dist`)));

gulp.task('minify-css', () =>
  gulp.src(`${PATHS.src}/dist/100x100.css`)
    .pipe($.concat('100x100.css'))
    .pipe($.cleanCss())
    .pipe($.rename({
      suffix: '.min',
    }))
    .pipe(gulp.dest(`${PATHS.build}/css`))
    .pipe(livereload()));

gulp.task('build-css', ['compile-scss', 'minify-css']);

// Compile the js files
gulp.task('browserify', () => {
  const bundler = browserify(`${PATHS.src}/js/100x100.js`).transform(babelify, {
    presets: ['es2015'],
  });

  return bundler.bundle()
    .pipe(source('100x100.js'))
    .pipe(buffer())
    .pipe($.if(!PRODUCTION, $.sourcemaps.init({
      loadMaps: true,
    })))
    .pipe($.uglify())
    .pipe($.rename('bundle.js'))
    .pipe($.if(!PRODUCTION, $.sourcemaps.write('./')))
    .pipe(gulp.dest(`${PATHS.src}/dist`));
});

// Move map file
gulp.task('moveMapFile', () => {
  gulp.src([`${PATHS.src}/js/bundle.js.map`])
    .pipe(gulp.dest(`${PATHS.build}/js`));
});

// Minify the js files
gulp.task('build-js', ['browserify'], () =>
  gulp.src(`${PATHS.src}/dist/bundle.js`)
    .pipe($.concat('100x100.min.js'))
    .pipe(gulp.dest(`${PATHS.build}/js`)));

gulp.task('scripts', () => gulp.src(`${PATHS.src}/js/100x100.js`)
  .pipe($.concat('main.js'))
  .pipe(gulp.dest(`${PATHS.build}/js/`))
  .pipe(gulp.dest('./src/js/'))
  .pipe($.uglify())
  .pipe($.rename({ extname: '.min.js' }))
  .pipe(gulp.dest('./src/js/'))
  .pipe(gulp.dest(`${PATHS.build}/js/`)));


gulp.task('default', () => {
  livereload.listen();
  gulp.watch(`${PATHS.src}/scss/**/*.scss`, ['build-css']);
  gulp.watch(`${PATHS.src}/js/**/*.js`, ['build-js']);
});
