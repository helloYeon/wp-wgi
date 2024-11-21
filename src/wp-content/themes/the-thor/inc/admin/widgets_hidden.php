<?php

global $pagenow;
if ( 'widgets.php' === $pagenow ) {
    
    // 表示オプションタブを表示
	function add_screen_options() {
		return true;
    }
    add_filter( 'manage_widgets_columns', 'add_widgets_title' );
    add_action( 'admin_footer', 'fit_not_checked' );
}

// ウィジェットエリアのチェックと連動して表示・非表示を切り替える
function fit_not_checked() {

    $url    = admin_url( 'admin-ajax.php' );
    $tmpt_path = get_template_directory_uri();
    $nonce = wp_create_nonce( "my-ajax-nonce" );

    $script = <<<EOF
<script>
jQuery(function($){

    // タイトルの書き換え
    $('.metabox-prefs legend').html('ウィジェット表示エリア');

    // ウィジェットの表示・非表示
    $('.hide-column-tog').on('change', function(){
        var hiddenids = [];
        var printids = [];
        var elm = "";

        // db登録
        const hide_widget = document.getElementsByClassName("hide-column-tog");
    
        for ( let i = 0; i < hide_widget.length; i++ ) {
            if( !hide_widget[i].checked ){ 
                hiddenids.push( hide_widget[i].value );
            } else {
                printids.push( hide_widget[i].value );
            }
        }
        var hiddens = hiddenids.join(',');

        $.ajax({
            type: 'POST',
            url:  '$url',
            data: {
                'action' : 'fit_hidden_widgets',
                'hidden' : hiddens,
                'page' : 'widgets',
                'nonce': '$nonce'
            },
        })

        // 画面上の表示・非表示
        for ( let i = 0; i < hiddenids.length; i++ ) {
            hidewidget =  '#' + hiddenids[i];
            $(hidewidget).parent().css('display','none');
        }
        for ( let i = 0; i < printids.length; i++ ) {
            printwidget =  '#' + printids[i];
            $(printwidget).parent().css('display','block');
        }

    });
    
});
</script>
EOF;

    echo $script;

}

// POSTで受け取った非表示のウィジェットの値をDBへ格納処理
function fit_hidden_widgets() {
    $hidden = "";

    $user = wp_get_current_user();
	if ( ! $user ) {
		wp_die( -1 );
    }
    
    $hidden = ! empty( $_POST['hidden'] ) ? explode( ',', $_POST['hidden'] ) : array();
    update_user_option( $user->ID, "managewidgetscolumnshidden", $hidden, true );

	wp_die( 1 );
}
add_action( 'wp_ajax_fit_hidden_widgets', 'fit_hidden_widgets' );

// 最初の画面のウィジェットエリアの表示・非表示
function fit_widgets_area_hidden() {
    global $wp_registered_sidebars;
    
    $user = wp_get_current_user();
	if ( ! $user ) {
		wp_die( -1 );
    }

    // 非表示設定されているウィジェットIDの取得
    $val = get_user_meta( $user->ID, 'managewidgetscolumnshidden' );
    if ( ! isset( $val[0] ) || !is_array($val[0]) ) {
        return;
    }
    $val = implode(",", $val[0]);

$script = <<<EOF
<script>
var val = '$val';
var hiddenids = val.split(",");
jQuery(function($){
    hidewidget = "";
    for ( let i = 0; i < hiddenids.length; i++ ) {
        hidewidget =  '#' + hiddenids[i]
        $(hidewidget).parent().css('display','none');
    }
});
</script>
EOF;

echo $script;
}
add_action( 'admin_head', 'fit_widgets_area_hidden' );

// ウィジェットエリア名を取得
function add_widgets_title() {

    $widget_array = get_option('widget_raindrops_pinup_entry_widget');
    $widget_areas = $GLOBALS['wp_registered_sidebars'];
    $widget_areas_name = array();

    foreach ( $widget_areas as $id => $widget_area ) {
        $widget_areas_name[$widget_area['id']]  = $widget_area['name'];
    }

    return $widget_areas_name;
}