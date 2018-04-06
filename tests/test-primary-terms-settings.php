<?php
/**
 * WP Primary Category Primary Terms Settings Tests.
 *
 * @since   1.0.0
 * @package WP_Primary_Category
 */
class WPPC_Primary_Terms_Settings_Test extends WP_UnitTestCase {

	/**
	 * Test if our class exists.
	 *
	 * @since  1.0.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'WPPC_Primary_Terms_Settings') );
	}

	/**
	 * Test that we can access our class through our helper function.
	 *
	 * @since  1.0.0
	 */
	function test_class_access() {
		$this->assertInstanceOf( 'WPPC_Primary_Terms_Settings', wppc()->primary-terms-settings );
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
