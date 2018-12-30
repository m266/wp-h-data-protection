<?php
// Newsletter Unsubscribe
$wphdp_mail = $wp_h_data_protection_options['textbox_1']; // E-Mail-Adresse auslesen
if (!$wphdp_mail == "") {
// Syntax der E-Mail-Adresse und Verfuegbarkeit der Domain checken
    // Quelle: http://www.gtkdb.de/index_4_1545.html
    function validate_mail($wphdp_mail) {
        if (preg_match("/^[\w\.-]{1,}\@([\w-]{1,}\.){1,}[\w-]{2,4}$/", $wphdp_mail) == 0) {
            return false;
        }

        list($prefix, $domain) = explode("@", $wphdp_mail);
        if (function_exists("getmxrr") && getmxrr($domain . '.', $mxhosts)) {
            return true;
        } elseif (function_exists("checkdnsrr") && checkdnsrr($domain . '.', 'MX')) {
            return true;
        } elseif (function_exists("checkdnsrr") && checkdnsrr($domain . '.', 'A')) {
            return true;
        } else {
            return false;
        }

    }
    if (validate_mail($wphdp_mail) == false) {

        function wphdp_email_syntax_error_domain_error_notice() {; // Syntax Error der E-Mail-Adresse oder Domain-Name fehlerhaft
            ?>
        <div class="error notice">  <!-- Wenn ja, Meldung ausgeben -->
                <p><?php _e('E-Mail-Adresse zur Newsletter-Abmeldung (Plugin <b>"WP H-Data Protection"</b>): Syntax Error der E-Mail-Adresse oder Domain-Name fehlerhaft!');?></p>
        </div>
                                                <?php
}
        add_action('admin_notices', 'wphdp_email_syntax_error_domain_error_notice');
    }
    function wphdp_nl_unsubscribe_function() {
        global $wphdp_mail;
        $wphdp_mailto = "mailto:" . $wphdp_mail;
        $wphdp_kontakt_mail = "<a href='$wphdp_mailto'>$wphdp_mail</a>";
        return $wphdp_kontakt_mail;
    }
    add_shortcode('wphdp_nl_unsubscribe', 'wphdp_nl_unsubscribe_function');
}
?>