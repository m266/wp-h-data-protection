<?php
// Newsletter Unsubscribe
$wphdp_mail = $wp_h_data_protection_options['textbox_1']; // E-Mail-Adresse auslesen
function wphdp_nl_unsubscribe_function() {
    global $wphdp_mail;
    $wphdp_mailto = "mailto:" . $wphdp_mail;
    $wphdp_kontakt_mail = "<a href='$wphdp_mailto'>$wphdp_mail</a>";
    return $wphdp_kontakt_mail;
}
add_shortcode('wphdp_nl_unsubscribe', 'wphdp_nl_unsubscribe_function');
?>