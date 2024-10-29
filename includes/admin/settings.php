<?php
/**
 * Als Admin Settings Class
 *
 * @author   Picocodes
 * @package  @package Ajax Live Search
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Als_Admin_Settings.
 */
class Als_Admin_Settings {

	/**
	 * Setting pages.
	 *
	 * @var array
	 */
	private static $settings = array();

	/**
	 * Error messages.
	 *
	 * @var array
	 */
	private static $errors   = array();

	/**
	 * Update messages.
	 *
	 * @var array
	 */
	private static $messages = array();
	
		/**
	 * Add a message.
	 * @param string $text
	 */
	public static function add_message( $text ) {
		self::$messages[] = $text;
	}

	/**
	 * Add an error.
	 * @param string $text
	 */
	public static function add_error( $text ) {
		self::$errors[] = $text;
	}

	/**
	 * Output messages + errors.
	 * @return string
	 */
	public static function show_messages() {
		if ( sizeof( self::$errors ) > 0 ) {
			foreach ( self::$errors as $error ) {
				echo '<div id="message" class="error"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
			}
		} elseif ( sizeof( self::$messages ) > 0 ) {
			foreach ( self::$messages as $message ) {
				echo '<div id="message" class="updated"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
			}
		}
	}
	
	/**
	 * Add an admin page
	 */
	public static function add_menu_page(  ) {
		add_menu_page(
			'Ajax Live Search',
			'Ajax Live Search',
			'manage_options',
			'als-admin-page',
			'Als_Admin_Settings::als_upgrade_screen_content',
			false,
			'2');
			
		add_submenu_page(
			'als-admin-page',
			'Search Statistics',
			'Statistics',
			'manage_options',
			'als-statistics',
			'Als_Admin_Settings::show_statistics_page'
			);
			
		add_submenu_page(
			'als-admin-page',
			'Als Settings',
			'Settings',
			'manage_options',
			'als-settings',
			'Als_Admin_Settings::output'
			);
			
		add_dashboard_page(
			'Welcome To Ajax Live Search',
			'Welcome To Ajax Live Search',
			'read',
			'als-welcome-screen-about',
			'Als_Admin_Settings::als_welcome_screen_content'
  );
		/*add_submenu_page(
			'als-admin-page',
			'Get in touch',
			'Contact Us',
			'read',
			'als-contact-screen',
			'Als_Admin_Settings::als_contact_screen_content'
  );*/
		
	}
	
	/**
	 * Display the statistics page
	 */
	public static function show_statistics_page(  ) {
		$dummy = false;
		$tabs = array('basic'=>'Basic'); // Graphs not available in lite mode
		$current_tab = 'basic';
		
		if(isset($_GET['action']) && $_GET['action']=='dummy'){
			als_dummy_data();
			$dummy = true;
		}
		
	?>
			<div class="wrap">
				<h1><?php _e('Search Statistics.', 'als');?></h1>
					<h2 class="nav-tab-wrapper als-nav-tab-wrapper">
						<?php
							foreach ( $tabs as $name => $label ) {
								echo '<a href="' . admin_url( 'admin.php?page=als-statistics&tab=' . $name ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
							}

						?>
					</h2>
				
				<div id='message' class='updated notice is-dismissible'>
				<p>
				<?php if (!$dummy){
				
				_e('Click the button below to generate dummy data and insert into the database.', 'als');?>
				<form action="">
				<input type="hidden" name="page" value="als-statistics" >
				<button name="action" class="button-primary" value="dummy">Generate</button>
				</form>
				<?php }else{
				
				_e('Dummy data successfully generated.', 'als');
				}
				?>
				</p>
				</div>

				<?php 
					if (true) : //stupid condition (because we aint showing graphs)
				?>
				<h3><?php _e('Total Searches', 'als');?>(<?php self::total_searches()?>).</h3>

				<h3><?php _e('Searches with no results', 'als');?>(<?php self::total_searches('hits = 0')?>).</h3>
					<?php self::show_searches('hits = 0','searches DESC' ); ?>
					
				<h3><?php _e('Searches with results.', 'als');?>(<?php self::total_searches('hits > 0')?>)</h3>
					<?php self::show_searches('hits > 0','searches DESC' ); ?>
					
				<h3><?php _e('Last 10 Queries.', 'als');?></h3>
					<?php self::show_searches('1=1','time DESC' ); ?>
					
				<h3><?php _e('Popular Search Queries.', 'als');?></h3>
					<?php self::show_searches('1=1','searches DESC' ); ?>
					
				<h3><?php _e('Queries with most results.', 'als');?></h3>
					<?php self::show_searches('hits > 0','hits DESC' ); ?>
					
				<h3><?php _e('Queries with the least results (excluding those with no results).', 'als');?></h3>
					<?php self::show_searches('hits > 0','hits ASC' ); ?>
					
				<?php
				else: 
					//do nothing cause graphs not available in lite mode
				endif;
				?>
				
				
			</div>
		
	<?php }
	
	
	/**
	 * Displays the welcome screen
	 *
	 */
	public static function als_welcome_screen_content( ) {
		
			include ALS__PLUGIN_DIR . 'includes/admin/welcome-screen.php';

	}
	
	/**
	 * Displays the contact screen
	 *
	 */
	public static function als_contact_screen_content( ) {
		
			include ALS__PLUGIN_DIR . 'includes/admin/contact-screen.php';

	}
	
	/**
	 * Displays the upgrade info
	 *
	 */
	public static function als_upgrade_screen_content( ) {
		
			include ALS__PLUGIN_DIR . 'includes/admin/upgrade-screen.php';

	}
	
	
	/**
	 * Displays searches based on conditions
	 *
	 * @param $conditions Specific conditions that you need to be used
	 * @param $orderby The field that you would like to use for ordering
	 * @param $limit maximum number of results and an optional offset
	 * @param $no_results_msg What message to be displayed incase no results are found
	 */
	public static function show_searches( $conditions = '1=1', $order_by = 'time DESC', $limit = 10, $no_results_msg = false ) {
		global $wpdb;

		$searches_log_table = $wpdb->prefix . "als_log";
		$sql = "SELECT * FROM $searches_log_table WHERE $conditions ORDER BY $order_by LIMIT $limit";
		$results = $wpdb->get_results($sql); 
		
		if(count($results)<1){
			if(!$no_results_msg) {
			_e('No stats to show.','als');
			} else {
				echo $no_results_msg;
			}
			return;
		}
		
		?>
		<table class='wp-list-table widefat fixed striped posts'>
			<thead><tr>
				<th>Query</th>
				<th>Modified</th>
				<th>Searches</th>
				<th>Results</th>
				<th>Last Search</th></thead><tbody>
			</tr>
			<?php
				foreach($results as $single){
				$time = als_date_diff($single->time);
				echo "<tr><td class='manage-column column-title'>$single->query</td>";
				echo "<td class='manage-column '>$single->modified</td>";
				echo "<td class='manage-column '>$single->searches</td>";
				echo "<td class='manage-column '>$single->hits</td>";
				echo "<td class='manage-column '>$time</td></tr>";
				}
			?>
		</tbody></table>
		<?php

	}
	
	/**
	 * Returns Total searches
	 *
	 * @param $where specific conditions to accompany this request
	 * @param $echo whether or not to echo the results
	 */
	public static function total_searches($where = "1=1", $echo = true ) {
		global $wpdb;

		$searches_log_table = $wpdb->prefix . "als_log";
		$sql = "SELECT SUM(searches) as total FROM $searches_log_table WHERE $where";
		$results = $wpdb->get_results($sql); 
		
		if ($echo) {
			echo intval($results[0]->total);
		}
		return intval($results[0]->total);

	}
	
	/**
	 * Get a setting from the settings API.
	 *
	 * @param mixed $option_name the name of the option to retrieve
	 * @param mixed $default the default value of the option name
	 * @return string
	 */
	public static function get_option( $option_name, $default = '' ) {

		$option_value = get_option( $option_name, null );

		if ( is_array( $option_value ) ) {
			$option_value = array_map( 'stripslashes', $option_value );
		} elseif ( ! is_null( $option_value ) ) {
			$option_value = stripslashes( $option_value );
		}

		return $option_value === null ? $default : $option_value;
	}
	
	/**
	 * Settings page.
	 *
	 * Handles the display of the main als settings page in admin.
	 */
	public static function output() {
		global $current_tab;

		do_action( 'als_settings_start' );

		// Include settings pages
		self::get_settings_pages();

		// Get current tab
		$current_tab     = empty( $_GET['tab'] ) ? 'general' : sanitize_title( $_GET['tab'] );
		
		// Save settings if data has been posted
		if ( ! empty( $_POST ) ) {
			self::save();
		}

		// Add any posted messages
		if ( ! empty( $_GET['als_error'] ) ) {
			self::add_error( stripslashes( $_GET['als_error'] ) );
		}

		if ( ! empty( $_GET['als_message'] ) ) {
			self::add_message( stripslashes( $_GET['als_message'] ) );
		}

		// Get tabs for the settings page
		$tabs = apply_filters( 'als_settings_tabs_array', array() );

		include ALS__PLUGIN_DIR . 'includes/admin/html-admin-settings.php';
	}
	
	/**
	 * Save the settings.
	 */
	public static function save() {
		global $current_tab;

		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'als-settings' ) ) {
			die( __( 'Action failed. Please refresh the page and retry.', 'als' ) );
		}

		// Trigger actions
		do_action( 'als_settings_save_' . $current_tab );
		do_action( 'als_update_options_' . $current_tab );
		do_action( 'als_update_options' );

		self::add_message( __( 'Your settings have been saved.', 'als' ) );

		do_action( 'als_settings_saved' );
	}
	
	/**
	 * Include the settings page classes.
	 */
	public static function get_settings_pages() {
		if ( empty( self::$settings ) ) {
			$settings = array();

			include_once( ALS__PLUGIN_DIR . 'includes/admin/class-als-settings-page.php' );

			$settings[] = include( ALS__PLUGIN_DIR . 'includes/admin/general.php' );
			$settings[] = include( ALS__PLUGIN_DIR . 'includes/admin/searching.php');
			$settings[] = include( ALS__PLUGIN_DIR . 'includes/admin/result_pages.php' );
		 
			self::$settings = apply_filters( 'als_get_settings_pages', $settings );
		}

		return self::$settings;
	}
	
	/**
	 * Output admin fields.
	 *
	 * Loops though the als options array and outputs each field.
	 *
	 * @param array $options Opens array to output
	 */
	public static function output_fields( $options ) {
		foreach ( $options as $value ) {
			if ( ! isset( $value['type'] ) ) {
				continue;
			}
			if ( ! isset( $value['id'] ) ) {
				$value['id'] = '';
			}
			if ( ! isset( $value['title'] ) ) {
				$value['title'] = isset( $value['name'] ) ? $value['name'] : '';
			}
			if ( ! isset( $value['class'] ) ) {
				$value['class'] = '';
			}
			if ( ! isset( $value['css'] ) ) {
				$value['css'] = '';
			}
			if ( ! isset( $value['default'] ) ) {
				$value['default'] = '';
			}
			if ( ! isset( $value['desc'] ) ) {
				$value['desc'] = '';
			}
			if ( ! isset( $value['desc_tip'] ) ) {
				$value['desc_tip'] = false;
			}
			if ( ! isset( $value['placeholder'] ) ) {
				$value['placeholder'] = '';
			}

			// Custom attribute handling
			$custom_attributes = array();

			if ( ! empty( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) {
				foreach ( $value['custom_attributes'] as $attribute => $attribute_value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
				}
			}

			// Description handling
			$field_description = self::get_field_description( $value );
			extract( $field_description );

			// Switch based on type
			switch ( $value['type'] ) {

				// Section Titles
				case 'title':
					if ( ! empty( $value['title'] ) ) {
						echo '<h3>' . esc_html( $value['title'] ) . '</h3>';
					}
					if ( ! empty( $value['desc'] ) ) {
						echo wpautop( wptexturize( wp_kses_post( $value['desc'] ) ) );
					}
					echo '<table class="form-table">'. "\n\n";
					if ( ! empty( $value['id'] ) ) {
						do_action( 'als_settings_' . sanitize_title( $value['id'] ) );
					}
					break;

				// Section Ends
				case 'sectionend':
					if ( ! empty( $value['id'] ) ) {
						do_action( 'als_settings_' . sanitize_title( $value['id'] ) . '_end' );
					}
					echo '</table>';
					if ( ! empty( $value['id'] ) ) {
						do_action( 'als_settings_' . sanitize_title( $value['id'] ) . '_after' );
					}
					break;

				// Standard text inputs and subtypes like 'number'
				case 'text':
				case 'email':
				case 'number':
				case 'color' :
				case 'password' :

					$type         = $value['type'];
					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">

							<input
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="<?php echo esc_attr( $type ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								value="<?php echo esc_attr( $option_value ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); ?>
								/> <?php echo $description; ?>
						</td>
					</tr><?php
					break;

				// Textarea
				case 'textarea':

					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
							<?php echo $description; ?>

							<textarea
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); ?>
								><?php echo esc_textarea( $option_value );  ?></textarea>
						</td>
					</tr><?php
					break;

				// Select boxes
				case 'select' :
				case 'multiselect' :

					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
							<select
								name="<?php echo esc_attr( $value['id'] ); ?><?php if ( $value['type'] == 'multiselect' ) echo '[]'; ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); ?>
								<?php echo ( 'multiselect' == $value['type'] ) ? 'multiple="multiple"' : ''; ?>
								>
								<?php
									foreach ( $value['options'] as $key => $val ) {
										?>
										<option value="<?php echo esc_attr( $key ); ?>" <?php

											if ( is_array( $option_value ) ) {
												selected( in_array( $key, $option_value ), true );
											} else {
												selected( $option_value, $key );
											}

										?>><?php echo $val ?></option>
										<?php
									}
								?>
						   </select> <?php echo $description; ?>
						</td>
					</tr><?php
					break;

				// Radio inputs
				case 'radio' :

					$option_value = self::get_option( $value['id'], $value['default'] );

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
							<?php echo $tooltip_html; ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
							<fieldset>
								<?php echo $description; ?>
								<ul>
								<?php
									foreach ( $value['options'] as $key => $val ) {
										?>
										<li>
											<label><input
												name="<?php echo esc_attr( $value['id'] ); ?>"
												value="<?php echo $key; ?>"
												type="radio"
												style="<?php echo esc_attr( $value['css'] ); ?>"
												class="<?php echo esc_attr( $value['class'] ); ?>"
												<?php echo implode( ' ', $custom_attributes ); ?>
												<?php checked( $key, $option_value ); ?>
												/> <?php echo $val ?></label>
										</li>
										<?php
									}
								?>
								</ul>
							</fieldset>
						</td>
					</tr><?php
					break;

				// Checkbox input
				case 'checkbox' :

					$option_value    = self::get_option( $value['id'], $value['default'] );
					$visbility_class = array();

					if ( ! isset( $value['hide_if_checked'] ) ) {
						$value['hide_if_checked'] = false;
					}
					if ( ! isset( $value['show_if_checked'] ) ) {
						$value['show_if_checked'] = false;
					}
					if ( 'yes' == $value['hide_if_checked'] || 'yes' == $value['show_if_checked'] ) {
						$visbility_class[] = 'hidden_option';
					}
					if ( 'option' == $value['hide_if_checked'] ) {
						$visbility_class[] = 'hide_options_if_checked';
					}
					if ( 'option' == $value['show_if_checked'] ) {
						$visbility_class[] = 'show_options_if_checked';
					}

					if ( ! isset( $value['checkboxgroup'] ) || 'start' == $value['checkboxgroup'] ) {
						?>
							<tr valign="top" class="<?php echo esc_attr( implode( ' ', $visbility_class ) ); ?>">
								<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?></th>
								<td class="forminp forminp-checkbox">
									<fieldset>
						<?php
					} else {
						?>
							<fieldset class="<?php echo esc_attr( implode( ' ', $visbility_class ) ); ?>">
						<?php
					}

					if ( ! empty( $value['title'] ) ) {
						?>
							<legend class="screen-reader-text"><span><?php echo esc_html( $value['title'] ) ?></span></legend>
						<?php
					}

					?>
						<label for="<?php echo $value['id'] ?>">
							<input
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="checkbox"
							 	class="<?php echo esc_attr(isset($value['class']) ? $value['class'] : ''); ?>"
								value="1"
								<?php checked( $option_value, 'yes'); ?>
								<?php echo implode( ' ', $custom_attributes ); ?>
							/> <?php echo $description ?>
						</label> <?php echo $tooltip_html; ?>
					<?php

					if ( ! isset( $value['checkboxgroup'] ) || 'end' == $value['checkboxgroup'] ) {
									?>
									</fieldset>
								</td>
							</tr>
						<?php
					} else {
						?>
							</fieldset>
						<?php
					}
					break;

				// Single page selects
				case 'single_select_page' :

					$args = array(
						'name'             => $value['id'],
						'id'               => $value['id'],
						'sort_column'      => 'menu_order',
						'sort_order'       => 'ASC',
						'show_option_none' => ' ',
						'class'            => $value['class'],
						'echo'             => false,
						'selected'         => absint( self::get_option( $value['id'] ) )
					);

					if ( isset( $value['args'] ) ) {
						$args = wp_parse_args( $value['args'], $args );
					}

					?><tr valign="top" class="single_select_page">
						<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ) ?> <?php echo $tooltip_html; ?></th>
						<td class="forminp">
							<?php echo str_replace(' id=', " data-placeholder='" . esc_attr__( 'Select a page&hellip;', 'als' ) .  "' style='" . $value['css'] . "' class='" . $value['class'] . "' id=", wp_dropdown_pages( $args ) ); ?> <?php echo $description; ?>
						</td>
					</tr><?php
					break;

				// Default: run an action
				default:
					do_action( 'als_admin_field_' . $value['type'], $value );
					break;
			}
		}
	}
	
	/**
	 * Helper function to get the formated description and tip HTML for a
	 * given form field. Plugins can call this when implementing their own custom
	 * settings types.
	 *
	 * @param array $value The form field value array
	 * @returns array The description and tip as a 2 element array
	 */
	public static function get_field_description( $value ) {
		$description  = '';
		$tooltip_html = '';

		if ( true === $value['desc_tip'] ) {
			$tooltip_html = $value['desc'];
		} elseif ( ! empty( $value['desc_tip'] ) ) {
			$description  = $value['desc'];
			$tooltip_html = $value['desc_tip'];
		} elseif ( ! empty( $value['desc'] ) ) {
			$description  = $value['desc'];
		}

		if ( $description && in_array( $value['type'], array( 'textarea', 'radio' ) ) ) {
			$description = '<p style="margin-top:0">' . wp_kses_post( $description ) . '</p>';
		} elseif ( $description && in_array( $value['type'], array( 'checkbox' ) ) ) {
			$description = wp_kses_post( $description );
		} elseif ( $description ) {
			$description = '<span class="description">' . wp_kses_post( $description ) . '</span>';
		}

		if ( $tooltip_html && in_array( $value['type'], array( 'checkbox' ) ) ) {
			$tooltip_html = '<p class="description">' . $tooltip_html . '</p>';
		} elseif ( $tooltip_html ) {
			$tooltip_html = als_help_tip( $tooltip_html );
		}

		return array(
			'description'  => $description,
			'tooltip_html' => $tooltip_html
		);
	}
	
	/**
	 * Save admin fields.
	 *
	 *
	 * @param array $options
	 * @return bool
	 */
	public static function save_fields( $options ) {
		if ( empty( $_POST ) ) {
			return false;
		}

		// Options to update will be stored here and saved later
		$update_options = array();

		// Loop options and get values to save
		foreach ( $options as $option ) {
			if ( ! isset( $option['id'] ) || ! isset( $option['type'] ) ) {
				continue;
			}

			// Get posted value
			if ( strstr( $option['id'], '[' ) ) {
				parse_str( $option['id'], $option_name_array );
				$option_name  = current( array_keys( $option_name_array ) );
				$setting_name = key( $option_name_array[ $option_name ] );
				$raw_value    = isset( $_POST[ $option_name ][ $setting_name ] ) ? wp_unslash( $_POST[ $option_name ][ $setting_name ] ) : null;
			} else {
				$option_name  = $option['id'];
				$setting_name = '';
				$raw_value    = isset( $_POST[ $option['id'] ] ) ? wp_unslash( $_POST[ $option['id'] ] ) : null;
			}

			// Format the value based on option type
			switch ( $option['type'] ) {
				case 'checkbox' :
					$value = is_null( $raw_value ) ? 'no' : 'yes';
					break;
				case 'textarea' :
					$value = wp_kses_post( trim( $raw_value ) );
					break;
				case 'multiselect' :
				case 'multi_select_countries' :
					$value = array_filter( array_map( 'als_clean', (array) $raw_value ) );
					break;
				case 'image_width' :
					$value = array();
					if ( isset( $raw_value['width'] ) ) {
						$value['width']  = als_clean( $raw_value['width'] );
						$value['height'] = als_clean( $raw_value['height'] );
						$value['crop']   = isset( $raw_value['crop'] ) ? 1 : 0;
					} else {
						$value['width']  = $option['default']['width'];
						$value['height'] = $option['default']['height'];
						$value['crop']   = $option['default']['crop'];
					}
					break;
				default :
					$value = als_clean( $raw_value );
					break;
			}

			/**
			 * Sanitize the value of an option.
			 */
			$value = apply_filters( 'als_admin_settings_sanitize_option', $value, $option, $raw_value );

			/**
			 * Sanitize the value of an option by option name.
			 */
			$value = apply_filters( "als_admin_settings_sanitize_option_$option_name", $value, $option, $raw_value );

			if ( is_null( $value ) ) {
				continue;
			}

			// Check if option is an array and handle that differently to single values.
			if ( $option_name && $setting_name ) {
				if ( ! isset( $update_options[ $option_name ] ) ) {
					$update_options[ $option_name ] = get_option( $option_name, array() );
				}
				if ( ! is_array( $update_options[ $option_name ] ) ) {
					$update_options[ $option_name ] = array();
				}
				$update_options[ $option_name ][ $setting_name ] = $value;
			} else {
				$update_options[ $option_name ] = $value;
			}

		}

		// Save all options in our array
		foreach ( $update_options as $name => $value ) {
			update_option( $name, $value );
		}

		return true;
	}
	
}
