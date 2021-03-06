
module.exports = function(grunt) {

    grunt.initConfig({
        // LESS compilation
        sass: {
            dev: {
                files: {
                    "../build/css/styles.css": "sass/styles.scss"
                }
            }
        },
        // Adds vendor prefixes to CSS
        autoprefixer: {
          dev: {
            src: '../build/css/*.css'
          }
        },
        watch: {
            php: {
                files: ['templates/*','resources/*','components/*'],
                tasks: ['copy:php'],
                options: {
                  spawn: false,
                  livereload: true
                }
            },
            sass: {
                files: [ 'sass/*.scss' ],
                tasks: [ 'sass:dev', 'autoprefixer:dev'],
                options: {
                  spawn: false,
                  livereload: true
                }
            },
            js: {
                files: [ 'js/{,*/}*.js', '!js/combined*.js' ],
                tasks: [ 'copy:js' ],
                options: {
                  spawn: false,
                  livereload: true
                }
            }
        },
        // Copies these files into build folder
        copy: {
            php: {
                files: [{
                  expand: true,
                  flatten: true,
                  src: ['templates/*'],
                  dest: '../build/'
                }]
            },
            resources: {
                files: [{
                  expand: true,
                  flatten: true,
                  src: ['resources/*'],
                  dest: '../build/resources/'
                }]
            },
            components: {
                files: [{
                  expand: true,
                  flatten: true,
                  src: ['components/*'],
                  dest: '../build/components/'
                }]
            },
            js: {
                files: [{
                  expand: true,
                  flatten: true,
                  src: ['js/*.js'],
                  dest: '../build/js/'
                }]
            },
            css: {
                files: [{
                  expand: true,
                  flatten: true,
                  src: ['css/*.css'],
                  dest: '../build/css/'
                }]
            }
        },
        clean: {
          src: ['build/**']
        }
    });

    // ---------------------------------------------------------------------
    // Load tasks
    // ---------------------------------------------------------------------
    //require('load-grunt-tasks')(grunt);
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-clean');

    // ---------------------------------------------------------------------
    // Register tasks
    // ---------------------------------------------------------------------

    // The default task just runs build
    grunt.registerTask('default', ['sass', 'autoprefixer', 'clean', 'copy', 'watch']);

};
