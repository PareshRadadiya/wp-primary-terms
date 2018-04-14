<?php
/**
 * WP Primary Terms Wp_primary_terms Tests.
 *
 * @since   1.0.0
 * @package WP_Primary_Terms
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

	/**
	 * @covers setup
	 * @throws PHPUnit_Framework_AssertionFailedError
	 */
	public function testSetup() {

		$this->assertNotFalse( has_action( 'admin_enqueue_scripts', array( self::$class_instance, 'include_scripts' ) ) );
		$this->assertNotFalse( has_action( 'admin_footer', array( self::$class_instance, 'print_primary_terms_templates' ) ) );
		$this->assertNotFalse( has_action( 'save_post', array( self::$class_instance, 'save_primary_terms' ) ) );
	}

	/**
	 * @covers include_scripts
	 */
	public function testIncludeScripts() {
		global $pagenow, $post;
		$pagenow = 'post.php';

		$post = self::factory()->post->create_and_get( array( 'post_status' => 'auto-draft' ) );
		setup_postdata( $post );

		self::$class_instance->include_scripts();

		$this->assertTrue( wp_script_is( 'wp-primary-terms-admin-script', 'enqueued' ) );
		$this->assertTrue( wp_style_is( 'wp-primary-terms-admin-style', 'enqueued' ) );
	}
}
