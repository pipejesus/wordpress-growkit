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
		watch: {
			sass: {
				files: ['sass/**/*.{scss,sass}'],
				tasks: ['newer:sass:dist']
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
				outputStyle: 'expanded'
			},
			dist: {
				files : [
					{
						src : ['**/*.scss', '!**/_*.scss'],
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
	grunt.loadNpmTasks('grunt-newer');
};
