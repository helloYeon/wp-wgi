<?php
////////////////////////////////////////////////////////
//PWA用JSONマニフェストの設定
////////////////////////////////////////////////////////

function fit_add_pwa() {
	$manifest_file = TEMPLATEPATH . '/js/manifest.json';
	if ( 'off' === get_option( 'fit_pwaFunction_switch', 'off' ) ) {
		// PWAがOFFの場合は処理終了
		if ( file_exists( $manifest_file ) ) {
			// マニフェストファイルがあれば削除する
			unlink( $manifest_file );
		}
		return;
	}

	$text    = get_bloginfo('name');
	$icon72  = get_template_directory_uri().'/img/pwa-72.png';
	$icon192 = get_template_directory_uri().'/img/pwa-192.png';
	$icon512 = get_template_directory_uri().'/img/pwa-512.png';
	if(get_option('fit_pwaFunction_text')){
		$text = get_option('fit_pwaFunction_text');
	}
	if(get_fit_pwa72()){
		$icon72 = get_fit_pwa72();
	}
	if(get_fit_pwa192()){
		$icon192 = get_fit_pwa192();
	}
	if(get_fit_pwa512()){
		$icon512 = get_fit_pwa512();
	}
	
	$contents = '
	{
		"name": "'.get_bloginfo('name').'",
		"short_name": "'.$text.'",
		"description": "'.get_bloginfo('description').'",
		"start_url": "/",
		"display": "standalone",
		"theme_color": "#191919",
		"background_color": "#ffffff",
		"icons": [{
			"src": "'.$icon72.'",
			"sizes": "72x72",
			"type": "image/png"
		}, {
			"src": "'.$icon192.'",
			"sizes": "192x192",
			"type": "image/png"
		}, {
			"src": "'.$icon512.'",
			"sizes": "512x512",
			"type": "image/png"
		}]
	}
	';
	if ( file_exists( $manifest_file ) ) {
		if ( date( 'YmdHis', filemtime( $manifest_file ) ) === date( 'YmdHis' ) ) {
			// 過剰なファイルの作成を避けるためすでに現在時刻と同じ時間にファイルが作成されている場合はファイルを作らない
			return;
		}
		chmod( $manifest_file, 0755 );
	}
	file_put_contents( $manifest_file, $contents );
}
add_action( 'after_setup_theme', 'fit_add_pwa' );