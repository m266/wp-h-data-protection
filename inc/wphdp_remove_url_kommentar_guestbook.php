<?php
// Feld "Website" aus Kommentar-Formular und optional Gästebuch "WP H-Guestbook" entfernen
function remove_url_comment_guestbook()
{
?>
<style>
#commentform .comment-form-url {
    display: none;
}
</style>
<?php
}
add_action('wp_enqueue_scripts', 'remove_url_comment_guestbook');
?>