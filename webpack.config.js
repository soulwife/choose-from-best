var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .autoProvidejQuery()
    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: false
    })
    .enableVersioning(false)
    // show OS notifications when builds finish/fail
    .enableBuildNotifications()
    .createSharedEntry('vendor', ['jquery', 'bootstrap'])
    .addEntry('js/app', './assets/js/app.js')
    .addEntry('js/admin', './assets/js/admin.js')
    .addStyleEntry('css/app', ['./assets/css/app.scss'])
;

module.exports = Encore.getWebpackConfig();