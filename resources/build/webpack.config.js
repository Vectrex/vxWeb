const webpack = require("webpack");
const path = require("path");
const VueLoaderPlugin = require("vue-loader/lib/plugin");

const resolve = relativePath => path.resolve(__dirname, "..", relativePath);

module.exports = {
    mode: "development",
    entry: {
        vue: "vue",
        index: resolve("vue/app.js")
    },
    output: {
        filename: '[name].js',
        path: resolve('dist/js/vue'),
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    loaders: {
                        css: ['vue-style-loader', {
                            loader: 'css-loader',
                        }],
                        /*
                        js: [
                            'babel-loader',
                        ],
                        */
                    },
                    cacheBusting: true,
                }
            },
            {
                // This is required for other javascript modules you are gonna write besides vue.
                /*
                test: /\.js$/,
                loader: 'babel-loader',
                include: [
                    resolve('src'),
                    resolve('node_modules/webpack-dev-server/client'),
                ],
                */
            },
        ]
    },
    plugins: [
        new VueLoaderPlugin()
    ]
}
