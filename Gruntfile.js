module.exports = function(grunt) {
  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    resourcesPath: 'src/AppBundle/Resources',
    resourcesAdminPath: 'src/AdminBundle/Resources',
    copy: {
      js: {
        expand: true,
        cwd: '<%= resourcesPath %>/public/js/',
        src: '**',
        dest: 'web/js'
      },
      adminjs: {
        expand: true,
        cwd: '<%= resourcesAdminPath %>/public/js/',
        src: '**',
        dest: 'web/js/admin'
      },
      fontawesome: {
            expand: true,
            cwd: 'frontend_components/fontawesome/webfonts',
            src: '**',
            dest: 'web/css/webfonts'
      },
      jquery: {
            expand: true,
            cwd: 'node_modules/jquery/dist',
            src: 'jquery.min.js',
            dest: 'web/js'
      },
      fancybox_js: {
          expand: true,
          cwd: 'web/vendor/fancyBox/dist',
          src: 'jquery.fancybox.min.js',
          dest: 'web/js'
      },
      fancybox_css: {
          expand: true,
          cwd: 'web/vendor/fancyBox/dist',
          src: 'jquery.fancybox.min.css',
          dest: 'web/css'
      },
      jqueryvalidation: {
            expand: true,
            cwd: 'web/vendor/jqueryvalidation',
            src: '**',
            dest: 'web/js/jqueryvalidation'
      },
    },
    watch: {
        less: {
            files: [
                '<%= resourcesPath %>/public/less/**/*.less',
                '<%= resourcesAdminPath %>/public/less/**/*.less'
            ],
            tasks: ['less', 'concat']
        },
        js: {
            files: [
                '<%= resourcesPath %>/public/js/**/*.js',
                '<%= resourcesAdminPath %>/public/js/**/*.js'
            ],
            tasks: ['copy']
        }
    },
    less: {
      app: {
          options: {
              paths: ['<%= resourcesPath %>/public/less', '<%= resourcesAdminPath %>/public/less'],
              compress: true
          },
          files: {
              'web/css/gowood.min.css': '<%= resourcesPath %>/public/less/gowood.less',
              'web/css/admin/admin.min.css': '<%= resourcesAdminPath %>/public/less/admin.less',
          }
      }
    },
    sass: {
      dist: {
        options: {
          compass: true,
          style: 'compressed'
        },
        files: {
          'web/css/bootstrap.css' : 'node_modules/bootstrap/scss/bootstrap.scss',
          'web/css/fontawesome.css' : 'frontend_components/fontawesome/scss/fa.scss',
        }
      }
    },

    concat: {
      styles: {
         files: {
            'web/css/styles.css': [
                'web/css/bootstrap.css',
                'web/css/fontawesome.css',
                'web/css/gowood.min.css',
                'web/css/jquery.fancybox.min.css',

            ],
         },
      }
    },

    clean: {
      css: ['web/js/*', 'web/css/*']
    }
  });

  // Load the plugin that provides the "clean" task.
  grunt.loadNpmTasks('grunt-contrib-clean');

  grunt.loadNpmTasks('grunt-contrib-copy');

  grunt.loadNpmTasks('grunt-contrib-compass');

  // Load the plugin that provides the "watch" task.
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Load the plugin that provides the "sass" task.
  grunt.loadNpmTasks('grunt-contrib-sass');

  grunt.loadNpmTasks('grunt-contrib-less');

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');

  // Load the plugin that provides the "concat" task.
  grunt.loadNpmTasks('grunt-contrib-concat');

  // Load the plugin that provides the "cssmin" task.
  grunt.loadNpmTasks('grunt-contrib-cssmin');

  // Default task(s).
  grunt.registerTask('default', ['clean', 'copy', 'sass', 'less', 'concat']);

  grunt.registerTask('dev', ['default', 'watch']);

};