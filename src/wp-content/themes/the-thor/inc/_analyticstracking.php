<?php
if ( ! defined( 'ABSPATH' ) ) exit;

////////////////////////////////////////////////////////
// アナリティクス　トラッキングID
////////////////////////////////////////////////////////
function fit_add_analytics_id_head() {
    $ua_id  = get_option('fit_bsAccess_gaid');
    $ga4_id = get_option('fit_bsAccess_ga4id');

    if($ua_id || $ga4_id){
        $tracking_id     = '';
        $gtag_ua_code    = '';
        $gtag_ga4_code   = '';
    
        if( $ua_id ){
            $tracking_id   = $ua_id;
            $gtag_ua_code  = "gtag('config', '$ua_id');";
        }
        if( $ga4_id ){
            $tracking_id   = $ga4_id;
            $gtag_ga4_code = "gtag('config', '$ga4_id');";
        }
    
$str = <<<EOM
<!– Global site tag (gtag.js) – Google Analytics –>
<script async src='https://www.googletagmanager.com/gtag/js?id=$tracking_id'></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    $gtag_ua_code
    $gtag_ga4_code
</script>
<!– /Global site tag (gtag.js) – Google Analytics –>\n
EOM;
    
        echo $str;
    }
}
add_action( 'wp_head', 'fit_add_analytics_id_head', 99998 );