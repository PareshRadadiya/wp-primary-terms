const webpack = require( 'webpack' );
const path = require( 'path' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );
const inProduction = ('production' === process.env.NODE_ENV);
const CleanWebpackPlugin = require( 'clean-webpack-plugin' );
const WebpackRTLPlugin = require( 'webpack-rtl-plugin' );
const wpPot = require( 'wp-pot' );

// Webpack config.
const config = {
	entry: {
		admin: [ './assets/js/admin/admin.js', './assets/css/admin/admin.scss' ],
	},

	// Tell webpack where to output.
	output: {
		path: path.resolve( __dirname, './assets/dist/' ),
		filename: 'js/[name].min.js'
	},

	devtool: 'source-map',
	module: {
		rules: [

			// Use Babel to compile JS.
			{
				test: /\.js$/,
				exclude: /node_modules/,
				loaders: [
					'babel-loader'
				]
			},

			// Expose accounting.js for plugin usage.
			{
				test: require.resolve( 'accounting' ),
				loader: 'expose-loader?accounting'
			},

			// Create RTL styles.
			{
				test: /\.css$/,
				loader: ExtractTextPlugin.extract( 'style-loader' )
			},

			// SASS to CSS.
			{
				test: /\.scss$/,
				use: ExtractTextPlugin.extract( {
					use: [ {
						loader: 'css-loader',
						options: {
							sourceMap: true
						}
					}, {
						loader: 'postcss-loader',
						options: {
							sourceMap: true
						}
					}, {
						loader: 'sass-loader',
						options: {
							sourceMap: true,
							outputStyle: (inProduction ? 'compressed' : 'nested')
						}
					} ]
				} )
			},

		]
	},

	// Plugins. Gotta have em'.
	plugins: [

		// Removes the "dist" folder before building.
		new CleanWebpackPlugin( [ 'assets/dist' ] ),

		new ExtractTextPlugin( 'css/[name].css' ),

		// Create RTL css.
		new WebpackRTLPlugin(),
	]
};

// inProd?
if ( inProduction ) {

	// POT file.
	wpPot( {
		package: 'WP Primary Terms',
		domain: 'wp-primary-terms',
		destFile: 'languages/wp-primary-terms.pot',
		relativeTo: './'
	} );

	// Uglify JS.
	config.plugins.push( new webpack.optimize.UglifyJsPlugin( { sourceMap: true } ) );

	// Minify CSS.
	config.plugins.push( new webpack.LoaderOptionsPlugin( { minimize: true } ) );
}

module.exports = config;
