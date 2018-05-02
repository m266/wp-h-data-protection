<?php
// Credits: Plugin Name: MailPoet Remove tracking
/**
*
* @param string $site
*/
add_action('wysija_replaceusertags', 'wysija_remove_tracking',1,2);

function wysija_remove_tracking(){
remove_all_actions( 'wysija_replaceusertags', 11 );
add_action('wysija_replaceusertags', 'wysija_tracker_replaceusertags',13,2);
}

function wysija_tracker_replaceusertags($email,$user){
$urls = array();
$results = array();// collect all links in email
if(!preg_match_all('#href[ ]*=[ ]*"(?!mailto:|\#|ymsgr:|callto:|file:|ftp:|webcal:|skype:)([^"]+)"#Ui',$email->body,$results)) return;

$modelConf=WYSIJA::get('config','model');

foreach($results[1] as $i => $url){

if( !in_array($url, array( '[unsubscribe_link]', '[subscriptions_link]' , '[view_in_browser_link]' ) ) ){
continue;
}

$email_url = $url;

$args = array();
$args['email_id'] = $email->email_id;
$args['user_id'] = $user->user_id;
if(empty($user->user_id)) $args['demo']=1;
$args['urlpassed'] = base64_encode($email_url);
$args['controller'] = 'stats';

$page_id = $modelConf->getValue('confirm_email_link');
//if it's a system url that needs privacy we hash it
if(strpos($email_url, '[unsubscribe_link]')!==false){
$args['hash']=md5(AUTH_KEY.'[unsubscribe_link]'.$args['user_id']);
$page_id = $modelConf->getValue('unsubscribe_page');
}

if(strpos($email_url, '[subscriptions_link]')!==false){
$args['hash']=md5(AUTH_KEY.'[subscriptions_link]'.$args['user_id']);
$page_id = $modelConf->getValue('subscriptions_page');
}

$args['action'] = 'analyse';
$args['wysija-page'] = 1;

$mytracker=WYSIJA::get_permalink($modelConf->getValue($page_id),$args);

$urls[$results[0][$i]] = str_replace($url,$mytracker,$results[0][$i]);
}
$email->body = str_replace(array_keys($urls),$urls,$email->body);

}//endfct
?>