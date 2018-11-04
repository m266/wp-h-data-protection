<?php
/*
Plugin Name:   WP H-Data Protection
Plugin URI:    https://github.com/m266/wp-h-data-protection
Description:   Datenschutz f&uuml;r WordPress
Author:        Hans M. Herbrand
Author URI:    https://www.web266.de
Version:       1.3.7
Date:          2018-11-04
License:       GNU General Public License v2 or later
License URI:   http://www.gnu.org/licenses/gpl-2.0.html
GitHub Plugin URI: https://github.com/m266/wp-h-data-protection
 */
// Externer Zugriff verhindern
defined('ABSPATH') || exit();
// Variablen deklarieren
global $wpdb;
// Makes sure the plugin is defined before trying to use it
if (!function_exists('is_plugin_active')) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}
// Makes sure the plugin is defined before trying to use it
if (!function_exists('is_plugin_inactive')) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}
// GitHub-Updater aktiv?
// Makes sure the plugin is defined before trying to use it
if (!function_exists('is_plugin_active')) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}
// Makes sure the plugin is defined before trying to use it
if (!function_exists('is_plugin_inactive')) {
    require_once ABSPATH . '/wp-admin/includes/plugin.php';
}
// GitHub-Updater inaktiv?
if (is_plugin_inactive('github-updater/github-updater.php')) {
    // Plugin ist inaktiv
    // Plugin-Name im Meldungstext anpassen
    function wphdp_missing_github_updater_notice() {; // GitHub-Updater fehlt
        ?>
    <div class="error notice">  <!-- Wenn ja, Meldung ausgeben -->
        <p><?php _e('Bitte das Plugin <a href="https://www.web266.de/tutorials/github/github-updater/" target="_blank">
        <b>"GitHub-Updater"</b></a> herunterladen, installieren und aktivieren.
        Ansonsten werden keine weiteren Updates f&uuml;r das Plugin <b>"WP H-Data Protection"</b> bereit gestellt!');?></p>
    </div>
                        <?php
}
    add_action('admin_notices', 'wphdp_missing_github_updater_notice');
}
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
        // Kommentare, WP H-Guestbook - Cookie-Checkbox entfernen
        add_settings_field('checkbox_1_0_2', // id
            'Kommmentare' . $wphdp_option_1_guestbook . '<br>(Cookie-Checkbox entfernen)', // title
            array($this, 'checkbox_1_0_2_callback'), // callback
            'wp-h-data-protection-admin', // page
            'wp_h_data_protection_setting_section' // section
        );
        // Plugin WP Cerber
        if (is_plugin_active('wp-cerber/wp-cerber.php')) {
            // Plugin ist aktiv
            add_settings_field('checkbox_2_1', // id
                'Plugin WP Cerber', // title
                array($this, 'checkbox_2_1_callback'), // callback
                'wp-h-data-protection-admin', // page
                'wp_h_data_protection_setting_section' // section
            );
        }
        // Plugin "MailPoet 2"
        if (is_plugin_active('wysija-newsletters/index.php') and is_plugin_inactive('mailpoet-remove-tracking/mailpoet-remove-tracking.php')) {
            // Plugin "MailPoet 2" ist aktiv oder Plugin "MailPoet Remove tracking" ist inaktiv
            add_settings_field('checkbox_3_2', // id
                'Plugin MailPoet 2', // title
                array($this, 'checkbox_3_2_callback'), // callback
                'wp-h-data-protection-admin', // page
                'wp_h_data_protection_setting_section' // section
            );
        }
        // E-Mail-Adresse zur Newsletter-Abmeldung
        add_settings_field('textbox_1', // id
            'E-Mail-Adresse zur Newsletter-Abmeldung', // title
            array($this, 'textbox_1_callback'), // callback
            'wp-h-data-protection-admin', // page
            'wp_h_data_protection_setting_section' // section
        );
        // Nachfolgende Optionen nur bei SBR-Theme anzeigen
        $wphdp_theme_sbr_activ = wp_get_theme(); // SBR-Theme aktiv?
        if ('SBR' == $wphdp_theme_sbr_activ->name) {
            // Website-URL
            add_settings_field('checkbox_4_3', // id
                'Website-URL', // title
                array($this, 'checkbox_4_3_callback'), // callback
                'wp-h-data-protection-admin', // page
                'wp_h_data_protection_setting_section' // section
            );
            // Link zum Kontaktformular
            add_settings_field('checkbox_5_4', // id
                'Link zum Kontaktformular', // title
                array($this, 'checkbox_5_4_callback'), // callback
                'wp-h-data-protection-admin', // page
                'wp_h_data_protection_setting_section' // section
            );
            // Link zum Anmeldeformular
            add_settings_field('checkbox_6_5', // id
                'Link zum Anmeldeformular', // title
                array($this, 'checkbox_6_5_callback'), // callback
                'wp-h-data-protection-admin', // page
                'wp_h_data_protection_setting_section' // section
            );
            // Link zum G&auml;stebuch
            if (is_plugin_active('wp-h-guestbook/wphgb.php')) {
                // Plugin ist aktiv
                add_settings_field('checkbox_7_6', // id
                    'Link zum G&auml;stebuch', // title
                    array($this, 'checkbox_7_6_callback'), // callback
                    'wp-h-data-protection-admin', // page
                    'wp_h_data_protection_setting_section' // section
                );
            }
        }
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
        // Kommentare, WP H-Guestbook - Cookie-Checkbox entfernen
        if (isset($input['checkbox_1_0_2'])) {
            $sanitary_values['checkbox_1_0_2'] = $input['checkbox_1_0_2'];
        }
        // Plugin WP Cerber
        if (isset($input['checkbox_2_1'])) {
            $sanitary_values['checkbox_2_1'] = $input['checkbox_2_1'];
        }
        // Plugin "MailPoet 2"
        if (isset($input['checkbox_3_2'])) {
            $sanitary_values['checkbox_3_2'] = $input['checkbox_3_2'];
        }
        // E-Mail-Adresse zur Newsletter-Abmeldung
        if (isset($input['textbox_1'])) {
            $sanitary_values['textbox_1'] = sanitize_text_field($input['textbox_1']);
        }
        // Website-URL
        if (isset($input['checkbox_4_3'])) {
            $sanitary_values['checkbox_4_3'] = $input['checkbox_4_3'];
        }
        // Link zum Kontaktformular
        if (isset($input['checkbox_5_4'])) {
            $sanitary_values['checkbox_5_4'] = $input['checkbox_5_4'];
        }
        // Link zum Anmeldeformular
        if (isset($input['checkbox_6_5'])) {
            $sanitary_values['checkbox_6_5'] = $input['checkbox_6_5'];
        }
        // Link zum G&auml;stebuch
        if (isset($input['checkbox_7_6'])) {
            $sanitary_values['checkbox_7_6'] = $input['checkbox_7_6'];
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
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_1_0_1]" id="checkbox_1_0_1" value="checkbox_1_0_1" %s> <label for="checkbox_1_0_1">Aktiviert den Privacy-Check (Empfohlen)</label>', (isset($this->wp_h_data_protection_options['checkbox_1_0_1']) && $this->wp_h_data_protection_options['checkbox_1_0_1'] === 'checkbox_1_0_1') ? 'checked' : '');
    }
    // Kommentare, WP H-Guestbook - Cookie-Checkbox entfernen
    public function checkbox_1_0_2_callback() {
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_1_0_2]" id="checkbox_1_0_2" value="checkbox_1_0_2" %s> <label for="checkbox_1_0_2">Entfernt die Cookie-Checkbox</label>', (isset($this->wp_h_data_protection_options['checkbox_1_0_2']) && $this->wp_h_data_protection_options['checkbox_1_0_2'] === 'checkbox_1_0_2') ? 'checked' : '');
    }
    // Plugin WP Cerber
    public function checkbox_2_1_callback() {
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_2_1]" id="checkbox_2_1" value="checkbox_2_1" %s> <label for="checkbox_2_1">Entfernt die IP-Adressen aus dem Traffic Inspector</label>', (isset($this->wp_h_data_protection_options['checkbox_2_1']) && $this->wp_h_data_protection_options['checkbox_2_1'] === 'checkbox_2_1') ? 'checked' : '');
    }
    // Plugin "MailPoet 2"
    public function checkbox_3_2_callback() {
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_3_2]" id="checkbox_3_2" value="checkbox_3_2" %s> <label for="checkbox_3_2">Deaktiviert das User-Tracking</label>', (isset($this->wp_h_data_protection_options['checkbox_3_2']) && $this->wp_h_data_protection_options['checkbox_3_2'] === 'checkbox_3_2') ? 'checked' : '');
    }
    // E-Mail-Adresse zur Newsletter-Abmeldung
    public function textbox_1_callback() {
        printf(
            '<input class="regular-text" type="text" name="wp_h_data_protection_option_name[textbox_1]" id="textbox_1" value="%s"> <label for="textbox_1">E-Mail-Adresse eingeben <br> <strong>(Nur wenn ein Newsletter-Tool vorhanden ist!) </strong></label>',
            isset($this->wp_h_data_protection_options['textbox_1']) ? esc_attr($this->wp_h_data_protection_options['textbox_1']) : ''
        );
    }
    // Website-URL
    public function checkbox_4_3_callback() {
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_4_3]" id="checkbox_4_3" value="checkbox_4_3" %s> <label for="checkbox_4_3">F&uuml;gt die Website-URL im Datenschutz-Hinweis ein</label>', (isset($this->wp_h_data_protection_options['checkbox_4_3']) && $this->wp_h_data_protection_options['checkbox_4_3'] === 'checkbox_4_3') ? 'checked' : '');
    }
    // Link zum Kontaktformular
    public function checkbox_5_4_callback() {
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_5_4]" id="checkbox_5_4" value="checkbox_5_4" %s> <label for="checkbox_5_4">F&uuml;gt den Link zum Kontakt-Formular ein</label>', (isset($this->wp_h_data_protection_options['checkbox_5_4']) && $this->wp_h_data_protection_options['checkbox_5_4'] === 'checkbox_5_4') ? 'checked' : '');
    }
    // Link zum Anmeldeformular
    public function checkbox_6_5_callback() {
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_6_5]" id="checkbox_6_5" value="checkbox_6_5" %s> <label for="checkbox_6_5">F&uuml;gt den Link zum Anmelde-Formular ein</label>', (isset($this->wp_h_data_protection_options['checkbox_6_5']) && $this->wp_h_data_protection_options['checkbox_6_5'] === 'checkbox_6_5') ? 'checked' : '');
    }
    // Link zum G&auml;stebuch
    public function checkbox_7_6_callback() {
        printf('<input type="checkbox" name="wp_h_data_protection_option_name[checkbox_7_6]" id="checkbox_7_6" value="checkbox_7_6" %s> <label for="checkbox_7_6">F&uuml;gt den Link zum G&auml;stebuch ein</label>', (isset($this->wp_h_data_protection_options['checkbox_7_6']) && $this->wp_h_data_protection_options['checkbox_7_6'] === 'checkbox_7_6') ? 'checked' : '');
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
 * $checkbox_2_1 = $wp_h_data_protection_options['checkbox_2_1']; // Checkbox 2
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
// Kommentare, WP H-Guestbook - Cookie-Checkbox entfernen
if (isset($wp_h_data_protection_options['checkbox_1_0_2'])) { // Wenn aktiviert, lade Script
    require_once 'inc/wphdp_comment_form_remove_cookie_checkbox.php';
}
// Plugin WP Cerber
if (is_plugin_active('wp-cerber/wp-cerber.php')) {
    // Plugin ist aktiv
    if (isset($wp_h_data_protection_options['checkbox_2_1'])) { // Wenn aktiviert, lade Script
        // Tabelle cerber_traffic
        $wpdb->query('ALTER TABLE cerber_traffic MODIFY ip VARCHAR(3)'); // Spaltenbreite 3 Zeichen
        $wpdb->query('ALTER TABLE cerber_traffic MODIFY ip_long VARCHAR(3)'); // Spaltenbreite 3 Zeichen
    } else { // Wenn inaktiv, stelle Spaltenbreit Original her
        // Tabelle cerber_traffic
        $wpdb->query('ALTER TABLE cerber_traffic MODIFY ip VARCHAR(39)'); // Spaltenbreite Original
        $wpdb->query('ALTER TABLE cerber_traffic MODIFY ip_long VARCHAR(20)'); // Spaltenbreite Original
    }
}
;
// Plugin "MailPoet 2"
if (is_plugin_active('wysija-newsletters/index.php') and is_plugin_inactive('mailpoet-remove-tracking/mailpoet-remove-tracking.php')) {
    // Plugin "MailPoet 2" ist aktiv oder Plugin "MailPoet Remove tracking" ist inaktiv
    if (isset($wp_h_data_protection_options['checkbox_3_2'])) { // Wenn aktiviert, lade Script
        require_once 'inc/wphdp_mailpoet2_tracking_deaktivieren.php';
        // Alte Daten aus der Tracking Statistik entfernen
        $wphdp_table_name = $wpdb->prefix . "wysija_email_user_stat";
        $wpdb->query("TRUNCATE TABLE $wphdp_table_name");
    }
}
// Newsletter Unsubscribe
require_once 'inc/wphdp_nl_unsubscribe.php';

// Website-URL
if (isset($wp_h_data_protection_options['checkbox_4_3'])) { // Wenn aktiviert, lade Script
    require_once 'inc/wphdp_site_url.php';
}
// Link zum Kontaktformular
if (isset($wp_h_data_protection_options['checkbox_5_4'])) { // Wenn aktiviert, lade Script
    require_once 'inc/wphdp_kontakt_form.php';
}
// Link zum Anmeldeformular
if (isset($wp_h_data_protection_options['checkbox_6_5'])) { // Wenn aktiviert, lade Script
    require_once 'inc/wphdp_anmelde_form.php';
}
// Link zum G&auml;stebuch
if (isset($wp_h_data_protection_options['checkbox_7_6'])) { // Wenn aktiviert, lade Script
    require_once 'inc/wphdp_guestbook.php';
}
; //////////////////////////////////////////////////////////////////////////////////////////
?>