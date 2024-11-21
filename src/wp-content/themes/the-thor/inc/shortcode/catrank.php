<?php
////////////////////////////////////////////////////////
//カテゴリ指定ランキング記事一覧ショートコード
////////////////////////////////////////////////////////

/*
記事のランキングを表示するショートコード
使い方: [ranklist num="5" cat="1,7" tag="-1" writer="1" period="a"]
属性:
	num:    記事の表示件数
			省略時や0以下の数字の場合は5件
	cat:    絞り込むカテゴリーのID 複数指定する場合はカンマ区切り
			正の整数: 指定したカテゴリーに属する投稿を表示する
			負の整数: 指定したカテゴリーに属する投稿を除外する
			省略した場合 カテゴリーでの絞り込みは行わない
	tag:    絞り込むタグのID 複数指定する場合はカンマ区切り
			正の整数: 指定したタグに関連付けられた投稿を表示する
			負の整数: 指定したタグに関連付けられた投稿を除外する
			省略した場合 タグでの絞り込みは行わない
	writer: 絞り込む投稿者のID 複数指定する場合はカンマ区切り
			正の整数: 指定した投稿者の投稿を表示する
			負の整数: 指定した投稿者の投稿を除外する
			省略した場合 投稿者での絞り込みは行わない
	period: ランキングを集計する期間
			a: 全期間ランキング
			m: 月ランキング
			w: 週ランキング
			d: 日ランキング
			省略した場合 全期間ランキング
*/

function fit_get_catlistRank( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'num'    => '5',
			'cat'    => '',
			'tag'    => '',
			'writer' => '',
			'period' => 'a',
		),
		$atts,
		'ranklist'
	);

	$retHtml = '';

	if ( isset( $_GET['type'] )
		&& $_GET['type'] == 'AMP'
		&& is_single()
		&& get_option( 'fit_ampFunction_switch' ) == 'on'
		&& get_post_meta( get_the_ID(), 'amp_hide', true ) != '1'
		&& is_ssl() ) {
		// AMPページ
		$query_param = '';
		$query_param .= '?num=' . $atts['num'];
		$query_param .= '&period=' . $atts['period'];
		$query_param .= '&amp=true';
		if ( isset( $atts['cat'] ) ) {
			$query_param .= '&cat=' . $atts['cat'];
		}
		if ( isset( $atts['tag'] ) ) {
			$query_param .= '&tag=' . $atts['tag'];
		}
		if ( isset( $atts['writer'] ) ) {
			$query_param .= '&writer=' . $atts['writer'];
		}
		$site_url = get_site_url();
		$eyecatch_hover = ( get_option( 'fit_bsEyecatch_hover' ) && get_option( 'fit_bsEyecatch_hover' ) != 'off' ) ? 'eyecatch__link-' . get_option( 'fit_bsEyecatch_hover' ) : '';
		$retHtml .= <<<EOD
<amp-list
	class="archiveScode archiveScode-rank"
	template="rank-scode-template"
	layout="flex-item"
	src="{$site_url}/wp-json/wp/fit/posts-ranking/{$query_param}"
	items="."
	single-item
	binding="no">
	<div placeholder>データを取得中</div>
	<div fallback>データの取得に失敗しました。</div>
</amp-list>

<template type="amp-mustache" id="rank-scode-template">
	{{#items}}
		<div class="archiveScode__item">
			<div class="eyecatch eyecatch-11">
				<a class="eyecatch__link {$eyecatch_hover}" href="{{link}}">
					<amp-img src="{{thumbnail_src}}" alt="{{thumbnail_alt}}" width="{{thumbnail_width}}" height="{{thumbnail_height}}" layout="responsive"></amp-img>
				</a>
			</div>
			<div class="archiveScode__contents">
				<div class="the__category cc-bg{{term_id}}">
					<a href="{{cat_link}}">{{cat_name}}</a>
				</div>
				<div class="heading heading-secondary"><a href="{{link}}">{{post_title}}</a></div>
				<p class="phrase phrase-tertiary">{{excerpt}}</p>
			</div>
		</div>
	{{/items}}
	{{^items}}
		<div class="archiveScode__item archiveScode__item-no">
			<p>対象期間のランキングデータが1件も見つかりませんでした。</p>
		</div>
	{{/items}}
</template>
EOD;
	} else {
		// AMPではない
		// フッターにランキング取得用のスクリプト追加
		$id              = fit_ranklist_id_hash();
		$get_rank_action = new Fit_Get_Rank_Action( $id, Fit_Get_Rank_Action::TYPE_SCODE, array( 'atts' => $atts ) );
		$get_rank_action->add_wp_footer();

		$retHtml .= '<div class="archiveScode archiveScode-rank" id="ranklist-' . $id . '">';
		$retHtml .= '    <div class="archiveScode__item archiveScode__item-no archiveScode__item-offline rank-offline">';
		$retHtml .= '        <p>オフラインのためランキングが表示できません</p>';
		$retHtml .= '    </div>';
		$retHtml .= '</div>';
	}

	return $retHtml;
}
add_shortcode( 'ranklist', 'fit_get_catlistRank' );
