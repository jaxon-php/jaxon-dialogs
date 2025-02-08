// Gulp.js configuration

// Modules
const { src, dest, series } = require('gulp');
const concat = require('gulp-concat');
// const deporder = require('gulp-deporder');
const rename = require('gulp-rename');
// const stripdebug = require('gulp-strip-debug');
const terser = require('gulp-terser');

// Folders and files
const folders = {
    src: './js/',
    dist: './dist/',
};
const files = {
    src: {
        libs: [
            'alertify',
            'bootbox',
            'bootstrap3',
            'bootstrap4',
            'bootstrap5',
            'cute',
            'jalert',
            'jconfirm',
            'notify',
            'noty',
            'sweetalert',
            'tingle',
            'toastr',
        ],
    },
    dist: {
        all: 'dialogs.all.js',
    },
    min: {
        all: 'dialogs.all.min.js',
    },
};

// Concat each library js file
const js_libs_min = () => src(files.src.libs.map((file) => folders.src + file + '.js'))
    // .pipe(stripdebug())
    .pipe(terser())
    .pipe(rename({ extname: '.min.js' }))
    .pipe(dest(folders.src));

// Concat all the js files
const js_all = () => src(files.src.libs.map((file) => folders.src + file + '.js'))
    // .pipe(deporder())
    .pipe(concat(files.dist.all, {newLine: "\n\n"}))
    .pipe(dest(folders.dist));

// Minify the resulting file
const js_all_min = () => src(folders.dist + files.dist.all)
    // .pipe(stripdebug())
    .pipe(terser())
    .pipe(rename({ extname: '.min.js' }))
    .pipe(dest(folders.dist));

exports.default = series(js_libs_min, js_all, js_all_min);
exports.js_all = js_all;
