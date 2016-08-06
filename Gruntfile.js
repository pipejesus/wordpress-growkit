/**
 *
 * Credit: Ben Frain
 * https://benfrain.com/lightning-fast-sass-compiling-with-libsass-node-sass-and-grunt-sass/
 * Adapted to watch for file changes in subdirectories too
 *
 */

module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		autoprefixer: {
		  options: {
		  	browsers: ['last 20 versions'],
		  },
		  dist: {
		  	expand: true,
		  	flatten: true,
		  	src: 'css/*.css',
		  	dest: 'css/'
		  }
		},
		watch: {
			sass: {
				files: ['sass/**/*.{scss,sass}'],
				tasks: ['sass:dist', 'autoprefixer:dist'],
			},
			livereload: {
				files: ['**/*.html', '**/*.php', 'js/**/*.{js,json}', 'css/**/*.css','img/**/*.{png,jpg,jpeg,gif,webp,svg}'],
				options: {
					livereload: true
				}
			}
		},
		sass: {
			options: {
				sourceMap: true,
				outputStyle: 'expanded',
				precision: 8
			},
			dist: {
				// files: { 'css/main.css' : 'sass/main.scss', 'css/growgrid.css' : 'sass/growgrid.scss' }
				files : [
					{
						src : ['**/*.scss'], //,'!**/_*.scss' 
						cwd : 'sass',
						dest : 'css',
						ext : '.css',
						expand : true
					}
				],
			}
		}
	});

	grunt.registerTask('default', ['sass:dist', 'watch']);
	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-autoprefixer');

};
