// rollup.config.mjs
import terser from '@rollup/plugin-terser';
import css from "rollup-plugin-import-css";
import resolve from '@rollup/plugin-node-resolve';

export default {
	input: 'src/js/getLocation.js',
	output: [
		{
			file: 'build/geolocation.js',
			format: 'umd' //'es'
		},
		{
			file: 'build/geolocation.min.js',
			format: 'umd', //'esm',
			name: 'version',
			plugins: [terser()]
		}
	],
	plugins: [
		css({
			minify: true,
			inject: true
		}),
		resolve()
	],
	moduleContext: (id) => {
		const modules = ["src/js/autocomplete.js-10.2.9/dist/autoComplete.min.js"];

		if (modules.some(_id => id.trim().endsWith(id))) {
			return "window";
		}

		return undefined;
	}

};
