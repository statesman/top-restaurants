module.exports = function(grunt) {

  'strict mode';

  // Project config
  grunt.initConfig({

    // Transpile LESS
    less: {
      all: {
        files: {
          "dist/styles.css": "src/css/app.less"
        }
      }
    },

    // Watch files for changes, rebuild and livereload accordingly
    watch: {
      options: {
        livereload: true
      },
      css: {
        files: ['src/css/**.less'],
        tasks: ['less']
      },
      markup: {
        files: ['web/views/**.twig']
      }
    }

  });

  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-connect');
  grunt.loadNpmTasks('grunt-contrib-watch');

};
