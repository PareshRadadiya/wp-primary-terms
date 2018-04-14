<?php
/**
 * Class Test_Primary_Taxonomy
 *
 */
class Test_Primary_Taxonomy extends WP_UnitTestCase {

	protected static $post;
	protected static $tax_key = 'wptests_tax';

	public static function setUpBeforeClass() {
		self::$post = self::factory()->post->create_and_get( array( 'post_status' => 'auto-draft' ) );
		register_taxonomy( self::$tax_key, array( 'post' ), array( 'hierarchical' => true ) );
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * Test at first it return 'category' taxonomy
	 */
	public function testDefaultCategoryPrimaryTaxonomyEnabled() {

		$primary_taxonomy = wppt_get_primary_taxonomies( self::$post );
		$this->assertContains( 'category', $primary_taxonomy );
	}

	/**
	 * Test primary taxonomy support only enabled for the custom taxonomy
	 */
	public function testOnlyCustomPrimaryTaxonomyEnabled() {

		// Update plugin settings option
		update_option( WP_Primary_Terms_Settings::OPTION_KEY, array( self::$tax_key ) );

		$primary_taxonomy = wppt_get_primary_taxonomies( self::$post );

		$this->assertContains( self::$tax_key, $primary_taxonomy );

		$this->assertNotContains( 'category', $primary_taxonomy );
	}

	/**
	 * Test none of the taxonomies has support for primary taxonomy
	 */
	public function testZeroPrimaryTaxonomyEnabled() {

		// Update plugin settings option
		update_option( WP_Primary_Terms_Settings::OPTION_KEY, array() );

		$primary_taxonomy = wppt_get_primary_taxonomies( self::$post );

		$this->assertNotContains( self::$tax_key, $primary_taxonomy );

		$this->assertNotContains( 'category', $primary_taxonomy );
	}

	/**
	 * Try to hook non-hierarchical category via wppt_get_settings filter
	 */
	public function testNoneHierarchicalTaxonomyHookedViaFilter() {
		$tax = 'tax1';

		register_taxonomy( $tax, 'post' );

		add_filter( 'wppt_get_settings', array( $this, 'filter_wppt_get_settings' ) );

		$primary_taxonomy = wppt_get_primary_taxonomies( self::$post );

		$this->assertNotContains( $tax, $primary_taxonomy );
		$this->assertContains( 'category', $primary_taxonomy );
	}

	/**
	 * Test primary taxonomy with custom post type and taxonomies
	 */
	public function testCustomPostTypePrimaryTaxonomy() {
		$post_type = 'custom_post_type';
		$tax1      = 'new_tax_1';
		$tax2      = 'new_tax_2';

		register_post_type( $post_type, array( 'taxonomies' => array( 'category', $tax1, $tax2 ) ) );

		register_taxonomy( $tax1, $post_type );
		register_taxonomy( $tax2, $post_type, array( 'hierarchical' => true ) );

		// Update plugin settings option
		update_option( WP_Primary_Terms_Settings::OPTION_KEY, array( $tax1, $tax2 ) );

		$post = self::factory()->post->create_and_get(
			array(
				'post_type'   => $post_type,
				'post_status' => 'auto-draft',
			)
		);

		$primary_taxonomy = wppt_get_primary_taxonomies( $post );

		$this->assertNotContains( 'category', $primary_taxonomy );
		$this->assertNotContains( $tax1, $primary_taxonomy );
		$this->assertContains( $tax2, $primary_taxonomy );
	}

	/**
	 * Filter callback for wppt_get_setting
	 * @param $settings
	 * @return mixed
	 */
	public function filter_wppt_get_settings( $settings ) {
		array_push( $settings, 'tax1' );
		return $settings;
	}

}
