import { createAppConfig } from '@nextcloud/vite-config'
import { join, resolve } from 'path'
import eslint from 'vite-plugin-eslint'

const isProduction = process.env.NODE_ENV === 'production'

export default createAppConfig(
	{
		filesplugin: resolve(join('src', 'filesplugin.js')),
		adminSettings: resolve(join('src', 'adminSettings.js')),
	}, {
		config: {
			css: {
				modules: {
					localsConvention: 'camelCase',
				},
				preprocessorOptions: {
					scss: {
						api: 'modern-compiler',
					},
				},
			},
			plugins: [eslint()],
			build: {
				cssCodeSplit: true,
			},
		},
		inlineCSS: { relativeCSSInjection: true },
		minify: isProduction,
	})
