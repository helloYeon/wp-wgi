<?php
////////////////////////////////////////////////////////
// 投稿ページ メモ機能
////////////////////////////////////////////////////////

if ( ! function_exists( 'fit_add_memo_box' ) ) {
	/**
	 * メモ欄の追加
	 */
	function fit_add_memo_box() {
		add_meta_box( 'memodiv', 'メモ', 'fit_memo_form', 'post', 'side');
		add_meta_box( 'memodiv', 'メモ', 'fit_memo_form', 'page', 'side');
	}
	add_action('admin_menu', 'fit_add_memo_box');
}

if ( ! function_exists( 'fit_memo_form' ) ) {
	/**
	 * 入力フォームのHTML出力
	 */
	function fit_memo_form() {
		$memo = get_post_meta( get_the_ID(), 'fit_post_memo', true );
		?>
		<textarea rows="5" cols="30" name="fit_post_memo"><?php echo $memo; ?></textarea>
		<?php
	}
}

if ( ! function_exists( 'fit_update_memo' ) ) {
	/**
	 * 更新処理
	 */
	function fit_update_memo() {
		$memo = filter_input( INPUT_POST, 'fit_post_memo' );
		if ( $memo ) {
			update_post_meta( get_the_ID(), 'fit_post_memo', $memo );
		} else {
			delete_post_meta( get_the_ID(), 'fit_post_memo' );
		}
	}
	add_action('save_post', 'fit_update_memo');
}
