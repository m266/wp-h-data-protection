<?php
// Link zum Anmeldeformular
function wphdp_anmelde_form_function() {
    $wphdp_site_url = site_url();
    $wphdp_page = "/anmeldeformular/";
    $wphdp_page_link = $wphdp_site_url . $wphdp_page;
    $wphdp_anmelde_form_link = "(<a href='$wphdp_page_link'>Anmeldeformular</a>)";
    return $wphdp_anmelde_form_link;
}
add_shortcode('wphdp_anmelde_form', 'wphdp_anmelde_form_function');
?>
