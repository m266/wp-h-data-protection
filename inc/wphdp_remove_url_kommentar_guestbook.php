<?php
// Feld "Website" aus Kommentar-Formular und optional Gästebuch "WP H-Guestbook" entfernen
function remove_comment_fields($fields) {
unset($fields['url']);
return $fields;
}
add_filter('comment_form_default_fields', 'remove_comment_fields');
?>