module.exports = function (grunt) {
	const sass = require('sass');

	require('load-grunt-tasks')(grunt, {scope: 'devDependencies'});

	grunt.initConfig({
		sass: {
			options: {
				implementation: sass,
				sourceMap: true,
				outputStyle: 'compressed',
			},
			common: {
				files: [
					{
						expand: true,
						cwd: 'common/sass',
						src: ['*.sass', '*.scss'],
						dest: '../http/htdocs/content/themes/base/assets/css',
						ext: '.css',
						flatten: true
					},
				]
			},
			templates: {
				files: [
					{
						expand: true,
						cwd: 'templates',
						src: ['**/*.scss'],
						dest: '../http/htdocs/content/themes/base/assets/css',
						ext: '.css',
						flatten: true
					}
				]
			}
		},

		postcss: {
			options: {
				processors: [
					require('autoprefixer')({browsers: 'last 2 versions'}) // add vendor prefixes
				]
			},
			dist: {
				files: [
					{
						expand: true,
						cwd: '../http/htdocs/content/themes/base/assets/css',
						src: ['**/*.css'],
						dest: '../http/htdocs/content/themes/base/assets/css',
						ext: '.css',
						flatten: true
					}
				]
			},
			live: {
				options: {
					processors: [
						require('postcss-pxtorem')({propList: ['*']}),
					]
				},
				files: [
					{
						expand: true,
						cwd: '../http/htdocs/content/themes/base/assets/css',
						src: ['**/*.css'],
						dest: '../http/htdocs/content/themes/base/assets/css',
						ext: '.css',
						flatten: true
					}
				]
			}
		},
		
		copy: {
			templates: {
				files: [{
					expand: true,
					cwd: 'templates',
					src: ['**/*.js'],
					dest: '../http/htdocs/content/themes/base/assets/js',
					ext: '.js',
					flatten: true
				}]
			},
			other: {
				files: [
					// jquery.js
					{'../http/htdocs/content/themes/base/assets/js/jquery.js': 'node_modules/jquery/dist/jquery.min.js'},
					// glide.js
					{'../http/htdocs/content/themes/base/assets/js/glide.js': 'node_modules/@glidejs/glide/dist/glide.js'},
					// splide.js
					{'../http/htdocs/content/themes/base/assets/js/splide.js': 'node_modules/@splidejs/splide/dist/js/splide.js'},
					// EasePick
					{'../http/htdocs/content/themes/base/assets/js/easepick.js': 'node_modules/@easepick/bundle/dist/index.umd.js'},
					{'../http/htdocs/content/themes/base/assets/css/easepick.css': 'node_modules/@easepick/bundle/dist/index.css'},
					// ReModal
					{'../http/htdocs/content/themes/base/assets/js/remodal.js': 'node_modules/remodal/dist/remodal.js'},
					{'../http/htdocs/content/themes/base/assets/css/remodal.css': 'node_modules/remodal/dist/remodal.css'},
					// mapbox-gl.js
					{'../http/htdocs/content/themes/base/assets/js/mapbox-gl.js': 'node_modules/mapbox-gl/dist/mapbox-gl.js'},
					// mapbox-gl.css
					{'../http/htdocs/content/themes/base/assets/css/mapbox-gl.css': 'node_modules/mapbox-gl/dist/mapbox-gl.css'},
				]
			}
		},

		concat: {
			general: {
				files: {
					// common.js
					'../http/htdocs/content/themes/base/assets/js/common.js': [
						// Lazy loading
						'node_modules/lazysizes/lazysizes.js',
						// Focus visible
						'node_modules/focus-visible/dist/focus-visible.js',
						// Theme
						'common/js/common.js'
					],
					// bootstrap.js
					// todo: Uncomment to use
					// '../http/htdocs/content/themes/base/assets/js/bootstrap.js': [
					// 	'node_modules/bootstrap/js/dist/collapse.js',
					// ]
				}
			}
		},

		svgmin: {
			options: {
				plugins: [
					{ removeViewBox: false },
					{ removeUselessStrokeAndFill: false },
					{ removeAttrs: {
							attrs: [
								'svg:width',
								'svg:height',
							]
						}
					},
					{ customStyling: {
							type: 'full',
							description: 'Add an ID and class to each SVG and fill="currentColor" to each path.',
							params: {},
							fn: function (data, params, extra) {
								let name = extra.path.split('/').pop().replace(/\.\w+$/, ''),
								svg = data.content[0];
								if (svg.isElem('svg')) {
									svg.attrs.id = {
										name: 'id',
										value: name,
										prefix: 'id',
										local: ''
									};

									svg.class.add.apply(svg.class, ['v-icon__icon', 'v-icon__icon--' + name]);

									// If icon is not a two color icon
									if (!extra.path.includes('two-color')) {
										for (i = 0; i < svg.content.length; i++) {
											// if (svg.content[i].elem === 'path') {
											// amended to include other elements for more complext icons
											if (svg.content[i].elem === 'path' || svg.content[i].elem === 'g' || svg.content[i].elem === 'defs') {
												svg.content[i].attrs.fill = {
													name: 'fill',
													value: 'currentColor',
													prefix: '',
													local: 'fill'
												};
											}
										}
									}
								}

								return data;
							}
						}
					}
				]
			},
			dist: {
				expand: true,
				cwd: 'icons/',
				src: ['*.svg'],
				dest: '../http/htdocs/content/themes/base/assets/img/icons',
				ext: '.svg',
				extDot: 'last',
			}
		},

		watch: {
			common: {
				files: [
					'common/sass/**'
				],
				tasks: ['sass:common', 'postcss:dist']
			},
			templates: {
				files: [
					'templates/**'
				],
				tasks: ['newer:sass:templates', 'newer:postcss:dist', 'newer:copy:templates']
			},
			scripts: {
				files: [
					'common/js/*.js',
				],
				tasks: ['concat']
			},
			icons: {
				files: [
					'icons/*.svg'
				],
				tasks: ['svgmin']
			}
		}
	});

	grunt.registerTask('build', ['svgmin', 'sass', 'copy', 'postcss:dist', 'concat']);
	grunt.registerTask('default', ['watch']);
};
