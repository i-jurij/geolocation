// rollup.config.mjs
import terser from '@rollup/plugin-terser';
import css from "rollup-plugin-import-css";
import { nodeResolve } from '@rollup/plugin-node-resolve';
import copy from 'rollup-plugin-copy'

export default {
	input: 'src/geolocation.js',
	output: [
		{
			file: 'build/geolocation.es.js',
			format: 'es' //'es', 'umd', 'iife', 'cjs', 'amd'
		},
		{
			file: 'build/geolocation.iife.min.js',
			format: 'iife',
			name: 'version',
			plugins: [terser()]
		}
	],
	plugins: [
		css({
			minify: true,
			inject: true
		}),
		nodeResolve(),
		copy({
			targets: [
			  { src: 'build/geolocation.iife.min.js', dest: 'example/public' },
			]
		  })
	]
};
