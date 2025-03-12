const path = require('path');

module.exports = {
  entry: './js/admin.js',  // Ton fichier d'entrée JavaScript
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, 'dist'),  // Le dossier de sortie
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
          },
        },
      },
    ],
  },
  devServer: {
    contentBase: path.resolve(__dirname, 'dist'),
    compress: true,
    port: 9000,
  },
  resolve: {
    alias: {
      '@fullcalendar': path.resolve(__dirname, 'node_modules/@fullcalendar'),
    },
  },
};
