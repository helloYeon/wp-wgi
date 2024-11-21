<?php
/**
 * ランキング機能の関数用ファイル
 */

if ( ! function_exists( 'fit_init_bsRank_startDatetime' ) ) {
	/**
	 * 日週月ランク用の集計開始日時初期化
	 */
	function fit_init_bsRank_startDatetime() {
		$rank = new Fit_Rank();

		if ( ! $rank->start_datetime ) {
			$rank->set_start_datetime();
		}
	}
	add_action( 'init_fit_post_accesslog_table', 'fit_init_bsRank_startDatetime' );
}

if ( ! function_exists( 'fit_update_database_post_views_script' ) ) {
	/**
	 * 投稿とタグのアクセス情報をDB登録するためのAjaxスクリプトを出力する
	 *
	 * @return void
	 */
	function fit_update_database_post_views_script( $postId ) {
		$url    = admin_url( 'admin-ajax.php' );
		$script = <<<EOS
<script>
jQuery( function( $ ) {
	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action' : 'fit_update_post_view_data',
			'post_id' : '$postId',
		},
	} );
} );
</script>
EOS;

		echo $script;
	}
}

if ( ! function_exists( 'fit_update_meta_post_views_by_period_script' ) ) {
	/**
	 * 日週月ランキングのデータ更新用スクリプトを出力する
	 */
	function fit_update_meta_post_views_by_period_script() {
		$url    = admin_url( 'admin-ajax.php' );
		$script = <<<EOS
<script>
jQuery( function() {
	setTimeout(
		function() {
			var realtime = 'false';
			if ( typeof _wpCustomizeSettings !== 'undefined' ) {
				realtime = _wpCustomizeSettings.values.fit_bsRank_realtime;
			}
			jQuery.ajax( {
				type: 'POST',
				url:  '$url',
				data: {
					'action'           : 'fit_update_post_views_by_period',
					'preview_realtime' : realtime,
				},
			} );
		},
		200
	);
} );
</script>
EOS;

		echo $script;
	}
	if ( get_option( 'fit_bsRank_should_aggregate', 1 ) ) {
		add_action( 'wp_footer', 'fit_update_meta_post_views_by_period_script', 30 );
		add_action( 'admin_footer', 'fit_update_meta_post_views_by_period_script', 30 );
	}
}

if ( ! function_exists( 'fit_add_ranking_box_script' ) ) {
	/**
	 * TOPページにアクセスランキングを表示するスクリプト
	 */
	function fit_add_ranking_box_script() {
		$url    = admin_url( 'admin-ajax.php' );
		$script = <<<EOS
<script>
jQuery( function( $ ) {
	var realtime        = 'false';
	var rank_period     = 'false';
	var rank_number     = 'false';
	var rank_conditions = 'false';
	var rank_id         = 'false';
	var rank_aspect     = 'false';
	var rank_category   = 'false';
	var rank_time       = 'false';
	var rank_update     = 'false';
	var rank_view       = 'false';
	if ( typeof _wpCustomizeSettings !== 'undefined' ) {
		realtime        = _wpCustomizeSettings.values.fit_bsRank_realtime;
		rank_period     = _wpCustomizeSettings.values.fit_homeRank_period;
		rank_number     = _wpCustomizeSettings.values.fit_homeRank_number;
		rank_conditions = _wpCustomizeSettings.values.fit_homeRank_conditions;
		rank_id         = _wpCustomizeSettings.values.fit_homeRank_id;
		rank_aspect     = _wpCustomizeSettings.values.fit_homeRank_aspect;
		rank_category   = _wpCustomizeSettings.values.fit_homeRank_category;
		rank_time       = _wpCustomizeSettings.values.fit_homeRank_time;
		rank_update     = _wpCustomizeSettings.values.fit_homeRank_update;
		rank_view       = _wpCustomizeSettings.values.fit_homeRank_view;
	}

	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action' : 'fit_add_ranking_box',
			'preview_realtime'        : realtime,
			'preview_rank_period'     : rank_period,
			'preview_rank_number'     : rank_number,
			'preview_rank_conditions' : rank_conditions,
			'preview_rank_id'         : rank_id,
			'preview_rank_aspect'     : rank_aspect,
			'preview_rank_category'   : rank_category,
			'preview_rank_time'       : rank_time,
			'preview_rank_update'     : rank_update,
			'preview_rank_view'       : rank_view,
		},
	} )
	.done( function( data ) {
		$( '.rankingBox__inner' ).html( data );
	} );
} );
</script>
EOS;

		echo $script;
	}
}

if ( ! function_exists( 'fit_add_ranklist_scode_script' ) ) {
	/**
	 * ランキング表示ショートコードのHTMLを生成するスクリプト
	 */
	function fit_add_ranklist_scode_script( $id, $atts ) {
		$url    = admin_url( 'admin-ajax.php' );
		$num    = $atts['num'];
		$cat    = $atts['cat'];
		$tag    = $atts['tag'];
		$writer = $atts['writer'];
		$period = $atts['period'];
		$script = <<<EOS
<script>
jQuery( function( $ ) {
	var realtime = 'false';
	if ( typeof _wpCustomizeSettings !== 'undefined' ) {
		realtime = _wpCustomizeSettings.values.fit_bsRank_realtime;
	}

	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action' : 'fit_add_ranklist_scode',
			'num' : '$num',
			'cat' : '$cat',
			'tag' : '$tag',
			'writer' : '$writer',
			'period' : '$period',
			'preview_realtime' : realtime,
		},
	} )
	.done( function( data ) {
		$( '#ranklist-$id' ).html( data );
	} );
} );
</script>
EOS;

		echo $script;
	}
}

if ( ! function_exists( 'fit_add_rank_widget_script' ) ) {
	/**
	 * 人気記事ウィジェットのHTMLを生成するスクリプト
	 */
	function fit_add_rank_widget_script( $id, $instance ) {
		$url      = admin_url( 'admin-ajax.php' );
		$instance = json_encode( $instance );
		$script = <<<EOS
<script>
var fitRankWidgetCount = fitRankWidgetCount || 0;
jQuery( function( $ ) {
	var realtime = 'false';
	if ( typeof _wpCustomizeSettings !== 'undefined' ) {
		realtime = _wpCustomizeSettings.values.fit_bsRank_realtime;
	}

	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action':   'fit_add_rank_widget',
			'instance': '$instance',
			'preview_realtime' : realtime,
		},
	} )
	.done( function( data ) {
		$( '#rankwidget-$id' ).html( data );
	} )
	.always( function() {
		fitRankWidgetCount++;
		$( document ).trigger( 'fitGetRankWidgetEnd' );
	} );
} );
</script>
EOS;

		echo $script;
	}
}

if ( ! function_exists( 'fit_add_category_rank_widget_script' ) ) {
	/**
	 * カテゴリ人気記事ウィジェットのHTMLを生成するスクリプト
	 */
	function fit_add_category_rank_widget_script( $id, $instance ) {
		$url      = admin_url( 'admin-ajax.php' );
		$instance = json_encode( $instance );
		$script   = <<<EOS
<script>
var fitRankWidgetCount = fitRankWidgetCount || 0;
jQuery( function( $ ) {
	var realtime = 'false';
	if ( typeof _wpCustomizeSettings !== 'undefined' ) {
		realtime = _wpCustomizeSettings.values.fit_bsRank_realtime;
	}

	$.ajax( {
		type: 'POST',
		url:  '$url',
		data: {
			'action':   'fit_add_category_rank_widget',
			'instance': '$instance',
			'preview_realtime' : realtime,
		},
	} )
	.done( function( data ) {
		$( '#categoryrankwidget-$id' ).html( data );
	} )
	.always( function() {
		fitRankWidgetCount++;
		$( document ).trigger( 'fitGetRankWidgetEnd' );
	} );
} );
</script>
EOS;

		echo $script;
	}
}

/**
 * アクセス数ランキングを表示するショートコードとウィジェットの識別用IDを生成する
 */
function fit_ranklist_id_hash() {
	$now = new DateTime();
	return md5( $now->format( 'YmdHisu' ) . mt_rand( 10, 99 ) );
}
