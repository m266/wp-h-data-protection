<?php
// Site-URL einbinden
function wphdp_site_url_function() {
    $wphdp_site_url = site_url();
    $wphdp_site_url_link = "<a href='$wphdp_site_url'>$wphdp_site_url</a>";
    return $wphdp_site_url_link;
}
add_shortcode('wphdp_site_url', 'wphdp_site_url_function');
?>
