<?php
/**
 * Plugin Name: Affylite - Easy Affiliate Disclosure and Disclaimer
 * Description: Add a simple affiliate disclosure and disclaimer to the top of each post. Easy, lite, and no bloat. Under 5kb.
 * Version:     1.2
 * Author:      Deep Creek Lake, Maryland Marketing
 * Author URI:  https://md-wv.com
 * License:     GPLv2 or later.
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: affylite
*/

// Admin page to set a custom affiliate disclosure and disclaimer
add_action( 'admin_menu', 'affylite_add_admin_menu' );
add_action( 'admin_init', 'affylite_settings_init' );

	// Admin page settings
	function affylite_add_admin_menu(  ) { 
		add_menu_page( 'Affylite', 'Affylite', 'manage_options', 'affylite', 'affylite_options_page', 'dashicons-format-chat');
	}

	// Admin page section descriptions
	function affylite_settings_init(  ) { 
		register_setting( 'pluginPage', 'affylite_settings' );
		add_settings_section(
			'affylite_pluginPage_section', 
			__( 'Add a simple affiliate disclosure and disclaimer to the top of each post.', 'affylite' ), 
			'affylite_settings_section_callback', 
			'pluginPage'
		);
		add_settings_field( 
			'affylite_custom_disclaimer', 
			__( 'Affylite supports basic HTML.', 'affylite' ), 
			'affylite_custom_disclaimer_render', 
			'pluginPage', 
			'affylite_pluginPage_section' 
		);
	}

	// Admin page custom textarea
	function affylite_custom_disclaimer_render(  ) { 
		$affylite_options = get_option( 'affylite_settings' );
		?>
		<p>
			<textarea cols='50' rows='10' name='affylite_settings[affylite_custom_disclaimer]'><?php echo $affylite_options['affylite_custom_disclaimer']; ?></textarea>
		</p>
		<?php
	}

	// Admin page render
	function affylite_options_page(  ) { 
			?>
			<form action='options.php' method='post'>
				<h2>Affylite - Easy Affiliate Disclosure and Disclaimer</h2>
				<?php
				settings_fields( 'pluginPage' );
				do_settings_sections( 'pluginPage' );
				submit_button();
				?>
			</form>
			<?php
	}

// Append post text
function affylite_filter( $original ) {
	$affylite_options = get_option( 'affylite_settings' );
	if ( $affylite_options['affylite_custom_disclaimer'] != '' ) {
		if ( is_singular('post') ) {
			$affylite_disclaimer = '<p><em>' . $affylite_options['affylite_custom_disclaimer'] . '</em></p>';
			$affylite_disclaimer .= $original;
			return $affylite_disclaimer;
		}
		else {
			return $original;
		}
	}
	else {
		if ( is_singular('post') ) {
			$affylite_disclaimer = '<p><em>We use affiliate links. If you purchase something using one of these links, we may receive compensation or commission.</em></p>';
			$affylite_disclaimer .= $original;
			return $affylite_disclaimer;
		}
		else {
			return $original;
		}
	}
}

add_filter( 'the_content', 'affylite_filter' );
