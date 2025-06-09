const webpackConfig = require('@nextcloud/webpack-vue-config')
const ESLintPlugin = require('eslint-webpack-plugin')
const StyleLintPlugin = require('stylelint-webpack-plugin')
const path = require('path')

const appId = 'integration_overleaf'
const buildMode = process.env.NODE_ENV
const isDev = buildMode === 'development'


webpackConfig.entry = {
	filesplugin: { import: path.join(__dirname, 'src', 'filesplugin.js'), filename: appId + '-filesplugin.js' },
	adminSettings: { import: path.join(__dirname, 'src', 'adminSettings.js'), filename: appId + '-adminSettings.js' },
}

webpackConfig.plugins.push(
	new ESLintPlugin({
		extensions: ['js', 'vue'],
		files: 'src',
		failOnError: !isDev,
	}),
)
webpackConfig.plugins.push(
	new StyleLintPlugin({
		files: 'src/**/*.{css,scss,vue}',
	}),
)

webpackConfig.module.rules.push({
	test: /\.svg$/i,
	type: 'asset/source',
})

module.exports = webpackConfig
