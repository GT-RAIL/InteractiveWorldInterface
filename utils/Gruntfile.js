module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      files: [
        'Gruntfile.js',
        '../src/*.js',
        '../src/**/*.js'
      ]
    },
    csslint: {
      build: {
        options: {
          csslintrc: '.csslintrc'
        },
        src: [
          '../src/*.css',
          '../src/**/*.css'
        ]
      }
    },
    cssmin: {
      options: {
        report: 'min'
      },
      build: {
        files: {
          '../build/style.css': ['../build/style.css']
        }
      }
    },
    clean: {
      options: {
        force: true
      },
      doc: ['../doc/js']
    },
    jsdoc: {
      doc: {
        src: [
          '../src/*.js',
          '../src/**/*.js'
        ],
        options: {
          destination: '../doc/js'
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-csslint');
  grunt.loadNpmTasks('grunt-jsdoc');
  grunt.loadNpmTasks('grunt-karma');

  grunt.registerTask('build', ['jshint', 'csslint', 'cssmin']);
  grunt.registerTask('doc', ['clean', 'jsdoc']);
};

