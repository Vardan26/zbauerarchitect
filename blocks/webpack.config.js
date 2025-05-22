const path = require("path");
const defaultConfig = require("@wordpress/scripts/config/webpack.config");

module.exports = {
  ...defaultConfig,
  entry: {
    bundle: "./src/index.tsx", // Your main entry file
  },
  output: {
    path: path.resolve(__dirname, "build"),
    filename: "[name].js",
  },
};
