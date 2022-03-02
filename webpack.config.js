const path = require('path');
const WebpackWatchedGlobEntries = require('webpack-watched-glob-entries-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin'); // eslint-disable-line import/no-extraneous-dependencies
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

const NODE_ENV = process.env.NODE_ENV;
// const IS_DEV = NODE_ENV === 'development';
// const IS_PROD = !IS_DEV;

module.exports = {
  resolve: {
    alias: {
      assert: 'assert',
      buffer: 'buffer',
      constants: 'constants-browserify',
      path: 'path-browserify',
      util: 'util',
      os: 'os-browserify',
      stream: 'stream-browserify',
    },
  },
  target: 'node',
  context: __dirname,
  entry: WebpackWatchedGlobEntries.getEntries([
    path.resolve(__dirname, 'src/scss/style.scss'),
    path.resolve(__dirname, 'src/scss/woocommerce.scss'),
    path.resolve(__dirname, 'src/js/src-single/*.js'),
    path.resolve(__dirname, 'src/js/*.js'),
  ]),
  output: {
    publicPath: '/wp-content/themes/KayMozCeramics/',
    path: path.resolve(__dirname),
    filename: './public/[name].bundle.js',
  },
  mode: NODE_ENV ? NODE_ENV : 'development',
  devtool: 'source-map',
  watchOptions: {
    ignored: /node_modules/,
  },
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        loader: 'babel-loader',
        options: {
          sourceMap: true,
          /* React-specific tools when needed
					presets: [
							// Preset include JSX, TypeScript, and some ESnext features
							require.resolve('babel-preset-react-app'),
					],
					*/
        },
      },
      {
        test: /\.s?css$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
          },
          {
            loader: 'css-loader',
            options: {
              sourceMap: true,
            },
          },
          {
            loader: 'postcss-loader',
            options: {
              sourceMap: true,
              postcssOptions: {
                plugins: [
                  [
                    'postcss-preset-env',
                    {
                      // Options
                    },
                  ],
                ],
              },
            },
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: true,
            },
          },
        ],
      },
      {
        test: /\.(woff|woff2|eot|ttf|svg)$/i,
        include: /fonts/,
        type: 'asset',
        generator: {
          filename: 'public/assets/fonts/[name][ext]',
        },
      },
      {
        test: /\.(jpe?g|png|gif|svg)$/i,
        include: /images/,
        type: 'asset',
        generator: {
          filename: 'public/assets/images/[name][ext]',
        },
      },
    ],
  },
  plugins: [
    new WebpackWatchedGlobEntries(),
    new RemoveEmptyScriptsPlugin(),
    new MiniCssExtractPlugin({
      filename: path.resolve(__dirname, '/public/[name].css'),
    }),
    new BrowserSyncPlugin({
      files: '**/*.php',
      proxy: 'https://kaymozceramics.local/',
      open: false,
    }),
  ],
  optimization: {
    minimize: true,
    minimizer: [
      new TerserPlugin({
        terserOptions: {
          sourceMap: true,
          parse: {
            // We want terser to parse ecma 8 code. However, we don't want it
            // to apply minification steps that turns valid ecma 5 code
            // into invalid ecma 5 code. This is why the `compress` and `output`
            ecma: 8,
          },
          compress: {
            ecma: 5,
            inline: 2,
          },
          mangle: {
            // Find work around for Safari 10+
            safari10: true,
          },
          format: {
            comments: false,
          },
        },
        extractComments: false,
      }),
      new CssMinimizerPlugin({
        minimizerOptions: {
          preset: [
            'default',
            {
              discardComments: { removeAll: true },
            },
          ],
        },
      }),
    ],
  },
};
