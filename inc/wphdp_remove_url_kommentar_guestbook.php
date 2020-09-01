<?php
// Feld "Website" aus Kommentar-Formular und optional Gästebuch "WP H-Guestbook" entfernen
function remove_url_comment_guestbook()
{
    wp_register_style('wphdp_remove_url_comment_guestbook', plugins_url('wp-h-data-protection/css/wphdp_remove_url_comment_guestbook.css')); // CSS-Datei einbinden
    wp_enqueue_style('wphdp_remove_url_comment_guestbook');
}
add_action('wp_enqueue_scripts', 'remove_url_comment_guestbook');
?>