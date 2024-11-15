const path = require('path')
const mix = require('laravel-mix')
// const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer')

mix
    .js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sourceMaps()
    .disableNotifications()


if (!mix.inProduction()) {
    mix.extract([
            'bootstrap',
            'vue-router'
        ])
}

if (mix.inProduction()) {
    mix.version()

    mix.extract([
        'vue',
        'vform',
        'axios',
        'vuex',
        'jquery',
        'popper.js',
        'vue-i18n',
        'vue-meta',
        'js-cookie',
        'bootstrap',
        'vue-router',
        'sweetalert2',
        'vuex-router-sync',
        '@fortawesome/fontawesome',
        '@fortawesome/vue-fontawesome'
    ])
}

mix.webpackConfig({
    plugins: [
        // new BundleAnalyzerPlugin()
    ],
    resolve: {
        extensions: ['.js', '.json', '.vue'],
        alias: {
            'vuejs-datatable': 'vuejs-datatable/dist/vuejs-datatable.esm.js',
            '~': path.join(__dirname, './resources/assets/js')
        }
    },
    output: {
        chunkFilename: 'js/[name].[chunkhash].js',
        publicPath: mix.config.hmr ? '//localhost:8080' : '/'
    }
})
