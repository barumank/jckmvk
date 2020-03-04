const path = require("path");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CopyWebpackPlugin = require('copy-webpack-plugin');
const {EnvironmentPlugin, ProvidePlugin} = require("webpack");

module.exports = {
    devtool: 'source-map',
    mode: 'development',
    entry: ['./src/index.js',],
    output: {
        path: path.resolve(__dirname, '../public/crm'),
        filename: '[name].[hash].js'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: ["babel-loader"]
            },
            {
                test: /\.css$/,
                include: /\.module\.css$/,
                oneOf: [
                    {
                        test: /\.module\.css$/,
                        use: [
                            MiniCssExtractPlugin.loader,
                            {
                                loader: "css-loader",
                                options: {
                                    modules: true,
                                    exportOnlyLocals: false,
                                    localIdentName: "[local]___[hash:base64:5]",
                                }
                            }
                        ]
                    },
                    {
                        use: [MiniCssExtractPlugin.loader, "css-loader"]
                    }
                ]
            },
            {
                test: /\.css$/,
                exclude: /\.module\.css$/,
                use: [
                    'style-loader',
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'postcss-loader'
                ]
            },
            /*{
                test: /\.scss$/,
                use: [
                    'style-loader',
                    MiniCssExtractPlugin.loader,
                    {
                        loader: "css-loader",
                        options: {
                            sourceMap: true,
                            modules: true,
                            localIdentName: "[local]___[hash:base64:5]"
                        }
                    },
                    'postcss-loader',
                    'sass-loader'
                ]
            },*/
            {
                test: /\.(woff(2)?|ttf|eot)(\?v=\d+\.\d+\.\d+)?$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'fonts/',
                        },
                    },
                ],
            },
            {
                test: /\.(gif|png|jpe?g|svg)$/i,
                use: {
                    loader: 'file-loader',
                    options: {
                        name: '[name].[ext]',
                        outputPath: 'img/',
                    },
                },
            },

        ]
    },
    devServer: {
        contentBase: '../public/crm',
        publicPath: '/',
        watchContentBase: true,
        inline: true,
        historyApiFallback: true,
        host: '0.0.0.0',
        port: 3080,
        disableHostCheck: true,
        stats: {
            colors: true,
            modules: false,
            chunks: false,
            chunkGroups: false,
            chunkModules: false,
            env: true,
        },
        proxy: {
            '/api': 'http://nginx',
            '/upload': 'http://nginx',
        }
    },
    plugins: [
        new ProvidePlugin({
            appApi: [path.resolve(path.join(__dirname, 'src/api')),'default']
        }),
        new EnvironmentPlugin({
            NODE_ENV: 'development', // use 'development' unless process.env.NODE_ENV is defined
        }),
        new MiniCssExtractPlugin({
            filename: 'style.[contenthash].css',
        }),
        // new OpenBrowserPlugin({ url: 'http://localhost:3080' }),
        new CopyWebpackPlugin([
            {
                from: path.resolve(__dirname, 'src/styles/fonts'),
                to: path.resolve(__dirname, '../public/crm/fonts'),
            },
            {
                from: path.resolve(__dirname, 'src/styles/img'),
                to: path.resolve(__dirname, '../public/crm/img'),
            },
        ]),
        new HtmlWebpackPlugin({
            /*inject: true,
            hash: false,*/
            inject: false,
            hash: true,
            template: path.resolve(__dirname, 'src/index.html'),
            filename: path.resolve(__dirname, '../public/crm/index.html')
        }),
    ]
};
