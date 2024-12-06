// rollup.config.mjs
import terser from '@rollup/plugin-terser';
import css from "rollup-plugin-import-css";

export default {
	input: 'src/js/getLocation.js',
	output: [
		{
			file: 'build/geolocation.js',
			format: 'es'
		},
		{
			file: 'build/geolocation.min.js',
			format: 'esm',
			name: 'version',
			plugins: [terser()]
		}
	],
	plugins: [css({
		minify: true,
		inject: true
	})
	]
};
