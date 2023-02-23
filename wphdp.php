<?php
/*
Plugin Name:       WP H-Data Protection
Plugin URI:        https://github.com/m266/wp-h-data-protection
Description:       Datenschutz f&uuml;r WordPress
Author:            Hans M. Herbrand
Author URI:        https://herbrand.org
Version:           2.3.1
Date:              2023-02-21
License:           GNU General Public License v2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html
GitHub Plugin URI: https://github.com/m266/wp-h-data-protection
 */
// Externer Zugriff verhindern
defined('ABSPATH') || exit();

//////////////////////////////////////////////////////////////////////////////////////////

// Erinnerung an Git Updater  
register_activation_hook( __FILE__, 'wphdp_activate' ); // Funktions-Name anpassen
function wphdp_activate() { // Funktions-Name anpassen
$to = get_option('admin_email');
$subject = 'Plugin "WP H-Data Protection"'; // Plugin-Name anpassen
$message = 'Falls nicht vorhanden:
Bitte das Plugin "Git Updater" hier https://herbrand.org/tutorials/github/git-updater/ herunterladen, installieren und aktivieren, um weiterhin Updates zu erhalten!';
wp_mail($to, $subject, $message );
}

//////////////////////////////////////////////////////////////////////////////////////////

global $wpdb;
class WPHDataProtection {
    private $wp_h_data_protection_options;
    public function __construct() {
        add_action('admin_menu', array($this, 'wp_h_data_protection_add_plugin_page'));
        add_action('admin_init', array($this, 'wp_h_data_protection_page_init'));
    }
    public function wp_h_data_protection_add_plugin_page() {
        add_menu_page('WP H-Data Protection', // page_title
            'WP H-Data Protection', // menu_title
            'manage_options', // capability
            'wp-h-data-protection', // menu_slug
            array($this, 'wp_h_data_protection_create_admin_page'), // function
            'dashicons-shield', // icon_url
            81// position
        );
    }
    public function wp_h_data_protection_create_admin_page() {
        $this->wp_h_data_protection_options = get_option('wp_h_data_protection_option_name');
        ?>

        <div class="wrap">
<h2>
                                            <?php
// Plugin-Name und Versions-Nummer ermitteln
        function wphdp_plugin_name_get_data() {
            $wphdp_ = get_plugin_data(__FILE__);
            $wphdp_plugin_name = $wphdp_['Name'];
            $wphdp_plugin_version = $wphdp_['Version'];
            $wphdp_plugin_name_version = $wphdp_plugin_name . " " . $wphdp_plugin_version;
            return $wphdp_plugin_name_version;
        }
        $wphdp_menu_name_version = wphdp_plugin_name_get_data();
        echo $wphdp_menu_name_version . " > " . "Einstellungen"; // Plugin-Name und Versions-Nummer ausgeben
        ?>
</h2>
<div class="card">
<form method="post" action="options.php">
                                        <?php
settings_fields('wp_h_data_protection_option_group');
        do_settings_sections('wp-h-data-protection-admin');
        submit_button();
        ?>
            </form>
            </div>
        </div>
                            <?php
}
//////////////////////////////////////////////////////////////////////////////////////////
    public function wp_h_data_protection_page_init() {
        register_setting('wp_h_data_protection_option_group', // option_group
            'wp_h_data_protection_option_name', // option_name
            array($this, 'wp_h_data_protection_sanitize') // sanitize_callback
        );
        add_settings_section('wp_h_data_protection_setting_section', // id
            '', // title
            array($this, 'wp_h_data_protection_section_info'), // callback
            'wp-h-data-protection-admin' // page
        );
        // Kommentare, Plugins Flamingo, WP H-Guestbook
        $wphdp_option_1 = "Kommmentare";
        if (is_plugin_active('flamingo/flamingo.php')) {
            // Plugin Flamingo aktiv?
            $wphdp_option_1_flamingo = "<br>- Flamingo";
        } else {
            $wphdp_option_1_flamingo = '';
        }
        if (is_plugin_active('wp-h-guestbook/wphgb.php')) {
            // Plugin WP H-Guestbook aktiv?
            $wphdp_option_1_guestbook = "<br>- WP H-Guestbook";
        } else {
            $wphdp_option_1_guestbook = '';
        }
        add_settings_field('checkbox_1_0', // id
            'Kommmentare' . $wphdp_option_1_flamingo . $wphdp_option_1_guestbook, // title
            array($this, 'checkbox_1_0_callback'), // callback
            'wp-h-data-protection-admin', // page
            'wp_h_data_protection_setting_section' // section
        );
        // Kommentare, WP H-Guestbook - Zustimmung zur Datenverarbeitung
        add_settings_field('checkbox_1_0_1', // id
            'Kommmentare' . $wphdp_option_1_guestbook . '<br>(Zustimmung zur Datenverarbeitung)', // title
            array($this, 'checkbox_1_0_1_callback'), // callback
            'wp-h-data-protection-admin', // page
            'wp_h_data_protection_setting_section' // section
        );
        // Kommentare, WP H-Guestbook - Entfernt das Feld Website aus dem Formular
        add_settings_field('checkbox_1_0_2', // id
            'Kommmentare' . $wphdp_option_1_guestbook, // title
            array($this, 'checkbox_1_0_2_callback'), // callback
            'wp-h-data-protection-admin', // page
            'wp_h_data_protection_setting_section' // section
        );
    }
//////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////
    public function wp_h_data_protection_sanitize($input) {
        $sanitary_values = array();
        // Kommentare, Plugin Flamingo
        if (isset($input['checkbox_1_0'])) {
            $sanitary_values['checkbox_1_0'] = $input['checkbox_1_0'];
        }
        // Kommentare, WP H-Guestbook - Zustimmung zur Datenverarbeitung
        if (isset($input['checkbox_1_0_1'])) {
            $sanitary_values['checkbox_1_0_1'] = $input['checkbox_1_0_1'];
        }
        // Kommentare, WP H-Guestbook - Entfernt das Feld Website aus dem Formular
        if (isset($input['checkbox_1_0_2'])) {
            $sanitary_values['checkbox_1_0_2'] = $input['checkbox_1_0_2'];
        }
        return $sanitary_values;
    }
//////////////////////////////////////////////////////////////////////////////////////////
    public function wp_h_data_protection_section_info() {
    }
//////////////////////////////////////////////////////////////////////////////////////////
    // Kommentare, Plugin Flamingo
    public function checkbox_1_0_callback() {
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_1_0]" id="checkbox_1_0" value="checkbox_1_0" %s> <label for="checkbox_1_0">Entfernt die IP-Adressen aus den Kommentaren und, wenn aktiviert, aus den genannten Plugins</label>', (isset($this->wp_h_data_protection_options['checkbox_1_0']) && $this->wp_h_data_protection_options['checkbox_1_0'] === 'checkbox_1_0') ? 'checked' : '');
    }
    // Kommentare, WP H-Guestbook - Zustimmung zur Datenverarbeitung
    public function checkbox_1_0_1_callback() {
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_1_0_1]" id="checkbox_1_0_1" value="checkbox_1_0_1" %s> <label for="checkbox_1_0_1">Aktiviert den Privacy-Check und, wenn aktiviert, in den genannten Plugins.<br>Der Link zur Datenschutzerkl&auml;rung funktioniert nur, <a href="options-privacy.php">wenn diese Seite von WordPress selbst verwaltet wird!</a> (Empfohlen)</label>', (isset($this->wp_h_data_protection_options['checkbox_1_0_1']) && $this->wp_h_data_protection_options['checkbox_1_0_1'] === 'checkbox_1_0_1') ? 'checked' : '');
    }
    // Kommentare, WP H-Guestbook - Entfernt das Feld Website aus dem Formular
    public function checkbox_1_0_2_callback() {
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_1_0_2]" id="checkbox_1_0_2" value="checkbox_1_0_2" %s> <label for="checkbox_1_0_2">Entfernt das Feld "Website" aus den Kommentaren und, wenn aktiviert, aus den genannten Plugins</label>', (isset($this->wp_h_data_protection_options['checkbox_1_0_2']) && $this->wp_h_data_protection_options['checkbox_1_0_2'] === 'checkbox_1_0_2') ? 'checked' : '');
    }
}
//////////////////////////////////////////////////////////////////////////////////////////
if (is_admin()) {
    $wp_h_data_protection = new WPHDataProtection();
}

/*
 * Retrieve this value with:
 * $wp_h_data_protection_options = get_option( 'wp_h_data_protection_option_name' ); // Array of All Options
 * $checkbox_1_0 = $wp_h_data_protection_options['checkbox_1_0']; // Checkbox 1
 * $checkbox_3_2 = $wp_h_data_protection_options['checkbox_3_2']; // Checkbox 3
 * $textbox_1    = $wp_h_data_protection_options['textbox_1'];    // Textbox 1
 */

//////////////////////////////////////////////////////////////////////////////////////////
// Inhalte aus Datenbank laden
$wp_h_data_protection_options = get_option('wp_h_data_protection_option_name'); // Array of All Options
// Kommentare, Plugin Flamingo
if (isset($wp_h_data_protection_options['checkbox_1_0'])) { // Wenn aktiviert, lade Script
    // IP-Adressen nicht mehr speichern
    $_SERVER['REMOTE_ADDR'] = '';
    // Alte IP-Adressen aus Kommentaren entfernen
    $wpdb->query('UPDATE ' . $wpdb->prefix . 'comments SET comment_author_IP = "";');
}
// Kommentare, WP H-Guestbook - Zustimmung zur Datenverarbeitung
if (isset($wp_h_data_protection_options['checkbox_1_0_1'])) { // Wenn aktiviert, lade Script
    require_once 'inc/wphdp_privacy_checkbox.php';
}
// Kommentare, WP H-Guestbook - Entfernt das Feld Website aus dem Formular
if (isset($wp_h_data_protection_options['checkbox_1_0_2'])) { // Wenn aktiviert, lade Script
    require_once 'inc/wphdp_remove_url_kommentar_guestbook.php';
}

//////////////////////////////////////////////////////////////////////////////////////////
// Abfrage der WordPress-Benutzer verhindern (User Enumeration)
// Quelle: https://kulturbanause.de/blog/wordpress-user-enumeration-verhindern/
if ( ! is_admin() && isset($_SERVER['REQUEST_URI'])){
 if(preg_match('/(wp-comments-post)/', $_SERVER['REQUEST_URI']) === 0 && !empty($_REQUEST['author']) ) {
 wp_die('Diese Abfrage ist nicht erlaubt!');
 }
}

//////////////////////////////////////////////////////////////////////////////////////////
// Login-Fehlermeldung deaktivieren
// Quelle: https://www.wpbeginner.com/wp-tutorials/how-to-disable-login-hints-in-wordpress-login-error-messages/
function no_wordpress_errors(){
    return 'Fehler!';
}
add_filter( 'login_errors', 'no_wordpress_errors' );
;
;
?>