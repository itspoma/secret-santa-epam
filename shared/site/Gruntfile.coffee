module.exports = (grunt) ->
    grunt.loadNpmTasks 'grunt-contrib-clean'
    grunt.loadNpmTasks 'grunt-contrib-less'
    grunt.loadNpmTasks 'grunt-autoprefixer'
    grunt.loadNpmTasks 'grunt-contrib-coffee'
    grunt.loadNpmTasks 'grunt-contrib-uglify'

    serverDirectory = './web'

    grunt.initConfig(
        'constants':
            banner: '/* <%= grunt.template.today("yyyy-mm-dd HH:mm:ss") %> */'

        'clean':
            options: { force: true }
            'before-css': [ "#{serverDirectory}/assets/*.css" ]
            'before-js': [ "#{serverDirectory}/assets/*.js" ]

        'less':
            options:
                compress: true
            css:
                files: [
                    expand: true
                    cwd:    "#{serverDirectory}/css"
                    src:    ['page-*.less']
                    dest:   "#{serverDirectory}/assets"
                    ext:    '.css'
                ]

        'coffee':
            options:
                bare: true
                join: false
            js:
                files: [
                    expand: true
                    cwd:    "#{serverDirectory}/js"
                    src:    ['*.coffee']
                    dest:   "#{serverDirectory}/assets"
                    ext:    '.js'
                ]

        'uglify':
            options:
                banner: '<%= constants.banner %>\n'
                compress: true
                beautify: false
                preserveComments: false
            dest:
                files: [
                    expand: true
                    cwd: "#{serverDirectory}/assets"
                    src: '*.js'
                    dest: "#{serverDirectory}/assets"
                ]

        'autoprefixer':
            options:
                diff: !true
            dest:
                files: [
                    expand: true
                    cwd: "#{serverDirectory}/assets"
                    src: '*.css'
                    dest: "#{serverDirectory}/assets"
                ]

    )

    grunt.registerTask 'build-css', [
        'clean:before-css'
        'less'
        'autoprefixer'
    ]

    grunt.registerTask 'build-js', [
        'clean:before-js'
        'coffee'
        'uglify'
    ]

    grunt.registerTask 'build-all', [
        'build-css'
        'build-js'
    ]

    grunt.registerTask 'default', 'build-all'