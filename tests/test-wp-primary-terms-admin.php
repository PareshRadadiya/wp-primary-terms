<?php
/**
 * WP Primary Category Wp_primary_terms Tests.
 *
 * @since   1.0.0
 * @package WP_Primary_Category
 */
class Test_WP_Primary_Terms_Admin extends WP_UnitTestCase {

	/**
	 * @var WP_Primary_Terms_Admin
	 */
	private static $class_instance;

	public function setup() {
		parent::setUp();
	}

	/**
	 * Set up the class which will be tested.
	 */
	public static function setUpBeforeClass() {
		parent::setUpBeforeClass();
		self::$class_instance = WP_Primary_Terms_Admin::get_instance();
	}

	public function testSetup() {

		$this->assertNotFalse( has_action( 'admin_enqueue_scripts', array( self::$class_instance, 'include_scripts' ) ) );
		$this->assertNotFalse( has_action( 'admin_footer', array( self::$class_instance, 'print_primary_terms_templates' ) ) );
		$this->assertNotFalse( has_action( 'save_post', array( self::$class_instance, 'save_primary_terms' ) ) );
	}
}