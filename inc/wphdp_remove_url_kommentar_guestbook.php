<?php
// Feld "Website" aus Kommentar-Formular und optional Gästebuch WP H-Guestbook entfernen
    add_filter('comment_form_default_fields', 'remove_url_comment_form');
    function remove_url_comment_form($wphdp_fields)
    {
    if(isset($wphdp_fields['url']))
    unset($wphdp_fields['url']);
    return $wphdp_fields;
    }
?>
