const path = require("path");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const AssetsPlugin = require('assets-webpack-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const autoprefixer = require('autoprefixer');

module.exports = {
    devtool: 'source-map',
    mode: 'development',
    watch: true,
    watchOptions: {
        aggregateTimeout: 300,
        poll: 500,
        ignored: /node_modules/
    },
    entry: ['./src/index.js',],
    output: {
        path: path.resolve(__dirname, '../public/crm'),
        filename: '[name].[hash].js',
        publicPath: "/frontend/crm/",
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
                use: ['style-loader', MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader']
            },
            {
                test: /\.scss$/,
                use: ['style-loader', MiniCssExtractPlugin.loader, 'css-loader', {
                    loader: 'postcss-loader',
                    options: {
                        plugins: [
                            autoprefixer
                        ],
                        sourceMap: true
                    }
                }, 'sass-loader']
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
        new CleanWebpackPlugin({
            dry: false,
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
        new HtmlWebpackPlugin({
            inject: true,
            hash: false,
            template: path.resolve(__dirname, 'src/index.html'),
            filename: path.resolve(__dirname, '../public/crm/index.html')
        }),
        new AssetsPlugin({
            filename: 'assets.json',
            path: path.join(__dirname, '../public/crm/assets'),
            includeAllFileTypes: false
        })
    ]
};