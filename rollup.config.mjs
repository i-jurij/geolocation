// rollup.config.mjs
import terser from '@rollup/plugin-terser';

export default {
	input: './src/js/getLocation.js',
	output: [
		{
			file: './build/geolocation.js',
			format: 'es'
		},
		{
			file: './build/geolocation.min.js',
			format: 'es',
			name: 'version',
			plugins: [terser()]
		}
	]
};