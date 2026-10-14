/**
 * Default @wordpress/scripts config (one bundle per block.json under src/blocks)
 * plus the Uppsättning editor sidebar.
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		'sidebar/index': path.resolve( __dirname, 'src/sidebar/index.js' ),
	},
};
