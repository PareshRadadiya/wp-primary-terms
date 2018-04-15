<?php
/**
 * WP Primary Terms Wp_primary_terms Tests.
 *
 * @since   1.0.0
 * @package WP_Primary_Terms
 */
namespace TenUp\WpPrimaryTerms;

class Test_WP_Primary_Terms extends \WP_UnitTestCase {

	public function setup() {
		parent::setUp();
	}

	/**
	 * Test if our class exists.
	 *
	 * @since  1.0.0
	 */
	public function testClassExists() {
		$this->assertTrue( class_exists( '\TenUp\WpPrimaryTerms\Core\WP_Primary_Terms' ) );
	}

	public function testWPPrimaryTermsInstance() {
		$this->assertClassHasStaticAttribute( 'instance', '\TenUp\WpPrimaryTerms\Core\WP_Primary_Terms' );
	}

	/**
	 * @covers WP_Primary_Terms::define_constants
	 */
	public function testConstants() {
		// Plugin Folder URL
		$path = plugin_dir_url( dirname( __FILE__ ) );
		$this->assertSame( WP_PRIMARY_TERMS_URL, $path );

		// Plugin Folder Path
		$path = plugin_dir_path( dirname( __FILE__ ) );
		$this->assertSame( WP_PRIMARY_TERMS_PATH, $path );
	}

	/**
	 * @covers WP_Primary_Terms::includes
	 */
	public function testIncludes() {
		$this->assertFileExists( WP_PRIMARY_TERMS_INC . 'functions/functions.php' );
		$this->assertFileExists( WP_PRIMARY_TERMS_INC . 'classes/admin/class-wp-primary-terms-settings.php' );
		$this->assertFileExists( WP_PRIMARY_TERMS_INC . 'classes/admin/class-wp-primary-terms-admin.php' );
	}

}
