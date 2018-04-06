<?php
/**
 * WP Primary Category Wp Primary Terms Admin Tests.
 *
 * @since   1.0.0
 * @package WP_Primary_Category
 */
class WPPC_Wp_Primary_Terms_Admin_Test extends WP_UnitTestCase {

	/**
	 * Test if our class exists.
	 *
	 * @since  1.0.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'WPPC_Wp_Primary_Terms_Admin') );
	}

	/**
	 * Test that we can access our class through our helper function.
	 *
	 * @since  1.0.0
	 */
	function test_class_access() {
		$this->assertInstanceOf( 'WPPC_Wp_Primary_Terms_Admin', wppc()->wp-primary-terms-admin );
	}

	/**
	 * Replace this with some actual testing code.
	 *
	 * @since  1.0.0
	 */
	function test_sample() {
		$this->assertTrue( true );
	}
}
