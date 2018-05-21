<?php
// Kommentare, WP H-Guestbook - Cookie-Checkbox entfernen
// Quelle: https://forum.wpde.org/threads/neue-checkbox-im-kommentarbereich.182033/
function wphdp_remove_comment_checkbox( $fields ) {
unset($fields['cookies']);
return $fields;
}
add_filter( 'comment_form_default_fields', 'wphdp_remove_comment_checkbox' );
?>