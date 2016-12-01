
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
                files: ['templates/*.php'],
                tasks: ['copy:php'],
                options: {
                  spawn: false,
                  livereload: true
                }
            },
            sass: {
                files: [ 'sass/{,*/}*.scss' ],
                tasks: [ 'sass:dev', 'autoprefixer:dev'],
                options: {
                  spawn: false,
                  livereload: true
                }
            },
            js: {
                files: [ 'js/{,*/}*.js', '!js/combined*.js' ],
                tasks: [ 'uglify:all', 'copy:js' ],
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
                  src: ['templates/*.php', 'templates/*.html'],
                  dest: '../build/'
                }]
            },
            js: {
                files: [{
                  expand: true,
                  flatten: true,
                  src: ['js/development/*.js', 'js/vendor/*.js'],
                  dest: '../build/js/'
                }]
            },
            images: {
              files: [{
                expand: true,
                flatten: true,
                src: ['images/**/*.jpg','images/**/*.png'],
                dest: '../build/images/'
              }]
            }
        },
        // Combines and minifies JS files
        uglify: {
          options: {
            mangle: false,
            compress: true,
            preserveComments: 'some'
          },
          all: {
            files: [{
              expand: true,
              flatten: true,
              src: ['js/resources/*.js', '!*.min.js'],
              dest: 'js/development/',
            }]
          }
        },
        // Clean build files
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
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-clean');

    // ---------------------------------------------------------------------
    // Register tasks
    // ---------------------------------------------------------------------

    // The default task just runs build
    grunt.registerTask('default', ['sass', 'autoprefixer', 'uglify', 'clean', 'copy', 'watch']);

};
