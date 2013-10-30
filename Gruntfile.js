module.exports = function(grunt) {

	'use strict';

	var

		config = grunt.file.readJSON('config.json'),

		js = config.path.js,

		scss = config.path.scss,

		image = config.path.image,

		sprite = config.path.sprite,

		uglify = {
			plugins : {},
			js : {},
			files : {
				plugins : [],
				js : []
			}
		},

		imagemin = {},

		fs = require('fs'),

		images = fs.readdirSync(image);

	for(var file in config.uglify.files.plugins) uglify.files.plugins[file] = js.plugins + '/' + config.uglify.files.plugins[file];

	for(var file in config.uglify.files.application) uglify.files.js[file] = js.application + '/' + config.uglify.files.application[file];

	for(var file in images) if(/.*\.(pn|jp)g$/.test(images[file])) imagemin[image + '/' + images[file]] = image + '/' + images[file];

	uglify.plugins[js.application + '/plugins.min.js'] = uglify.files.plugins;

	uglify.js[js.application + '/application.min.js'] = uglify.files.js;

	grunt.config.init({
		uglify : {
			plugins : {
				files : uglify.plugins,
				options : {
					banner : '// ' + config.uglify.banner + '\n'
				}
			},
			js : {
				files : uglify.js,
				options : {
					banner : '// ' + config.uglify.banner + '\n'
				}
			}
		},
		jshint : {
			js : {
				src : uglify.files.js,
				options : config.jshint.options
			}
		},
		compass : {
			init : {
				options : {
					config : 'config.rb'
				}
			}
		},
		imagemin : {
			init : {
				options : {
					optimizationLevel : config.imagemin.optimizationLevel
				},
				files : imagemin
			}
		},
		watch : {
			js : {
				files : [js.application + '/application.js'],
				tasks : ['uglify:js', 'jshint:js']
			},
			plugins : {
				files : [js.plugins + '/*.js'],
				tasks : ['uglify:plugins']
			},
			compass : {
				files : [scss + '/*.scss', sprite + '*.png'],
				tasks : ['compass:init']
			},
			images : {
				files : [image + '/*.jpg', image + '*.png'],
				tasks : ['imagemin:init']
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-uglify');

	grunt.loadNpmTasks('grunt-contrib-jshint');

	grunt.loadNpmTasks('grunt-contrib-compass');

	grunt.loadNpmTasks('grunt-contrib-imagemin');

	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask('default', ['compass:init', 'imagemin:init', 'uglify:plugins', 'uglify:js', 'jshint:js']);

	grunt.registerTask('images', ['imagemin:init']);

	/*

		grunt uglify:js
		grunt uglify:plugins
		grunt compass
		grunt images
		grunt watch:js
		grunt watch:plugins
		grunt watch:compass
		grunt watch:images

	*/
};