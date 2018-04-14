<?php
/**
 * WP Primary Terms Wp_primary_terms Tests.
 *
 * @since   1.0.0
 * @package WP_Primary_Terms
 */
class Test_WP_Primary_Terms extends WP_UnitTestCase {

	protected $object;

	public function setup() {
		parent::setUp();
		$this->object = wppt()::get_instance();
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Test if our class exists.
	 *
	 * @since  1.0.0
	 */
	public function testClassExists() {
		$this->assertTrue( class_exists( 'WP_Primary_Terms' ) );
	}

	public function testWPPrimaryTermsInstance() {
		$this->assertClassHasStaticAttribute( 'instance', 'WP_Primary_Terms' );
	}

	/**
	 * @covers WP_Primary_Terms::define_constants
	 */
	public function testConstants() {
		// Plugin Folder URL
		$path = plugin_dir_url( dirname( __FILE__ ) );
		$this->assertSame( WPPT_URL, $path );

		// Plugin Folder Path
		$path = plugin_dir_path( dirname( __FILE__ ) );
		$this->assertSame( WPPT_ABSPATH, $path );
	}

	/**
	 * @covers WP_Primary_Terms::includes
	 */
	public function testIncludes() {
		$this->assertFileExists( WPPT_ABSPATH . 'includes/functions.php' );
		$this->assertFileExists( WPPT_ABSPATH . 'includes/admin/class-wp-primary-terms-settings.php' );
		$this->assertFileExists( WPPT_ABSPATH . 'includes/admin/class-wp-primary-terms-admin.php' );
	}

}
