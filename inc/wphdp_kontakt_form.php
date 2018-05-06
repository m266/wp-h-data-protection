<?php
// Link zum Kontaktformular
function wphdp_kontakt_form_function() {
    $wphdp_site_url = site_url();
    $wphdp_page = "/kontakt/";
    $wphdp_page_link = $wphdp_site_url . $wphdp_page;
    $wphdp_kontakt_form_link = "(<a href='$wphdp_page_link'>Kontakt</a>)";
    return $wphdp_kontakt_form_link;
}
add_shortcode('wphdp_kontakt_form', 'wphdp_kontakt_form_function');
?>
