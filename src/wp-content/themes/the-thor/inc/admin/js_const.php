<?php

if ( ! function_exists( 'fit_add_hidden_parts' ) ) {
	/**
	 * 投稿編集画面の表示制御用にhiddenを追加する
	 */
	function fit_add_hidden_parts() {

		// 吹き出し左名前
		if ( get_option( 'fit_partsList_balloonNameLeft' ) ) {
			echo '<input type="hidden" name="hid-leftname" id="hid-leftname" value="' . get_option( 'fit_partsList_balloonNameLeft' ) . '">';
		} else {
			echo '<input type="hidden" name="hid-leftname" id="hid-leftname" value="Name">';
		}

		// 吹き出し右名前
		if ( get_option( 'fit_partsList_balloonNameRight' ) ) {
			echo '<input type="hidden" name="hid-rightname" id="hid-rightname" value="' . get_option( 'fit_partsList_balloonNameRight' ) . '">';
		} else {
			echo '<input type="hidden" name="hid-rightname" id="hid-rightname" value="Name">';
		}

		// 目次の表示設定
		global $post_type;
		if ( 'post' === $post_type ) {
			// 投稿
			echo '<input type="hidden" name="outline-switch" id="outline-switch" value="' . get_option( 'fit_postOutline_switch' ) . '">';
		} elseif ( 'page' === $post_type ) {
			// 固定ページ
			echo '<input type="hidden" name="outline-switch" id="outline-switch" value="' . get_option( 'fit_pageOutline_switch' ) . '">';
		} else {
			// 投稿でも固定ページでもない場合
			echo '<input type="hidden" name="outline-switch" id="outline-switch" value="">';
		}

	}
	add_action( 'edit_form_top', 'fit_add_hidden_parts' );
}
