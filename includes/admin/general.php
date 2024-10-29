<?php
/**
 * Displays the general settings tab
 *
 * @author   Picocodes
 * @package  @package Ajax Live Search
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * The main class that displays the general settings tab
 *
 */
 
class Als_General_Settings extends Als_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'general';
		$this->label = __( 'General', 'als' );

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


		$settings = apply_filters( 'als_general_settings', array(

			array( 'title' => __( 'General Options', 'als' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

			array(
				'title'    => __( 'Use our searching engine?', 'als' ),
				'description' => __('Why would anyone want to uncheck this?','als'),
				'id'       => 'als_enable_search',
				'css'      => '',
				'default'  => 'checked',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Use our rendering engine?', 'als' ),
				'description' => __('Unchecking this automatically disables some features such as live search and ajax search.','als'),
				'id'       => 'als_enable_render',
				'css'      => '',
				'default'  => 'checked',
				'type'     => 'checkbox',
			),

				array(
					'type' 	=> 'sectionend',
					'id' 	=> 'general_options'
				)
				));
		return apply_filters( 'als_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output a colour picker input box.
	 *
	 * @param mixed $name
	 * @param string $id
	 * @param mixed $value
	 * @param string $desc (default: '')
	 */
	public function color_picker( $name, $id, $value, $desc = '' ) {
		echo '<div class="color_box">' . als_help_tip( $desc ) . '
			<input name="' . esc_attr( $id ). '" id="' . esc_attr( $id ) . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
		</div>';
	}

	/**
	 * Save settings.
	 */
	public function save() {
		$settings = $this->get_settings();

		Als_Admin_Settings::save_fields( $settings );
	}

}

return new Als_General_Settings();
