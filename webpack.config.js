const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('semantic_styles', './node_modules/semantic-ui/dist/semantic.min.css')
    .addEntry('semantic_javascripts', './node_modules/semantic-ui/dist/semantic.min.js')
    .addEntry('awesome_notifications_css', './node_modules/awesome-notifications/dist/style.css')
    .addEntry('awesome_notifications_js', './node_modules/awesome-notifications/dist/index.var.js')
    .addEntry('textarea_markdown', './node_modules/textarea-markdown/lib/textarea-markdown.js')
    .addEntry('codemirror_css', './node_modules/codemirror/lib/codemirror.css')
    .addEntry('codemirror_js', './node_modules/codemirror/lib/codemirror.js')
    .addEntry('markdown_it', './node_modules/markdown-it/dist/markdown-it.min.js')
    .addEntry('highlight_css', './node_modules/highlight.js/styles/default.css')
    .addEntry('highlight_js', './node_modules/highlight.js/lib/highlight.js')
    .addEntry('bible', './assets/app.js')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })

    // enables @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()

    // enable Vue
    .enableVueLoader()
;

module.exports = Encore.getWebpackConfig();
