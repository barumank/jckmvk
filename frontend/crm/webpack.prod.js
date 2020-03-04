const path = require("path");
const TerserJSPlugin = require('terser-webpack-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const AssetsPlugin = require('assets-webpack-plugin');
const {EnvironmentPlugin, ProvidePlugin} = require("webpack");


module.exports = {
    mode: 'production',
    entry: ['./src/index.js'],
    output: {
        path: path.resolve(__dirname, '../public/crm'),
        filename: '[name].[hash].js',
        publicPath: "/frontend/",
    },
    optimization: {
        minimizer: [new TerserJSPlugin({}), new OptimizeCSSAssetsPlugin({})],
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
    plugins: [
        new CleanWebpackPlugin(),
        new ProvidePlugin({
            appApi: [path.resolve(path.join(__dirname, 'src/api')),'default']
        }),
        new EnvironmentPlugin({
            NODE_ENV: 'production', // use 'development' unless process.env.NODE_ENV is defined
        }),
        new MiniCssExtractPlugin({
            filename: 'style.[contenthash].css',
        }),
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
        new AssetsPlugin({
            filename: 'assets.json',
            path: path.join(__dirname, '../public/crm/assets'),
            includeAllFileTypes:false
        })
    ]
};
