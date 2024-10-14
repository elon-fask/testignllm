const path = require('path');
const autoprefixer = require('autoprefixer');
const webpack = require('webpack');

module.exports = {
  context: __dirname,
  entry: {
    customReport: ['./src/customReport/entry.tsx'],
    dashboard: ['./src/dashboard/entry.tsx'],
    legacyImport: ['./src/legacyImport/entry.tsx'],
    studentUpdate: ['./src/studentUpdate/entry.jsx'],
    testSiteUpdate: ['./src/testSiteUpdate/entry.tsx'],
    userLog: ['./src/userLog/entry.tsx']
  },
  output: {
    filename: 'bundle.[name].js',
    chunkFilename: 'bundle.[name].[chunkhash].js',
    path: path.resolve(__dirname, '../web/js/react/frontend-new'),
    pathinfo: true,
    publicPath: '/js/react/frontend-new/'
  },
  resolve: {
    extensions: ['.mjs', '.ts', '.tsx', '.js', '.jsx']
  },
  devtool: 'source-map',
  stats: {
    colors: true
  },
  plugins: [
    new webpack.LoaderOptionsPlugin({
      options: {
        context: __dirname,
        postcss: [autoprefixer]
      }
    })
  ],
  module: {
    rules: [
      { enforce: 'pre', test: /\.js$/, loader: 'source-map-loader', include: [path.join(__dirname, '/src')] },
      {
        test: /\.tsx?$/,
        loader: 'awesome-typescript-loader',
        options: { useBabel: true, babelCore: '@babel/core' },
        include: [path.join(__dirname, '/src')]
      },
      {
        test: /\.jsx?$/,
        loader: 'babel-loader',
        include: [path.join(__dirname, '/src')]
      },
      {
        test: /\.css$/,
        use: [
          'style-loader',
          'css-loader',
          {
            loader: 'postcss-loader',
            options: {
              plugins: () => [autoprefixer]
            }
          }
        ]
      }
    ]
  }
};
