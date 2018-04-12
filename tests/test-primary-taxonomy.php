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

	public function testDefaultCategoryPrimaryTaxonomyEnabled() {

		$primary_taxonomy = wppt_get_primary_taxonomies( self::$post );

		$this->assertContains( 'category', $primary_taxonomy );
	}

	public function testOnlyCustomPrimaryTaxonomyEnabled() {

		update_option( WP_Primary_Terms_Settings::OPTION_KEY, array( self::$tax_key ) );

		$primary_taxonomy = wppt_get_primary_taxonomies( self::$post );

		$this->assertContains( self::$tax_key, $primary_taxonomy );

		$this->assertNotContains( 'category', $primary_taxonomy );
	}

	public function testZeroPrimaryTaxonomyEnabled() {

		update_option( WP_Primary_Terms_Settings::OPTION_KEY, array() );

		$primary_taxonomy = wppt_get_primary_taxonomies( self::$post );

		$this->assertNotContains( self::$tax_key, $primary_taxonomy );

		$this->assertNotContains( 'category', $primary_taxonomy );
	}

	public function testNoneHierarchicalPrimaryTaxonomy() {
		$tax = 'tax1';

		register_taxonomy( $tax, 'post' );

		add_filter( 'wppt_get_settings', array( $this, 'wppt_get_settings' ) );

		$primary_taxonomy = wppt_get_primary_taxonomies( self::$post );

		$this->assertNotContains( $tax, $primary_taxonomy );
	}

	public function wppt_get_settings( $settings ) {
		 array_push( $settings, 'tax1' );
		 return $settings;
	}

}