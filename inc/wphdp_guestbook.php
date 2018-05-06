<?php
// Link zum Gästebuch
function wphdp_guestbook_function() {
    $wphdp_site_url = site_url();
    $wphdp_page = "/gaestebuch/";
    $wphdp_page_link = $wphdp_site_url . $wphdp_page;
    $wphdp_guestbook_link = "(<a href='$wphdp_page_link'>G&auml;stebuch</a>)";
    return $wphdp_guestbook_link;
}
add_shortcode('wphdp_guestbook', 'wphdp_guestbook_function');
?>
