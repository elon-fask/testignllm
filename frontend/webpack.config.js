/* eslint-disable sort-keys */

const path = require('path');
const autoprefixer = require('autoprefixer');
const webpack = require('webpack');

module.exports = {
  context: __dirname,
  entry: {
    accountBalance: ['@babel/polyfill', './src/AccountBalance/entry.jsx'],
    bulkRegistration: ['@babel/polyfill', './src/BulkRegistration/entry.jsx'],
    calendarRoot: ['@babel/polyfill', './src/Calendar/entry.jsx'],
    company: ['@babel/polyfill', './src/Company/entry.jsx'],
    companyTransaction: ['@babel/polyfill', './src/CompanyTransaction/entry.jsx'],
    reports: ['@babel/polyfill', './src/Reports/entry.jsx'],
    staffPage: ['@babel/polyfill', './src/StaffPage/entry.jsx'],
    studentRegistration: ['@babel/polyfill', './src/StudentRegistration/entry.jsx'],
    studentSearchNew: ['@babel/polyfill', './src/StudentSearchNew/entry.jsx'],
    studentTransfer: ['@babel/polyfill', './src/StudentTransfer/entry.jsx'],
    studentFiles: ['@babel/polyfill', './src/StudentFiles/entry.jsx'],
    testSessionSpreadsheet: ['@babel/polyfill', './src/TestSessionSpreadsheet/entry.jsx'],
    travelFormUpdate: ['@babel/polyfill', './src/TravelFormUpdate/entry.jsx'],
    userMerge: ['@babel/polyfill', './src/UserMerge/entry.jsx']
  },
  output: {
    filename: 'bundle.[name].js',
    path: path.resolve(__dirname, '../web/js/react'),
    pathinfo: true
  },
  resolve: {
    extensions: ['.js', '.jsx']
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
    }),
    new webpack.optimize.CommonsChunkPlugin({
      name: 'vendor',
      filename: 'bundle.vendor.js'
    })
  ],
  module: {
    rules: [
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
