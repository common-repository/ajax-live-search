<?php
/**
 * Displays the searching settings tab
 *
 * @author   Picocodes
 * @package  @package Ajax Live Search
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Used to display the searching tab of our settings page
 * @since      1.0.0
 *
 * @package    Ajax Live Search
 * @subpackage Als/admin
 */
class Als_Settings_searching extends Als_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'searching';
		$this->label = __( 'Searching', 'als' );

		add_filter( 'als_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'als_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'als_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = apply_filters( 'als_searching_settings', array(

			array( 'title' => __( 'Search Autocomplete', 'als' ), 'type' => 'title', 'desc' => '', 'id' => 'autocomplete_options' ),

			array(
				'title'    => __( 'Display autocomplete suggestions.', 'als' ),
				'desc'     =>'',
				'id'       => 'als_display_autocomplete',
				'css'      => '',
				'default'  => 'checked',
				'type'     => 'checkbox',
				'desc_tip' =>  true,
			),
			
			array(
				'title'    => __( 'Autocomplete Engine.', 'als' ),
				'desc'     =>__('What engine to load suggestions with (Google and youtube available for Pro users only).', 'als'),
				'id'       => 'als_autocomplete_engine',
				'css'      => '',
				'default'  => 'local',
				'type'     => 'radio',
				'options'  => array(
					'local'      => __( 'Local Database.', 'als' ),
					),
				'desc_tip' =>  true,
			),

			array(
				'title'    => __( 'Database table to use for autocompletes.', 'als' ),
				'desc'     =>'',
				'id'       => 'als_autocomplete_table',
				'css'      => '',
				'default'  => 'posts',
				'type'     => 'radio',
				'options'  => array(
					'posts'      => __( 'WP Posts Table', 'als' ),
					'searches'      => __( 'Previous Searches Table', 'als' )
					),
				'desc_tip' =>  true,
			),
			
			array(
				'title'    => __( 'Number Of Suggestions', 'als' ),
				'desc'     =>'',
				'id'       => 'als_autocomplete_count',
				'css'      => 'width: 48px;',
				'default'  => '5',
				'type'     => 'text',
				'desc_tip' =>  true,
			),
			
			
			array(
				'title'    => __( 'Post Types to Search', 'als' ),
				'desc'     =>'Separate with a comma',
				'id'       => 'als_post_types',
				'css'      => '',
				'default'  => als_post_types(),
				'type'     => 'textarea',
				'desc_tip' =>  true,
			),


			array(
					'type' 	=> 'sectionend',
					'id' 	=> 'searching_options'
				),
			
			array( 'title' => __( 'Ranking', 'als' ), 'type' => 'title', 'desc' => '', 'id' => 'ranking_options' ),
			
			array(
				'title'    => __( 'Title Weight', 'als' ),
				'desc'     =>'',
				'id'       => 'als_title_weight',
				'css'      => 'width: 48px;',
				'default'  => '15',
				'type'     => 'text',
				'desc_tip' =>  true,
			),
			
			array(
				'title'    => __( 'Content Weight', 'als' ),
				'desc'     =>'',
				'id'       => 'als_content_weight',
				'css'      => 'width: 48px;',
				'default'  => '1',
				'type'     => 'text',
				'desc_tip' =>  true,
			),
			
			array(
				'title'    => __( 'Give more weight to newer posts?', 'als' ),
				'desc'     =>'',
				'id'       => 'als_favor_new',
				'css'      => '',
				'default'  => 'no',
				'type'     => 'checkbox',
				'desc_tip' =>  true,
			),
			array(
				'title'    => __( 'If yes, which field should we use.', 'als' ),
				'desc'     =>'',
				'id'       => 'als_favor_new_field',
				'css'      => '',
				'default'  => 'post_modified',
				'type'     => 'radio',
				'options'  => array(
					'post_modified'      => __( 'Last modified', 'als' ),
					'post_modified_gmt'      => __( 'Last modified gmt', 'als' ),
					'post_date'      => __( 'Publish date', 'als' ),
					'post_date_gmt'      => __( 'Publish date gmt', 'als' ),

					),
				'desc_tip' =>  true,
			),
			
			array(
				'title'    => __( 'Give more weight to posts containing comments?', 'als' ),
				'desc'     =>'',
				'id'       => 'als_favor_popular',
				'css'      => '',
				'default'  => 'no',
				'type'     => 'checkbox',
				'desc_tip' =>  true,
			),
			
			array(
				'title'    => __( 'Rank By.', 'als' ),
				'desc'     =>'',
				'id'       => 'als_rank_by',
				'css'      => '',
				'default'  => 'post__in',
				'type'     => 'radio',
				'options'  => array(
					'post__in'      => __( 'Relevance', 'als' ),
					'author'      => __( 'Author', 'als' ),
					'title'      => __( 'Title', 'als' ),
					'type'      => __( 'Post type', 'als' ),
					'date'      => __( 'Date', 'als' ),
					'rand'      => __( 'Random', 'als' ),
					'comment_count'      => __( 'Popular', 'als' )
					),
				'desc_tip' =>  true,
			),
			
			array(
					'type' 	=> 'sectionend',
					'id' 	=> 'ranking_options'
				)
		) );

		return apply_filters( 'als_get_settings_' . $this->id, $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		$settings = $this->get_settings();

		Als_Admin_Settings::save_fields( $settings );
	}

}

return new Als_Settings_searching();
