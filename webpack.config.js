/*jshint strict:false */
/*jslint node: true */
var Encore = require('@symfony/webpack-encore');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const BrowserSyncConfig = require('./webpack.config.browsersync.js');
const url = require("url");
const fileSync = require("fs");

Encore
    .setOutputPath('public/layout')
    .setPublicPath('/layout')
    .setManifestKeyPrefix('plentatheme')
    // User Contao's jQuery
    .addExternals({
        jquery: 'jQuery'
    })

    .addEntry('layout', './layout/js/layout.js')
    .addStyleEntry('styles', './layout/scss/style.scss')
    .addEntry('tinymce', './layout/js/tinymce.js')
    .addEntry('selectric', './layout/js/selectric.js')
    //.splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    //.enableSingleRuntimeChunk()
    .disableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableSassLoader()
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    /*.configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })*/
    .configureBabel(function(babelConfig) {
        babelConfig.plugins.push('@babel/plugin-transform-runtime');
    }, {})

    .addPlugin(new CopyWebpackPlugin({
        patterns: [
            {from: './layout/static', to: 'static'}
        ],
    }))

    .addPlugin(
        new BrowserSyncPlugin({
            // browse to http://localhost:3000/ during development,
            // ./public directory is being served
            host: BrowserSyncConfig.BrowserSyncHost,
            port: BrowserSyncConfig.BrowserSyncPort,
            //server: { baseDir: ['layout'] },
            proxy: BrowserSyncConfig.BrowserSyncProxy,
            browser: BrowserSyncConfig.BrowserSyncBrowser,
            serveStatic: [{
                route: '/layout/static',
                dir: 'public/layout/static'
            }],
            middleware: [
                function (req, res, next) {
                    var parsed = url.parse(req.url);
                    var match = parsed.pathname.match(/layout\/(.+)\.(.+)\.css$/);
                    if (match) {
                        var path = 'public/layout/'+match[1]+'.css';
                        try {
                            if (fileSync.existsSync(path)) {
                                var text = fileSync.readFileSync(path).toString('utf-8');
                                res.setHeader('Content-Type', 'text/css');
                                res.end(text);
                            }
                        } catch (err) {}

                        return;
                    }
                    next();
                },
                function (req, res, next) {
                    var parsed = url.parse(req.url);
                    var match = parsed.pathname.match(/layout\/(.+)\.(.+)\.js$/);
                    if (match) {
                        var path = 'public/layout/'+match[1]+'.js';
                        try {
                            if (fileSync.existsSync(path)) {
                                var text = fileSync.readFileSync(path).toString('utf-8');
                                res.setHeader('Content-Type', 'application/javascript');
                                res.end(text);
                            }
                        } catch (err) {}

                        return;
                    }
                    next();
                },
            ]
        })
    );

var defaultConfig = Encore.getWebpackConfig();
defaultConfig.name = 'default';

module.exports = [defaultConfig];