<?php
/*
Plugin Name:   WP H-Data Protection
Plugin URI:    https://github.com/m266/wp-h-data-protection
Description:   Datenschutz f&uuml;r WordPress
Author:        Hans M. Herbrand
Author URI:    https://www.web266.de
Version:       1.2.2
Date:          2018-05-02
License:       GNU General Public License v2 or later
License URI:   http://www.gnu.org/licenses/gpl-2.0.html
 */
// Externer Zugriff verhindern
defined('ABSPATH') || exit();
// Variablen deklarieren
global $wpdb;
/* Plugin-Updater */
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker('https://github.com/m266/wp-h-data-protection', __FILE__, 'wp-h-data-protection');
class WPHDataProtection
{
    private $wp_h_data_protection_options;
    public function __construct()
    {
        add_action('admin_menu', array($this, 'wp_h_data_protection_add_plugin_page'));
        add_action('admin_init', array($this, 'wp_h_data_protection_page_init'));
    }
    public function wp_h_data_protection_add_plugin_page()
    {
        add_menu_page('WP H-Data Protection', // page_title
            'WP H-Data Protection', // menu_title
            'manage_options', // capability
            'wp-h-data-protection', // menu_slug
            array($this, 'wp_h_data_protection_create_admin_page'), // function
            'dashicons-shield', // icon_url
            81// position
        );
    }
    public function wp_h_data_protection_create_admin_page()
    {
        $this->wp_h_data_protection_options = get_option('wp_h_data_protection_option_name');
        ?>

        <div class="wrap">
<h2>
                                            <?php
// Plugin-Name und Versions-Nummer ermitteln
        function wphdp_plugin_name_get_data()
        {
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
        <h3><b>(Das Plugin ist auf <a href="https://www.web266.de/software/eigene-plugins/wp-h-data-protection/" target="_blank">web266.de</a> detailliert beschrieben)</b></h3>
            <hr>
            <?php settings_errors();?>
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
    public function wp_h_data_protection_page_init()
    {
        register_setting('wp_h_data_protection_option_group', // option_group
            'wp_h_data_protection_option_name', // option_name
            array($this, 'wp_h_data_protection_sanitize') // sanitize_callback
        );
        add_settings_section('wp_h_data_protection_setting_section', // id
            '', // title
            array($this, 'wp_h_data_protection_section_info'), // callback
            'wp-h-data-protection-admin' // page
        );
        // Kommentare, Plugin Flamingo
        $wphdp_flamingo = "Kommmentare";
        if ( is_plugin_active( 'flamingo/flamingo.php' ) ) {
        // Plugin Flamingo aktiv
        $wphdp_flamingo = "Kommentare, Plugin Flamingo";
        }
        add_settings_field('checkbox_1_0', // id
            $wphdp_flamingo, // title
            array($this, 'checkbox_1_0_callback'), // callback
            'wp-h-data-protection-admin', // page
            'wp_h_data_protection_setting_section' // section
        );
        // Plugin WP Cerber
        if ( is_plugin_active( 'wp-cerber/wp-cerber.php' ) ) {
        // Plugin ist aktiv
        add_settings_field('checkbox_2_1', // id
            'Plugin WP Cerber', // title
            array($this, 'checkbox_2_1_callback'), // callback
            'wp-h-data-protection-admin', // page
            'wp_h_data_protection_setting_section' // section
        );
        }
        // Plugin "MailPoet 2"
        if ( is_plugin_active( 'wysija-newsletters/index.php' ) and is_plugin_inactive( 'mailpoet-remove-tracking/mailpoet-remove-tracking.php' ) ){
        // Plugin "MailPoet 2" ist aktiv oder Plugin "MailPoet Remove tracking" ist inaktiv
    add_settings_field('checkbox_3_2', // id
    'Plugin MailPoet 2', // title
    array($this, 'checkbox_3_2_callback'), // callback
    'wp-h-data-protection-admin', // page
    'wp_h_data_protection_setting_section' // section
    );
    }
        }
    public function wp_h_data_protection_sanitize($input)
    {
        $sanitary_values = array();
        // Kommentare, Plugin Flamingo
        if (isset($input['checkbox_1_0'])) {
            $sanitary_values['checkbox_1_0'] = $input['checkbox_1_0'];
        }
        // Plugin WP Cerber
        if (isset($input['checkbox_2_1'])) {
            $sanitary_values['checkbox_2_1'] = $input['checkbox_2_1'];
        }
                // Plugin "MailPoet 2"
        if (isset($input['checkbox_3_2'])) {
        $sanitary_values['checkbox_3_2'] = $input['checkbox_3_2'];
        }
        return $sanitary_values;
    }
    public function wp_h_data_protection_section_info()
    {
    }
    // Kommentare, Plugin Flamingo
    public function checkbox_1_0_callback()
    {
        if ( is_plugin_active( 'flamingo/flamingo.php' ) ) {
        // Plugin Flamingo aktiv
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_1_0]" id="checkbox_1_0" value="checkbox_1_0" %s> <label for="checkbox_1_0">Entfernt IP-Adressen aus Kommentaren und dem Plugin Flamingo</label>', (isset($this->wp_h_data_protection_options['checkbox_1_0']) && $this->wp_h_data_protection_options['checkbox_1_0'] === 'checkbox_1_0') ? 'checked' : '');
        }
        else {
            printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_1_0]" id="checkbox_1_0" value="checkbox_1_0" %s> <label for="checkbox_1_0">Entfernt IP-Adressen aus Kommentaren</label>', (isset($this->wp_h_data_protection_options['checkbox_1_0']) && $this->wp_h_data_protection_options['checkbox_1_0'] === 'checkbox_1_0') ? 'checked' : '');
        }
    }
    // Plugin WP Cerber
    public function checkbox_2_1_callback()
    {
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_2_1]" id="checkbox_2_1" value="checkbox_2_1" %s> <label for="checkbox_2_1">Entfernt IP-Adressen aus dem Traffic Inspector</label>', (isset($this->wp_h_data_protection_options['checkbox_2_1']) && $this->wp_h_data_protection_options['checkbox_2_1'] === 'checkbox_2_1') ? 'checked' : '');
    }// Plugin "MailPoet 2"

public function checkbox_3_2_callback() {
printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_3_2]" id="checkbox_3_2" value="checkbox_3_2" %s> <label for="checkbox_3_2">Tracking deaktivieren</label>',
(isset($this->wp_h_data_protection_options['checkbox_3_2']) && $this->wp_h_data_protection_options['checkbox_3_2'] === 'checkbox_3_2') ? 'checked' : '');
}
}
if (is_admin()) {
    $wp_h_data_protection = new WPHDataProtection();
}

/*
 * Retrieve this value with:
 * $wp_h_data_protection_options = get_option( 'wp_h_data_protection_option_name' ); // Array of All Options
 * $checkbox_1_0 = $wp_h_data_protection_options['checkbox_1_0']; // Checkbox 1
 * $checkbox_2_1 = $wp_h_data_protection_options['checkbox_2_1']; // Checkbox 2
 * $checkbox_3_2 = $wp_h_data_protection_options['checkbox_3_2']; // Checkbox 3
 */
// Inhalte aus Datenbank laden
$wp_h_data_protection_options = get_option('wp_h_data_protection_option_name'); // Array of All Options
// Kommentare, Plugin Flamingo
if (isset($wp_h_data_protection_options['checkbox_1_0'])) { // Wenn aktiviert, lade Script
    // IP-Adressen nicht mehr speichern
    $_SERVER['REMOTE_ADDR'] = '';
    // Alte IP-Adressen aus Kommentaren entfernen
    $wpdb->query('UPDATE ' . $wpdb->prefix . 'comments SET comment_author_IP = "";');
}
// Plugin WP Cerber
if (isset($wp_h_data_protection_options['checkbox_2_1'])) { // Wenn aktiviert, lade Script
    // Tabelle cerber_traffic
    $wpdb->query('ALTER TABLE cerber_traffic MODIFY ip VARCHAR(3)'); // Spaltenbreite 3 Zeichen
    $wpdb->query('ALTER TABLE cerber_traffic MODIFY ip_long VARCHAR(3)'); // Spaltenbreite 3 Zeichen
} else { // Wenn inaktiv, stelle Spaltenbreit Original her
    // Tabelle cerber_traffic
    $wpdb->query('ALTER TABLE cerber_traffic MODIFY ip VARCHAR(39)'); // Spaltenbreite Original
    $wpdb->query('ALTER TABLE cerber_traffic MODIFY ip_long VARCHAR(20)'); // Spaltenbreite Original
}
;
// Plugin "MailPoet 2"
if (isset($wp_h_data_protection_options['checkbox_3_2'])) { // Wenn aktiviert, lade Script
   require_once 'inc/wphdp_mailpoet2_tracking_deaktivieren.php';
    // Alte Daten aus der Tracking Statistik entfernen
    $wphdp_table_name = $wpdb->prefix . "wysija_email_user_stat";
    $wpdb->query("TRUNCATE TABLE $wphdp_table_name");
}
?>