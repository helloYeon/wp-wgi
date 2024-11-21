<?php
/**
 * REST-APIの初期設定用ファイル
 */

if ( ! function_exists( 'add_rest_endpoint_posts_ranking' ) ) {
	/**
	* 人気記事取得用APIのエンドポイントの追加
	*/
	function add_rest_endpoint_posts_ranking() {
		$post_ranking_api = new Fit_REST_API_Posts_Ranking();
		$post_ranking_api->register_routes();
	}
	add_action( 'rest_api_init', 'add_rest_endpoint_posts_ranking' );
}
