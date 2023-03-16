<?php
if (!is_admin()) { // Beantworten im Dashboard wieder möglich
    // Quelle: https://wordpress.stackexchange.com/questions/127027/how-to-add-a-privacy-checkbox-in-comment-template
    //add your checkbox after the comment field
    add_filter('comment_form_field_comment', 'wphdp_comment_form_field_comment');
    function wphdp_comment_form_field_comment($comment_field)
    {
        if (!empty(get_the_privacy_policy_link())) {
            return $comment_field . '<input type="checkbox" name="privacy" value="privacy-key" class="privacyBox" aria-req="true"><p class="pprivacy">Ich stimme der Speicherung und Verarbeitung meiner Daten nach der DSGVO zu und akzeptiere die ' . ((!empty($url = get_privacy_policy_url())) ? '<a class="privacy-policy-link" href="' . esc_url($url) . '">Datenschutzbedingungen</a>' : 'Datenschutzbedingungen') . '. *</p>';
        } else {
            global $wphdp_privacy_link;
            return $comment_field . '<input type="checkbox" name="privacy" value="privacy-key" class="privacyBox" aria-req="true"><p class="pprivacy">Ich stimme der Speicherung und Verarbeitung meiner Daten nach der DSGVO zu und akzeptiere die <a class="privacy-policy-link" href="' . esc_url(!empty($url = get_privacy_policy_url()) ? $url : $wphdp_privacy_link) . '">Datenschutzbedingungen</a>. *</p>';
        }
    }
//javascript validation
    add_action('wp_footer', 'valdate_privacy_comment_javascript');
    function valdate_privacy_comment_javascript()
    {
        if (is_single() && comments_open()) {
            wp_enqueue_script('jquery');
            ?>
        <script type="text/javascript">
        jQuery(document).ready(function($){
            $("#submit").click(function(e){
                if (!$('.privacyBox').prop('checked')){
                    e.preventDefault();
                    alert('<b>Fehler:</b> Bitte zum Akzeptieren der Datenverarbeitung die Checkbox anklicken.');
                    return false;
                }
            })
        });
        </script>
        <?php
}
    }
//no js fallback validation
    add_filter('preprocess_comment', 'verify_comment_privacy');
    function verify_comment_privacy($commentdata)
    {
        if (!isset($_POST['privacy'])) {
            wp_die(__('<b>Fehler:</b> Bitte zum Akzeptieren der Datenverarbeitung die Checkbox anklicken.'));
        }

        return $commentdata;
    }
}
?>