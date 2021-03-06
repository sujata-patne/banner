module.exports = function(grunt) {

  grunt.loadNpmTasks('grunt-shell');
  grunt.loadNpmTasks('grunt-open');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-connect');
  grunt.loadNpmTasks('grunt-karma');
  grunt.loadNpmTasks('grunt-reload');

  grunt.initConfig({
    shell: {
      options : {
        stdout: true
      },
      npm_install: {
        command: 'npm install'
      },
      bower_install: {
        command: './node_modules/.bin/bower install'
      },
      font_awesome_fonts: {
        command: 'cp -R bower_components/components-font-awesome/font app/font'
      }
    },

    reload: {
        port: 8888,
        proxy: {
            host: 'localhost'
        }
    },
    connect: {
      options: {
        base: ''
      },
      webserver: {
        options: {
          port: 8888,
          keepalive: true
        }
      },
      devserver: {
        options: {
          port: 8888
        }
      },
      testserver: {
        options: {
          port: 9999
        }
      },
      coverage: {
        options: {
          base: 'coverage/',
          port: 5555,
          keepalive: true
        }
      }
    },

    open: {
      devserver: {
        path: 'http://localhost:8888'
      },
      coverage: {
        path: 'http://localhost:5555'
      }
    },

    karma: {
      unit: {
        configFile: './test/karma-unit.conf.js',
        autoWatch: true,
        singleRun: false
      },
      unit_auto: {
        configFile: './test/karma-unit.conf.js'
      }

    },

    watch: {
      assets: {
        options: {
            livereload: true
          },
        files: ['app/styles/**/*.css',
                'bower_components/angularjs-datetime-picker/angularjs-datetime-picker.js',
            'app/scripts/**/*.js','app/templates/**/*.html','app/templates/**/**/*.html'],
        tasks: ['concat']
      }
    },

    concat: {
      styles: {
        dest: './app/assets/app.css',
        src: [
          'app/styles/reset.css',
          'bower_components/components-font-awesome/css/font-awesome.css',
          'bower_components/bootstrap/dist/css/bootstrap.css',
          'bower_components/angularjs-datetime-picker/angularjs-datetime-picker.css',
          'app/styles/app.css',
          'app/libs/bootstrap-datetime/css/bootstrap-datetimepicker.min.css',
          'bower_components/ngprogress/ngProgress.css'
        ]
      },
      scripts: {
        options: {
          separator: ';'
        },
        dest: './app/assets/app.js',
        src: [
          'bower_components/bootstrap/dist/js/jquery-2.0.0.min.js',
          'bower_components/bootstrap/dist/js/bootstrap.min.js',
          'app/libs/bootstrap-datetime/js/bootstrap-datetimepicker.js',
          'bower_components/moment/moment.js',
          'bower_components/angular/angular.js',
          'bower_components/multifiile-upload/src/angular-multiple-file-upload.js',          
          'bower_components/angular-storage/angular-storage.js',
          'bower_components/angularjs-datetime-picker/angularjs-datetime-picker.js',
          'app/libs/ui-bootstrap-tpls-1.2.4.min.js',
          'bower_components/angular-route/angular-route.js',
          'bower_components/angular-ui-router/release/angular-ui-router.min.js',
          'bower_components/angularjs-scope.safeapply/src/Scope.SafeApply.js',
          'bower_components/ngprogress/build/ngprogress.js',
          'bower_components/angular-filter/dist/angular-filter.min.js',
          'app/scripts/lib/router.js',
          'app/scripts/config/config.js',
          'app/scripts/services/**/*.js',
          'app/scripts/directives/**/*.js',
          'app/scripts/controllers/*.js',
          'app/scripts/controllers/**/*.js',
          'app/scripts/filters/**/*.js',
          'app/scripts/config/routes.js',
          'app/scripts/app.js'
        ]
      }
    }
  });

  grunt.registerTask('test', ['connect:testserver','karma:unit']);
  grunt.registerTask('test:unit', ['karma:unit']);
  grunt.registerTask('test:midway', ['connect:testserver','karma:midway']);
  grunt.registerTask('test:e2e', ['connect:testserver', 'karma:e2e']);

  //keeping these around for legacy use
  grunt.registerTask('autotest', ['autotest:unit']);
  grunt.registerTask('autotest:unit', ['connect:testserver','karma:unit_auto']);
  grunt.registerTask('autotest:midway', ['connect:testserver','karma:midway_auto']);
  grunt.registerTask('autotest:e2e', ['connect:testserver','karma:e2e_auto']);

  //installation-related
  grunt.registerTask('install', ['shell:npm_install','shell:bower_install','shell:font_awesome_fonts']);

  //defaults
  grunt.registerTask('default', ['local']);

  //development
  grunt.registerTask('dev', ['concat', 'connect:devserver', 'open:devserver', 'watch:assets']);

  //server daemon
  grunt.registerTask('serve', ['connect:webserver']);
};
